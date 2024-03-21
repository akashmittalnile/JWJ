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
                $support->type = $request->type ?? null;
                $support->user_id = auth()->user()->id;
                $support->save();

                return successMsg('Thank you submitting you query.');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to create a inquiry/query of user
    public function inquiryList(Request $request) {
        try{
            $response = array();
            $list = config('constant.inquiry_type');
            if(count($list) > 0){
                foreach($list as $key => $val){

                }
            }
            return successMsg('New community created successfully.', $response);
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
