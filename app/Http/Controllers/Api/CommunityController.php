<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\CommunityImage;
use App\Models\Plan;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\User;
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
                $ufc = UserFollowedCommunity::where('community_id', $val->id)->where('userid', auth()->user()->id)->first();
                $temp['id'] = $val->id;
                $temp['name'] = $val->name;
                $temp['description'] = $val->description;
                $temp['status'] = $val->status;
                $temp['image'] = isset($val->image_name) ? assets("uploads/community/".$val->image_name) : null;
                $temp['follow'] = isset($ufc->id) ? true : false;
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
    // This function is used to get the details of community and their posts if user follow
    public function communityDetails($id) {
        try{
            $data = Community::join('users as u', 'u.id', '=', 'communities.created_by')->join('community_images as ci', 'ci.community_id', '=', 'communities.id')->join('plan as p', 'p.id', '=', 'communities.plan_id')->select('communities.*', 'u.role', 'ci.name as image_name', 'p.name as plan_name', 'p.monthly_price', 'p.anually_price', 'p.currency')->where('communities.id', $id)->first();
            if(isset($data->id)){
                $ufc = UserFollowedCommunity::where('community_id', $data->id)->where('userid', auth()->user()->id)->first();
                if(isset($ufc->id)){
                    $post = Post::where('community_id', $data->id)->get();
                    
                    $response = [
                        'id' => $data->id,
                        'name' => $data->name,
                        'description' => $data->description,
                        'status' => $data->status,
                        'image' => isset($data->image_name) ? assets("uploads/community/".$data->image_name) : null,
                        'follow' => isset($ufc->id) ? true : false,
                        'plan_name' => $data->plan_name,
                        'plan_monthly_price' => $data->monthly_price,
                        'plan_anually_price' => $data->anually_price,
                        'plan_price_currency' => $data->currency,
                        'posts' => $post
                    ];
                    return successMsg('Community found', $response);
                } else return errorMsg('Please follow community first.');
            } else return errorMsg('Community not found.');
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
                'file' => 'required',
                'description' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $community = new Community;
                $community->name = $request->title;
                $community->plan_id = $request->plan_id ?? null;
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

    // Dev name : Dishant Gupta
    // This function is used to create a post by user
    public function createPost(Request $request) {
        try{
            $validator = Validator::make($request->all(), [
                'community_id' => 'required',
                'title' => 'required',
                'description' => 'required',
                'file' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $post = new Post;
                $post->community_id = $request->community_id;
                $post->title = $request->title;
                $post->post_description = $request->description;
                $post->created_by = auth()->user()->id;
                $post->save();

                if ($request->hasFile("file")) {
                    foreach ($request->file('file') as $value) {
                        $name = "JWJ_" .  time() . rand() . "." . $value->getClientOriginalExtension();
                        $value->move("public/uploads/community/post", $name);
                        $postImage = new PostImage;
                        $postImage->post_id = $post->id;
                        $postImage->name = $name;
                        $postImage->type = 'image';
                        $postImage->save();
                    }
                }

                return successMsg('New post created successfully.');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
