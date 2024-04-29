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
                $data = User::where('status', 1)->where('role', 1);
                if($request->filled('search')){
                    $data->whereRaw("(`name` LIKE '%" . $request->search . "%') or `email` LIKE '%" . $request->search . "%'");
                }
                $data = $data->get();
                $html = '';
                foreach($data as $val){
                    $fire = FirebaseChat::where('user_id', $val->id)->first();
                    if(isset($fire->updated_at)){
                        if(date('H:i') == date('H:i', strtotime($fire->updated_at)))
                            $time = 'Just Now';
                        elseif(date('Y-m-d') == date('Y-m-d', strtotime($fire->updated_at)))
                            $time = date('h:i A', strtotime($fire->updated_at));
                        elseif(date('Y-m-d',strtotime("-1 days")) == date('Y-m-d', strtotime($fire->updated_at)))
                            $time = 'Yesterday';
                        elseif(date('Y-m-d',strtotime("-2 days")) == date('Y-m-d', strtotime($fire->updated_at)))
                            $time = date('D', strtotime($fire->updated_at));
                        elseif(date('Y-m-d',strtotime("-3 days")) == date('Y-m-d', strtotime($fire->updated_at)))
                            $time = date('D', strtotime($fire->updated_at));
                        elseif(date('Y-m-d',strtotime("-4 days")) == date('Y-m-d', strtotime($fire->updated_at)))
                            $time = date('D', strtotime($fire->updated_at));
                        elseif(date('Y-m-d',strtotime("-5 days")) == date('Y-m-d', strtotime($fire->updated_at)))
                            $time = date('D', strtotime($fire->updated_at));
                        elseif(date('Y-m-d',strtotime("-6 days")) == date('Y-m-d', strtotime($fire->updated_at)))
                            $time = date('D', strtotime($fire->updated_at));
                        elseif(date('Y-m-d',strtotime("-7 days")) == date('Y-m-d', strtotime($fire->updated_at)))
                            $time = date('D', strtotime($fire->updated_at));
                        else $time = date('d M, Y', strtotime($fire->updated_at));
                    } else $time = '';
                    
                    $lastMsg = $fire->last_msg ?? 'No messages';
                    $unseenCout = (isset($fire->unseen_msg_count) && ($fire->unseen_msg_count!=0)) ? "<span class='badge bg-danger rounded-pill float-end  unseen-count-$val->id'>$fire->unseen_msg_count</span>" : "";
                    $userProfileImage = isset($val->profile) ? assets("uploads/profile/".$val->profile) : assets("assets/images/no-image.jpg");
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

    public function chatImage(Request $request){
        try {
            $name = fileUpload($request->image, "/uploads/chat/");  
            return response()->json(['status' => true, 'url' => $name, 'message' => 'image upload successfully.']);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

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
                $fire->updated_at = $fire->updated_at;
                $fire->save();
                return successMsg('Message seen');
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

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
                    $fire->updated_at = date('Y-m-d H:i:s');
                    $fire->save();
                } else {
                    $fire = new FirebaseChat;
                    $fire->user_id = $request->user_id;
                    $fire->last_msg = $request->msg;
                    $fire->save();
                }
                return successMsg('Record updated successfully');
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
