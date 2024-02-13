<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\CommunityImage;
use App\Models\Plan;
use App\Models\UserFollowedCommunity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommunityController extends Controller
{
    // Dev name : Dishant Gupta
    // This function is used to show all the plans
    public function plans(Request $request) {
        try{
            $plan = Plan::orderBy('monthly_price')->get();
            $response = array();
            foreach($plan as $val){
                $temp['id'] = $val->id;
                $temp['name'] = $val->name;
                $temp['monthly_price'] = $val->monthly_price;
                $temp['anually_price'] = $val->anually_price;
                $temp['currency'] = $val->currency;
                $temp['current_plan'] = ($val->monthly_price == 0) ? true : false;
                $temp['point1'] = $val->entries_per_day . ' Entry Per Day / ' . $val->words . ' Words';
                $temp['point2'] = $val->routines . ' Routines With Ability To Share';
                $temp['point3'] = 'Add ' . $val->picture_per_day . ' Picture Per Day';
                $temp['point4'] = (($val->community == 3) ? 'Submit Your Own Communities/ App Approval Required' : ($val->community == 2 ? 'Participate In Communities' : 'View Community'));
                $response[] = $temp;
            }
            return successMsg('Plans list', $response);
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to show all the active communities
    public function communityList(Request $request) {
        try{
            $data = Community::join('users as u', 'u.id', '=', 'communities.created_by')->join('community_images as ci', 'ci.community_id', '=', 'communities.id')->join('plan as p', 'p.id', '=', 'communities.plan_id')->select('communities.*', 'u.role', 'ci.name as image_name', 'p.name as plan_name', 'p.monthly_price', 'p.anually_price', 'p.currency')->where('communities.status', 1);
            if($request->filled('search')){
                $data->where('communities.name', 'like', '%' . $request->search . '%');
            }
            $data = $data->orderByDesc('communities.id')->get();
            $response = [];
            foreach($data as $val){
                $temp['id'] = $val->id;
                $temp['name'] = $val->name;
                $temp['description'] = $val->description;
                $temp['status'] = $val->status;
                $temp['image'] = isset($val->image_name) ? assets("uploads/community/".$val->image_name) : null;
                $temp['plan_name'] = $val->plan_name;
                $temp['plan_monthly_price'] = $val->monthly_price;
                $temp['plan_anually_price'] = $val->anually_price;
                $temp['plan_price_currency'] = $val->currency;
                $response[] = $temp;
            }
            return successMsg('Community list', $response);
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to create a community by user
    public function createCommunity(Request $request) {
        try{
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'plan_id' => 'required',
                'file' => 'required',
                'description' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $community = new Community;
                $community->name = $request->title;
                $community->plan_id = $request->plan_id;
                $community->description = $request->description;
                $community->status = 0;
                $community->created_by = auth()->user()->id ?? null;
                $community->save();
                
                if ($request->hasFile("file")) {
                    $file = $request->file('file');
                    $name = "JWJ_" .  time() . rand() . "." . $file->getClientOriginalExtension();
                    $communityImage = new CommunityImage;
                    $communityImage->community_id = $community->id;
                    $communityImage->name = $name;
                    $communityImage->type = 'image';
                    $communityImage->save();
                    $file->move("public/uploads/community", $name);
                }

                return successMsg('New community created successfully.');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to follow or unfollow a community
    public function followUnfollow(Request $request) {
        try{
            $validator = Validator::make($request->all(), [
                'community_id' => 'required',
                'status' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                if($request->status == 1){
                    $follow = new UserFollowedCommunity;
                    $follow->userid = auth()->user()->id;
                    $follow->community_id = $request->community_id;
                    $follow->save();
                    return successMsg('Followed');
                } else {
                    $isFollow = UserFollowedCommunity::where('userid', auth()->user()->id)->where('community_id', $request->community_id)->first();
                    if(isset($isFollow->id)){
                        UserFollowedCommunity::where('userid', auth()->user()->id)->where('community_id', $request->community_id)->delete();
                        return successMsg('Unfollow');
                    }else{
                        return errorMsg("Community not found.");
                    }
                }
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
