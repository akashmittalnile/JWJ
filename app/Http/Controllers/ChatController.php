<?php

namespace App\Http\Controllers;

use App\Models\FirebaseChat;
use App\Models\User;
use App\Models\UserPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    // Dev name : Dishant Gupta
    // This function is used to view a chat screen
    public function chat(Request $request, $id = null)
    {
        try {
            $data = User::leftJoin('firebase_chats as fc', 'fc.user_id', '=', 'users.id')->where('users.status', 1)->where('users.role', 1);
            if ($request->filled('search')) {
                $data->whereRaw("(`users`.`name` LIKE '%" . $request->search . "%') or `users`.`email` LIKE '%" . $request->search . "%'")->where('users.status', 1)->where('users.role', 1);
            }
            $data = $data->select('users.id', 'users.name', 'users.email', 'users.profile', 'fc.updated_at', 'fc.unseen_msg_count', 'fc.last_msg', 'last_msg_datetime')->orderByDesc('fc.last_msg_datetime')->distinct('users.id')->get();
            $users = array();
            foreach ($data as $val) {
                if (isset($val->last_msg_datetime)) {
                    if (date('H:i') == date('H:i', strtotime($val->last_msg_datetime)))
                        $time = 'Just Now';
                    elseif (date('Y-m-d') == date('Y-m-d', strtotime($val->last_msg_datetime)))
                        $time = date('h:i A', strtotime($val->last_msg_datetime));
                    elseif (date('Y-m-d', strtotime("-1 days")) == date('Y-m-d', strtotime($val->last_msg_datetime)))
                        $time = 'Yesterday';
                    elseif (date('Y-m-d', strtotime("-2 days")) == date('Y-m-d', strtotime($val->last_msg_datetime)))
                        $time = date('D', strtotime($val->last_msg_datetime));
                    elseif (date('Y-m-d', strtotime("-3 days")) == date('Y-m-d', strtotime($val->last_msg_datetime)))
                        $time = date('D', strtotime($val->last_msg_datetime));
                    elseif (date('Y-m-d', strtotime("-4 days")) == date('Y-m-d', strtotime($val->last_msg_datetime)))
                        $time = date('D', strtotime($val->last_msg_datetime));
                    elseif (date('Y-m-d', strtotime("-5 days")) == date('Y-m-d', strtotime($val->last_msg_datetime)))
                        $time = date('D', strtotime($val->last_msg_datetime));
                    elseif (date('Y-m-d', strtotime("-6 days")) == date('Y-m-d', strtotime($val->last_msg_datetime)))
                        $time = date('D', strtotime($val->last_msg_datetime));
                    elseif (date('Y-m-d', strtotime("-7 days")) == date('Y-m-d', strtotime($val->last_msg_datetime)))
                        $time = date('D', strtotime($val->last_msg_datetime));
                    else $time = date('d M, Y', strtotime($val->last_msg_datetime));
                } else $time = '';
                $lastMsg = $val->last_msg ?? 'No messages';

                $temp['id'] = $val->id;
                $temp['last_msg'] = $lastMsg;
                $temp['name'] = $val->name;
                $temp['email'] = $val->email;
                $temp['profile'] = $val->profile;
                $temp['unseen_msg_count'] = $val->unseen_msg_count;
                $temp['time'] = $time;
                $users[] = $temp;
            }

            $id = encrypt_decrypt('decrypt', $id);
            $user = User::where('id', $id)->first();
            $userPlan = UserPlan::join('plan as p', 'p.id', '=', 'user_plans.plan_id')->where('user_plans.user_id', $id)->where('user_plans.status', 1)->select('p.name')->first();
            return view('pages.admin.support.chat')->with(compact('users', 'user', 'userPlan'));
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to upload a chat image
    public function chatImage(Request $request)
    {
        try {
            $name = fileUpload($request->image, "/uploads/chat/");
            return response()->json(['status' => true, 'url' => $name, 'message' => 'image upload successfully.']);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to change the seen count of chats
    public function chatRecordSeen(Request $request)
    {
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
    public function chatRecord(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'msg' => 'required',
                'user_id' => 'required'
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $fire = FirebaseChat::where('user_id', $request->user_id)->first();
                if (isset($fire->id)) {
                    $fire->last_msg = $request->msg;
                    $fire->user_unseen_msg_count = $fire->user_unseen_msg_count + 1;
                    $fire->last_msg_datetime = date('Y-m-d H:i:s');
                    $fire->save();
                } else {
                    $fire = new FirebaseChat;
                    $fire->user_id = $request->user_id;
                    $fire->last_msg = $request->msg;
                    $fire->user_unseen_msg_count = $fire->user_unseen_msg_count + 1;
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
