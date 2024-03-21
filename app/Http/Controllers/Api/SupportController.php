<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HelpSupport;
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
}
