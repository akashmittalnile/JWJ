<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FirebaseChat;
use App\Models\HelpSupport;
use App\Models\Notify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupportController extends Controller
{
    // Dev name : Dishant Gupta
    // This function is used to create a inquiry/query of user
    public function createQuery(Request $request) {
        try{
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required',
                'contact'=>'required',
                'message'=>'required',
                'type'=>'required'
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $support = new HelpSupport;
                $support->name = $request->name ?? null;
                $support->email = $request->email ?? null;
                $support->contact = $request->contact ?? null;
                $support->message = $request->message ?? null;
                $support->inquiry_type = $request->type ?? null;
                $support->status = 3;
                $support->user_id = auth()->user()->id;
                $support->save();

                return successMsg('Thank you submitting you query.');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to getting the list of inquiry types eg. community related, journal related etc
    public function inquiryList(Request $request) {
        try{
            $response = array();
            $list = config('constant.inquiry_type');
            if(count($list) > 0){
                foreach($list as $key => $val){
                    $temp['id'] = $key;
                    $temp['name'] = $val;
                    $response[] = $temp;
                }
            }
            return successMsg('Inquiry type list', $response);
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to getting the list of user query
    public function queryList(Request $request) {
        try{
            $response = array();
            $list = HelpSupport::where('user_id', auth()->user()->id)->orderByDesc('id')->get();

            foreach($list as $key => $val){
                $temp['id'] = $val->id;
                $temp['name'] = $val->name;
                $temp['email'] = $val->email;
                $temp['contact'] = $val->contact;
                $temp['message'] = $val->message;
                $temp['admin_reply'] = $val->past_response;
                $temp['type_name'] = config("constant.inquiry_type")[$val->inquiry_type];
                $temp['type'] = $val->inquiry_type;
                $temp['status'] = $val->status;
                $temp['status_name'] = ($val->status == 1) ? 'Closed' : (($val->status == 2) ? 'In-Progress' : 'Pending');
                $temp['query_date'] = date('d M, Y h:i A', strtotime($val->created_at));
                $temp['admin_reply_date'] = isset($val->past_response) ? date('d M, Y h:i A', strtotime($val->updated_at)) : null;
                $response[] = $temp;
            }
            
            return successMsg('Query list', $response);
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to getting the list of all the notifications
    public function notifications(Request $request) {
        try{
            $response = array();
            $list = Notify::where('receiver_id', auth()->user()->id)->where('is_seen', 1)->orderByDesc('id')->get();

            foreach($list as $key => $val){
                $temp['id'] = $val->id;
                $temp['sender_name'] = $val->sender->name;
                $temp['sender_image'] = isset($val->sender->profile) ? assets('uploads/profile/'.$val->sender->profile) : null;
                $temp['title'] = $val->	title;
                $temp['message'] = $val->message;
                $temp['type'] = $val->type;
                $temp['created_date'] = date('d M, Y h:i A', strtotime($val->created_at));
                $response[] = $temp;
            }
            
            return successMsg('Notifications list', $response);
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to getting the list of all the notifications
    public function notificationSeen(Request $request) {
        try{
            Notify::where('receiver_id', auth()->user()->id)->where('is_seen', 1)->update(['is_seen', 2]);
            return successMsg('Notifications seen');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to manage the last message & unseen count of chats
    public function chatRecord(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'msg' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $fire = FirebaseChat::where('user_id', auth()->user()->id)->first();
                if(isset($fire->id)){
                    $fire->last_msg = $request->msg;
                    $fire->updated_at = date('Y-m-d H:i:s');
                    $fire->save();
                } else {
                    $fire = new FirebaseChat;
                    $fire->user_id = auth()->user()->id;
                    $fire->last_msg = $request->msg;
                    $fire->save();
                }
                return successMsg('Record updated successfully');
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to upload a chat image
    public function chatImage(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'image' => 'required|mimes:jpeg,png,jpg|image',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $name = fileUpload($request->image, "/uploads/chat/");  
                return response()->json(['status' => true, 'url' => $name, 'message' => 'Image upload successfully.']);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
