<?php

namespace App\Http\Controllers;

use App\Models\FirebaseChat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    // Dev name : Dishant Gupta
    // This function is used to view a chat screen
    public function chat(Request $request)
    {
        try {
            if($request->ajax()){
                $data = User::leftJoin('firebase_chats as fc', 'fc.user_id', '=', 'users.id')->where('users.status', 1)->where('users.role', 1);
                if($request->filled('search')){
                    $data->whereRaw("(`name` LIKE '%" . $request->search . "%') or `email` LIKE '%" . $request->search . "%'");
                }
                $data = $data->select('users.id', 'users.name', 'users.email', 'users.profile', 'fc.updated_at', 'fc.unseen_msg_count', 'fc.last_msg', 'last_msg_datetime')->orderByDesc('fc.last_msg_datetime')->distinct('users.id')->get();
                // dd($data);
                $html = '';
                foreach($data as $val){
                    if(isset($val->last_msg_datetime)){
                        if(date('H:i') == date('H:i', strtotime($val->last_msg_datetime)))
                            $time = 'Just Now';
                        elseif(date('Y-m-d') == date('Y-m-d', strtotime($val->last_msg_datetime)))
                            $time = date('h:i A', strtotime($val->last_msg_datetime));
                        elseif(date('Y-m-d',strtotime("-1 days")) == date('Y-m-d', strtotime($val->last_msg_datetime)))
                            $time = 'Yesterday';
                        elseif(date('Y-m-d',strtotime("-2 days")) == date('Y-m-d', strtotime($val->last_msg_datetime)))
                            $time = date('D', strtotime($val->last_msg_datetime));
                        elseif(date('Y-m-d',strtotime("-3 days")) == date('Y-m-d', strtotime($val->last_msg_datetime)))
                            $time = date('D', strtotime($val->last_msg_datetime));
                        elseif(date('Y-m-d',strtotime("-4 days")) == date('Y-m-d', strtotime($val->last_msg_datetime)))
                            $time = date('D', strtotime($val->last_msg_datetime));
                        elseif(date('Y-m-d',strtotime("-5 days")) == date('Y-m-d', strtotime($val->last_msg_datetime)))
                            $time = date('D', strtotime($val->last_msg_datetime));
                        elseif(date('Y-m-d',strtotime("-6 days")) == date('Y-m-d', strtotime($val->last_msg_datetime)))
                            $time = date('D', strtotime($val->last_msg_datetime));
                        elseif(date('Y-m-d',strtotime("-7 days")) == date('Y-m-d', strtotime($val->last_msg_datetime)))
                            $time = date('D', strtotime($val->last_msg_datetime));
                        else $time = date('d M, Y', strtotime($val->last_msg_datetime));
                    } else $time = '';
                    
                    $lastMsg = $val->last_msg ?? 'No messages';
                    $unseenCout = (isset($val->unseen_msg_count) && ($val->unseen_msg_count!=0)) ? "<span class='badge bg-danger rounded-pill float-end  unseen-count-$val->id'>$val->unseen_msg_count</span>" : "";
                    $userProfileImage = isset($val->profile) ? assets("uploads/profile/".$val->profile) : assets("assets/images/avatar.png");
                    $html .= "<li class='p-2 border-bottom user-info' data-id='$val->id' data-name='$val->name' data-img='$userProfileImage'>
                        <a href='javascript:void(0)' class='d-flex justify-content-between'>
                            <div class='d-flex flex-row'>
                                <div>
                                    <img style='border-radius: 50%; object-fit: cover; object-position: center;' src='$userProfileImage' alt='avatar' class='d-flex align-self-center me-3' width='60' height='60'>
                                    <span class='badge bg-success badge-dot'></span>
                                </div>
                                <div class='pt-1'>
                                    <p class='chat-name fw-bold mb-0' style='color: #1079c0; font-size: 0.8rem;'>$val->name</p>
                                    <p class='small text-muted last-message-$val->id'>$lastMsg</p>
                                </div>
                            </div>
                            <div class='pt-1 text-end' style='width: 20%;'>
                                <p class='small text-muted mb-1 time-message-$val->id'>$time</p>
                                $unseenCout
                            </div>
                        </a>
                    </li>";
                }

                if(count($data) < 1) return errorMsg("No users found");
                $response = array(
                    'html' => $html,
                );
                return successMsg('User list', $response);
            }
            return view('pages.admin.support.chat');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to upload a chat image
    public function chatImage(Request $request){
        try {
            $name = fileUpload($request->image, "/uploads/chat/");  
            return response()->json(['status' => true, 'url' => $name, 'message' => 'image upload successfully.']);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to change the seen count of chats
    public function chatRecordSeen(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required'
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $fire = FirebaseChat::where('user_id', $request->user_id)->first(); 
                $fire->unseen_msg_count = 0;
                $fire->save();
                return successMsg('Message seen');
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to manage the last message of chats
    public function chatRecord(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'msg' => 'required',
                'user_id' => 'required'
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $fire = FirebaseChat::where('user_id', $request->user_id)->first();
                if(isset($fire->id)){
                    $fire->last_msg = $request->msg;
                    $fire->user_unseen_msg_count = $fire->user_unseen_msg_count+1;
                    $fire->last_msg_datetime = date('Y-m-d H:i:s');
                    $fire->save();
                } else {
                    $fire = new FirebaseChat;
                    $fire->user_id = $request->user_id;
                    $fire->last_msg = $request->msg;
                    $fire->user_unseen_msg_count = $fire->user_unseen_msg_count+1;
                    $fire->last_msg_datetime = date('Y-m-d H:i:s');
                    $fire->status = 1;
                    $fire->save();
                }
                return successMsg('Record updated successfully');
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
