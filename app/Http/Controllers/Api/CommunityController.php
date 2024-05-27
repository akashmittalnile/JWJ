<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Community;
use App\Models\CommunityImage;
use App\Models\Plan;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\PostReport;
use App\Models\ReportReason;
use App\Models\User;
use App\Models\UserFollowedCommunity;
use App\Models\UserLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use SebastianBergmann\CodeCoverage\Report\Xml\Report;
use DB;

class CommunityController extends Controller
{
    // Dev name : Dishant Gupta
    // This function is used to show all the active communities
    public function communityList(Request $request) {
        try{
            $data = Community::where('communities.status', 1);
            if($request->filled('search')){
                $data->where('communities.name', 'like', '%' . $request->search . '%');
            }
            $data = $data->orderByDesc('communities.id')->paginate(config('constant.apiPaginatePerPage'));
            $response = [];
            foreach($data as $val){
                $ufc = UserFollowedCommunity::where('community_id', $val->id)->where('userid', auth()->user()->id)->first();
                $followCount = UserFollowedCommunity::where('community_id', $val->id)->count();
                $follow = UserFollowedCommunity::where('community_id', $val->id)->orderByDesc('id')->limit(3)->get();
                $memberImage = array();
                foreach($follow as $items){
                    $followedUser = User::where('id', $items->userid)->first();
                    array_push($memberImage, isset($followedUser->profile) ? assets("uploads/profile/$followedUser->profile") : assets('assets/images/no-image.jpg'));
                }
                $imgs = $val->communityImages;
                $images = array();
                foreach($imgs as $item){
                    $tem['id'] = $item->id;
                    $tem['image'] = isset($item->name) ? assets("uploads/community/".$item->name) : null;
                    $images[] = $tem;
                }
                $plan = Plan::where('id', $val->plan_id)->first();
                $post = Post::where('community_id', $val->id)->count();
                $temp['id'] = $val->id;
                $temp['name'] = $val->name;
                $temp['description'] = $val->description;
                $temp['status'] = $val->status;
                $temp['image'] = $images;
                $temp['follow'] = isset($ufc->id) ? true : false;
                $temp['member_follow_count'] = $followCount ?? 0;
                $temp['post_count'] = $post ?? 0;
                $temp['posted_by'] = $val->user->name ?? 'NA';
                $temp['my_community'] = ($val->created_by==auth()->user()->id) ? true : false;
                $temp['posted_by_image'] =  isset($val->user->profile) ? assets('uploads/profile/'.$val->user->profile) : null;
                $temp['member_image'] = $memberImage;
                $temp['plan_name'] = $plan->plan_name ?? null;
                $temp['plan_monthly_price'] = $plan->monthly_price ?? null;
                $temp['plan_anually_price'] = $plan->anually_price ?? null;
                $temp['plan_price_currency'] = $plan->currency ?? null;
                $response[] = $temp;
            }
            $pagination = array(
                'currentPage' => $data->currentPage(),
                'lastPage' => $data->lastPage(),
                'total' => $data->total()
            );
            $totalFollow = UserFollowedCommunity::where('userid', auth()->user()->id)->distinct('community_id')->count();
            Log::channel('community')->info($data);
            return successMsg('Community list', ['data' => $response, 'totalFollow' => $totalFollow ?? 0, 'pagination' => $pagination]);
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to show all my communities whether its active, pending, inactive & rejected
    public function myCommunityList(Request $request) {
        try{
            $data = Community::where('created_by', auth()->user()->id);
            if($request->filled('search')){
                $data->where('communities.name', 'like', '%' . $request->search . '%');
            }
            if($request->filled('status')){
                $data->where('communities.status', $request->status);
            }else $data->where('communities.status', 1);
            $data = $data->orderByDesc('communities.id')->paginate(config('constant.apiPaginatePerPage'));
            $rejectCount = Community::where('created_by', auth()->user()->id)->where('communities.status', 3)->count();
            $inactiveCount = Community::where('created_by', auth()->user()->id)->where('communities.status', 2)->count();
            $activeCount = Community::where('created_by', auth()->user()->id)->where('communities.status', 1)->count();
            $pendingCount = Community::where('created_by', auth()->user()->id)->where('communities.status', 0)->count();
            $response = [];
            foreach($data as $val){
                $ufc = UserFollowedCommunity::where('community_id', $val->id)->where('userid', auth()->user()->id)->first();
                $followCount = UserFollowedCommunity::where('community_id', $val->id)->count();
                $follow = UserFollowedCommunity::where('community_id', $val->id)->orderByDesc('id')->limit(3)->get();
                $memberImage = array();
                foreach($follow as $items){
                    $followedUser = User::where('id', $items->userid)->first();
                    array_push($memberImage, isset($followedUser->profile) ? assets("uploads/profile/$followedUser->profile") : assets('assets/images/no-image.jpg'));
                }
                $imgs = $val->communityImages;
                $images = array();
                foreach($imgs as $item){
                    $tem['id'] = $item->id;
                    $tem['image'] = isset($item->name) ? assets("uploads/community/".$item->name) : null;
                    $images[] = $tem;
                }
                $status_name = ($val->status == 0) ? 'Pending' : (($val->status == 1) ? 'Active' : (($val->status == 2) ? 'Inactive' : 'Rejected'));
                $plan = Plan::where('id', $val->plan_id)->first();
                $post = Post::where('community_id', $val->id)->count();
                $temp['id'] = $val->id;
                $temp['name'] = $val->name;
                $temp['description'] = $val->description;
                $temp['status'] = $val->status;
                $temp['reject_reason'] = $val->reject_reason ?? null;
                $temp['status_name'] = $status_name;
                $temp['image'] = $images;
                $temp['follow'] = isset($ufc->id) ? true : false;
                $temp['member_follow_count'] = $followCount ?? 0;
                $temp['post_count'] = $post ?? 0;
                $temp['posted_by'] = $val->user->name ?? 'NA';
                $temp['posted_by_image'] =  isset($val->user->profile) ? assets('uploads/profile/'.$val->user->profile) : null;
                $temp['member_image'] = $memberImage;
                $temp['plan_name'] = $plan->plan_name ?? null;
                $temp['plan_monthly_price'] = $plan->monthly_price ?? null;
                $temp['plan_anually_price'] = $plan->anually_price ?? null;
                $temp['plan_price_currency'] = $plan->currency ?? null;
                $response[] = $temp;
            }
            $pagination = array(
                'currentPage' => $data->currentPage(),
                'lastPage' => $data->lastPage(),
                'total' => $data->total()
            );
            $totalFollow = UserFollowedCommunity::where('userid', auth()->user()->id)->distinct('community_id')->count();
            Log::channel('community')->info($data);
            return successMsg('Community list', ['data' => $response, 'rejectCount' => $rejectCount ?? 0, 'inactiveCount' => $inactiveCount ?? 0, 'activeCount' => $activeCount ?? 0, 'pendingCount' => $pendingCount ?? 0, 'totalFollow' => $totalFollow ?? 0, 'pagination' => $pagination]);
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to show all my followed communities 
    public function followedCommunityList(Request $request) {
        try{
            $data = UserFollowedCommunity::join('communities', 'communities.id', '=', 'user_followed_community.community_id')->where('user_followed_community.userid', auth()->user()->id);
            if($request->filled('search')){
                $data->where('communities.name', 'like', '%' . $request->search . '%');
            }
            $data = $data->select('communities.*')->orderByDesc('user_followed_community.id')->paginate(config('constant.apiPaginatePerPage'));
            $response = [];
            foreach($data as $val){
                $followCount = UserFollowedCommunity::where('community_id', $val->id)->count();
                $follow = UserFollowedCommunity::where('community_id', $val->id)->orderByDesc('id')->limit(3)->get();
                $memberImage = array();
                foreach($follow as $items){
                    $followedUser = User::where('id', $items->userid)->first();
                    array_push($memberImage, isset($followedUser->profile) ? assets("uploads/profile/$followedUser->profile") : assets('assets/images/no-image.jpg'));
                }
                $imgs = CommunityImage::where('community_id', $val->id)->get();
                $images = array();
                foreach($imgs as $item){
                    $tem['id'] = $item->id;
                    $tem['image'] = isset($item->name) ? assets("uploads/community/".$item->name) : null;
                    $images[] = $tem;
                }
                $status_name = ($val->status == 0) ? 'Pending' : (($val->status == 1) ? 'Active' : (($val->status == 2) ? 'Inactive' : 'Rejected'));
                $plan = Plan::where('id', $val->plan_id)->first();
                $post = Post::where('community_id', $val->id)->count();
                $temp['id'] = $val->id;
                $temp['name'] = $val->name;
                $temp['description'] = $val->description;
                $temp['status'] = $val->status;
                $temp['status_name'] = $status_name;
                $temp['image'] = $images;
                $temp['follow'] = true;
                $temp['member_follow_count'] = $followCount ?? 0;
                $temp['post_count'] = $post ?? 0;
                $temp['posted_by'] = $val->user->name ?? 'NA';
                $temp['posted_by_image'] =  isset($val->user->profile) ? assets('uploads/profile/'.$val->user->profile) : null;
                $temp['member_image'] = $memberImage;
                $temp['plan_name'] = $plan->plan_name ?? null;
                $temp['plan_monthly_price'] = $plan->monthly_price ?? null;
                $temp['plan_anually_price'] = $plan->anually_price ?? null;
                $temp['plan_price_currency'] = $plan->currency ?? null;
                $response[] = $temp;
            }
            $pagination = array(
                'currentPage' => $data->currentPage(),
                'lastPage' => $data->lastPage(),
                'total' => $data->total()
            );
            $totalFollow = UserFollowedCommunity::where('userid', auth()->user()->id)->distinct('community_id')->count();
            Log::channel('community')->info($data);
            return successMsg('Community list', ['data' => $response, 'totalFollow' => $totalFollow ?? 0, 'pagination' => $pagination]);
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to get the details of community and their posts if user follow
    public function communityDetails($id, Request $request) {
        try{
            $data = Community::join('users as u', 'u.id', '=', 'communities.created_by')->leftjoin('community_images as ci', 'ci.community_id', '=', 'communities.id')->select('communities.*', 'u.role', 'ci.name as image_name')->where('communities.id', $id)->first();
            if(isset($data->id)){
                $ufc = UserFollowedCommunity::where('community_id', $data->id)->where('userid', auth()->user()->id)->first();
                if(isset($ufc->id) || ($data->created_by == auth()->user()->id)){
                    $posts = Post::where('community_id', $data->id)->select(DB::raw("(select COUNT(object_id) from user_likes where user_likes.object_id = posts.id and user_likes.object_type = 'post') as likesCount, (select COUNT(object_id) from comments where comments.object_id = posts.id and comments.object_type = 'post') as commentCount, posts.*"));
                    if($request->filled('post_latest')) $posts->orderByDesc('id');
                    elseif($request->filled('post_like')) $posts->orderByDesc('likesCount');
                    elseif($request->filled('post_comment')) $posts->orderByDesc('commentCount');
                    $posts = $posts->get();
                    $postCount = Post::where('community_id', $data->id)->count();
                    $followCount = UserFollowedCommunity::where('community_id', $data->id)->count();
                    $follow = UserFollowedCommunity::where('community_id', $data->id)->orderByDesc('id')->limit(3)->get();
                    $memberImage = array();
                    foreach($follow as $items){
                        $followedUser = User::where('id', $items->userid)->first();
                        array_push($memberImage, isset($followedUser->profile) ? assets("uploads/profile/$followedUser->profile") : assets('assets/images/no-image.jpg'));
                    }
                    $imgs = CommunityImage::where('community_id', $data->id)->get();
                    $images = array();
                    foreach($imgs as $item){
                        $tem['id'] = $item->id;
                        $tem['image'] = isset($item->name) ? assets("uploads/community/".$item->name) : null;
                        $images[] = $tem;
                    }
                    $plan = Plan::where('id', $data->plan_id)->first();
                    $post = array();
                    foreach($posts as $item){
                        $img = PostImage::where('post_id', $item->id)->get();
                        $user = User::where('id', $item->created_by)->first();
                        $image = array();
                        foreach($img as $val){
                            $tem['id'] = $val->id;
                            $tem['image'] = assets("uploads/community/post/".$val->name);
                            $image[] = $tem;
                        }
                        $isLiked = UserLike::where('user_id', auth()->user()->id)->where('object_id', $item->id)->where('object_type', 'post')->first();
                        $temp['id'] = $item->id;
                        $temp['title'] = $item->title;
                        $temp['description'] = $item->post_description;
                        $temp['image'] = $image;
                        $temp['is_liked'] = (isset($isLiked->id) && $isLiked->status == 1) ? 1 : 0;
                        $temp['likes_count'] = $item->likesCount ?? 0;
                        $temp['comment_count'] = $item->commentCount ?? 0;
                        $temp['my_post'] = ($item->created_by == auth()->user()->id) ? true : false;
                        $temp['posted_by_name'] = $user->name ?? null;
                        $temp['posted_by_user_name'] = $user->user_name ?? null;
                        $temp['posted_by_profile_image'] = isset($user->profile) ? assets('uploads/profile/'.$user->profile) : null;
                        $temp['created_at'] = date('d M, Y h:i A', strtotime($item->created_at));
                        $post[] = $temp;
                    }

                    $followers = array();
                    foreach($data->communityFollower as $follow){
                        $tempFollow['user_name'] = $follow->user->user_name ?? null;
                        $tempFollow['name'] = $follow->user->name ?? null;
                        $tempFollow['profile'] = isset($follow->user->profile) ? assets('uploads/profile/'.$follow->user->profile) : null;
                        $followers[] = $tempFollow;
                    }

                    $response = [
                        'id' => $data->id,
                        'name' => $data->name,
                        'description' => $data->description,
                        'status' => $data->status,
                        'image' => $images,
                        'my_community' => ($data->created_by == auth()->user()->id) ? true : false,
                        'follow' => isset($ufc->id) ? true : false,
                        'member_follow_count' => $followCount ?? 0,
                        'followers' => $followers ?? 0,
                        'member_image' => $memberImage,
                        'plan_name' => $plan->plan_name ?? null,
                        'plan_monthly_price' => $plan->monthly_price ?? null,
                        'plan_anually_price' => $plan->anually_price ?? null,
                        'plan_price_currency' => $plan->currency ?? null,
                        'post_count' => $postCount ?? 0,
                        'posts' => $post,
                        'posted_by' => $data->user->name ?? 'NA',
                        'posted_by_image' => isset($data->user->profile) ? assets('uploads/profile/'.$data->user->profile) : null,
                    ];
                    Log::channel('community')->info($data);
                    Log::channel('post')->info($posts);
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
                'file' => 'required|array',
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

                if (count($request->file) > 0) {
                    foreach ($request->file as $key => $value) {
                        $name = fileUpload($request->file[$key], "/uploads/community/");
                        $communityImage = new CommunityImage;
                        $communityImage->community_id = $community->id;
                        $communityImage->name = $name;
                        $communityImage->type = 'image';
                        $communityImage->save();
                    }
                }

                $data['type'] = 'COMMUNITY';
                $data['title'] = 'New Community';
                $data['message'] = auth()->user()->name . ' has created a new community "' .$request->title . '"';
                $data['user_id'] = auth()->user()->id;
                notifyAdmin($data);

                $data['subject'] = 'Community Approval Request';
                $data['site_title'] = 'Community Approval Request';
                $data['view'] = 'pages.user.email.new-community';
                $data['to_email'] = auth()->user()->email;
                $data['customer_name'] = auth()->user()->name;
                sendEmail($data);

                Log::channel('community')->info($community);
                return successMsg('New community created successfully.');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to update a community by user
    public function editCommunity(Request $request) {
        try{
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'title' => 'required',
                'file' => 'array',
                'deletefile' => 'array',
                'description' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $community = Community::where('id', $request->id)->first();
                if(isset($community->id)){
                    if($community->created_by == auth()->user()->id){
                        $community->name = $request->title;
                        $community->plan_id = $request->plan_id ?? null;
                        $community->description = $request->description;
                        $community->updated_at = date('Y-m-d H:i:s');
                        $community->save();
        
                        if(isset($request->deletefile) && count($request->deletefile) > 0){
                            foreach($request->deletefile as $val){
                                $communityImage = CommunityImage::where('id', $val)->where('community_id', $community->id)->first();
                                fileRemove("/uploads/community/$communityImage->name");
                                CommunityImage::where('id', $val)->where('community_id', $community->id)->delete();
                            }
                        }
                        if (count($request->file) > 0) {
                            foreach ($request->file as $key => $value) {
                                $name = fileUpload($request->file[$key], "/uploads/community/");
                                $communityImage = new CommunityImage;
                                $communityImage->community_id = $community->id;
                                $communityImage->name = $name;
                                $communityImage->type = 'image';
                                $communityImage->save();
                            }
                        }
                        Log::channel('community')->info($community);
                        return successMsg('Community updated successfully.');
                    } else return errorMsg('This community is not created by you.');
                } else return errorMsg('Community not found');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to delete the journal
    public function deleteCommunity(Request $request) {
        try{
            $validator = Validator::make($request->all(), [
                'id' => 'required'
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $community = Community::where('id', $request->id)->first();
                if(isset($community->id)){
                    if($community->created_by == auth()->user()->id){
                        $commImage = CommunityImage::where('community_id', $community->id)->get();
                        foreach($commImage as $key => $val){
                            fileRemove("/uploads/community/$val->name");
                        }
                        $posts = Post::where('community_id', $community->id)->get();
                        foreach($posts as $key => $val){
                            $postImage = PostImage::where('post_id', $val->id)->get();
                            foreach($postImage as $key1 => $val1){
                                fileRemove("/uploads/community/post/$val1->name");
                            }
                            PostImage::where('post_id', $val->id)->delete();
                            $likes = UserLike::where('object_id', $val->id)->where('object_type', 'post')->delete();
                            $comment = Comment::where('object_id', $val->id)->where('object_type', 'post')->delete();
                        }
                        CommunityImage::where('community_id', $community->id)->delete();
                        Post::where('community_id', $community->id)->delete();
                        UserFollowedCommunity::where('community_id', $community->id)->delete();
                        Community::where('id', $request->id)->delete();
                        return successMsg('Community deleted successfully.');
                    } else return errorMsg('This community is not created by you.');
                } else return errorMsg('Community not found');
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
                $community = Community::where('id', $request->community_id)->first();
                if(isset($community->id)){
                    if($request->status == 1){
                        $follow = new UserFollowedCommunity;
                        $follow->userid = auth()->user()->id;
                        $follow->community_id = $request->community_id;
                        $follow->save();
                        return successMsg('You are now following '.$community->name);
                    } else {
                        $isFollow = UserFollowedCommunity::where('userid', auth()->user()->id)->where('community_id', $request->community_id)->first();
                        if(isset($isFollow->id)){
                            UserFollowedCommunity::where('userid', auth()->user()->id)->where('community_id', $request->community_id)->delete();
                            return successMsg('You unfollowed '.$community->name);
                        }else{
                            return errorMsg("Community not found.");
                        }
                    }
                } else return errorMsg("Community not found.");
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to create a post by user. If they are follower
    public function createPost(Request $request) {
        try{
            $validator = Validator::make($request->all(), [
                'community_id' => 'required',
                'title' => 'required',
                'description' => 'required',
                'file' => 'required|array',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $ufc = UserFollowedCommunity::where('community_id', $request->community_id)->where('userid', auth()->user()->id)->first();
                $data = Community::where('id', $request->community_id)->first();
                if(isset($ufc->id) || (isset($data->id) && ($data->created_by == auth()->user()->id))){
                    $post = new Post;
                    $post->community_id = $request->community_id;
                    $post->title = $request->title;
                    $post->post_description = $request->description;
                    $post->created_by = auth()->user()->id;
                    $post->save();

                    if (count($request->file) > 0) {
                        foreach ($request->file as $key => $value) {
                            $name = fileUpload($request->file[$key], "/uploads/community/post/");
                            $postImage = new PostImage;
                            $postImage->post_id = $post->id;
                            $postImage->name = $name;
                            $postImage->type = 'image';
                            $postImage->save();
                        }
                    }
                    Log::channel('post')->info($post);
                    return successMsg('New post created successfully.');
                } else return errorMsg('Please follow community first.');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to update a post
    public function editPost(Request $request) {
        try{
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'title' => 'required',
                'description' => 'required',
                'file' => 'array',
                'deletefile' => 'array',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $post = Post::where('id', $request->id)->first();
                if(isset($post->id)){
                    if($post->created_by == auth()->user()->id){
                        $post->title = $request->title;
                        $post->post_description = $request->description;
                        $post->updated_at = date('Y-m-d H:i:s');
                        $post->save();
    
                        if(isset($request->deletefile) && count($request->deletefile) > 0){
                            foreach($request->deletefile as $val){
                                $postImage = PostImage::where('id', $val)->where('post_id', $post->id)->first();
                                fileRemove("/uploads/community/post/$postImage->name");
                                PostImage::where('id', $val)->where('post_id', $post->id)->delete();
                            }
                        }
                        if (count($request->file) > 0) {
                            foreach ($request->file as $key => $value) {
                                $name = fileUpload($request->file[$key], "/uploads/community/post/");
                                $postImage = new PostImage;
                                $postImage->post_id = $post->id;
                                $postImage->name = $name;
                                $postImage->type = 'image';
                                $postImage->save();
                            }
                        }
                        Log::channel('post')->info($post);
                        return successMsg('Post updated successfully.');
                    } else return errorMsg('This post is not created by you');
                } else return errorMsg('Post not found');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to delete a post
    public function deletePost(Request $request) {
        try{
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $post = Post::where('id', $request->id)->first();
                if(isset($post->id)){
                    if($post->created_by == auth()->user()->id){
                        $postImg = PostImage::where('post_id', $request->id)->get();
                        foreach($postImg as $key => $val){
                            fileRemove("/uploads/community/post/$val->name");
                        }
                        PostImage::where('post_id', $request->id)->delete();
                        $likes = UserLike::where('object_id', $request->id)->where('object_type', 'post')->delete();
                        $comment = Comment::where('object_id', $request->id)->where('object_type', 'post')->delete();
                        Post::where('id', $request->id)->delete();
                        return successMsg('Post deleted successfully.');
                    } else return errorMsg('This post is not created by you');
                } else return errorMsg('Post not found');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to get the details of posts in community
    public function postDetails($id) {
        try{
            $post = Post::where('id', $id)->first();
            if(isset($post->id)){
                $img = PostImage::where('post_id', $id)->get();
                $community = Community::where('id', $post->community_id)->first();
                $user = User::where('id', $post->created_by)->first();
                $image = array();
                foreach($img as $val){
                    $tem['id'] = $val->id;
                    $tem['image'] = assets("uploads/community/post/".$val->name);
                    $image[] = $tem;
                }
                $isLiked = UserLike::where('user_id', auth()->user()->id)->where('object_id', $id)->where('object_type', 'post')->first();
                $likesCount = UserLike::where('object_id', $id)->where('object_type', 'post')->count();
                $commentCount = Comment::where('object_id', $id)->where('object_type', 'post')->count();
                $comment = Comment::where('object_id', $id)->where('object_type', 'post')->where('parent_id', null)->orderByDesc('id')->get();
                $commentArr = array();
                foreach($comment as $key => $value){
                    $reply = Comment::where('object_id', $id)->where('object_type', 'post')->where('parent_id', $value->id)->get();
                    $replyArr = array();
                    foreach($reply as $key1 => $value1){
                        $temp1['reply_id'] = $value1->id;
                        $temp1['reply_comment'] = $value1->comment;
                        $temp1['reply_posted_date'] = date('d M, Y h:i A', strtotime($value1->created_at));
                        $temp1['reply_posted_by'] = $value1->user->name ?? 'NA';
                        $temp1['reply_my_comment'] = $value1->user_id == auth()->user()->id ? true : false;
                        $temp1['reply_posted_by_user_name'] = $value1->user->user_name ?? 'NA';
                        $temp1['reply_posted_by_profile_image'] = isset($value1->user->profile) ? assets('uploads/profile/'.$value1->user->profile) : null;
                        $replyArr[] = $temp1;
                    }
                    $temp['comment_id'] = $value->id;
                    $temp['comment'] = $value->comment;
                    $temp['reply'] = $replyArr;
                    $temp['my_comment'] = $value->user_id == auth()->user()->id ? true : false;
                    $temp['posted_date'] = date('d M, Y h:i A', strtotime($value->created_at));
                    $temp['posted_by'] = $value->user->name ?? 'NA';
                    $temp['posted_by_user_name'] = $value->user->user_name ?? 'NA';
                    $temp['posted_by_profile_image'] = isset($value->user->profile) ? assets('uploads/profile/'.$value->user->profile) : null;
                    $commentArr[] = $temp;
                };
                $postReport = PostReport::where('user_id', auth()->user()->id)->where('post_id', $post->id)->first();
                if(isset($postReport->id)){
                    if(isset($postReport->reason_id))
                        $reason = ReportReason::where('id', $postReport->reason_id)->first();
                    $postReportArr = array(
                        'id' => $postReport->id,
                        'reason_id' => $postReport->reason_id ?? null,
                        'reason' => isset($reason->text) ? $reason->text : null,
                        'other_reason' => $postReport->other_reason ?? null,
                        'report_date' => date('d M, Y h:i A', strtotime($postReport->created_at)),
                    );
                } else $postReportArr = array();
                $response = array(
                    'id' => $post->id,
                    'title' => $post->title,
                    'description' => $post->post_description,
                    'image' => $image,
                    'is_liked' => (isset($isLiked->id) && $isLiked->status == 1) ? 1 : 0,
                    'likes_count' => $likesCount ?? 0,
                    'comment_count' => $commentCount ?? 0,
                    'comments' => $commentArr,
                    'my_post' => ($post->created_by==auth()->user()->id) ? true : false,
                    'community_id' => $post->community_id,
                    'community_title' => $community->name,
                    'community_description' => $community->description,
                    'post_report' => isset($postReport->id) ? $postReportArr : null,
                    'posted_by_name' => $user->name,
                    'posted_by_user_name' => $user->user_name,
                    'posted_by_profile' => isset($user->profile) ? assets('uploads/profile/'.$user->profile) : null,
                    'created_at' => date('d M, Y h:i A', strtotime($post->created_at)),
                );
                Log::channel('post')->info($post);
                return successMsg('Post details', $response);
            } else return errorMsg('Post not found');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to like or dislike a post
    public function postLikeUnlike(Request $request) {
        try{
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'type' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $post = Post::where('id', $request->id)->first();
                if(isset($post->id)){
                    $ufc = UserFollowedCommunity::where('community_id', $post->community_id)->where('userid', auth()->user()->id)->first();
                    $data = Community::where('id', $post->community_id)->first();
                    if(isset($ufc) || (isset($data->id) && ($data->created_by == auth()->user()->id))){
                        $like = UserLike::where('user_id', auth()->user()->id)->where('object_id', $request->id)->where('object_type', config('constant.object_type')[$request->type])->first();
                        if(isset($like->id)){
                            $like->status = ($like->status == 0) ? 1 : 0;
                            $like->updated_at = date('Y-m-d H:i:s');
                            $like->save();
                            $msg = ($like->status == 0) ? "You have liked the post" : "You have unliked the post";
                            return successMsg($msg);
                        } else {
                            $like = new UserLike;
                            $like->object_id = $request->id;
                            $like->object_type = config('constant.object_type')[$request->type];
                            $like->user_id = auth()->user()->id;
                            $like->status = 1;
                            $like->save();
                            return successMsg("You have liked the post");
                        }
                    } else return errorMsg('Please follow community first.');
                } else return errorMsg('Post not found');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to comment on a post
    public function postComment(Request $request) {
        try{
            if(isset($request->is_reply) && $request->is_reply == 1)
                $valid = ['id' => 'required', 'comment' => 'required', 'is_reply' => 'required', 'reply_id' => 'required', 'type' => 'required'];
            else
                $valid = ['id' => 'required', 'comment' => 'required', 'is_reply' => 'required', 'type' => 'required'];
            $validator = Validator::make($request->all(), $valid);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $post = Post::where('id', $request->id)->first();
                if(isset($post->id)){
                    $ufc = UserFollowedCommunity::where('community_id', $post->community_id)->where('userid', auth()->user()->id)->first();
                    $data = Community::where('id', $post->community_id)->first();
                    if(isset($ufc) || (isset($data->id) && ($data->created_by == auth()->user()->id))){
                        $comment = new Comment;
                        $comment->user_id = auth()->user()->id;
                        $comment->object_id = $request->id;
                        $comment->object_type = config('constant.object_type')[$request->type];
                        $comment->parent_id = $request->reply_id ?? null;
                        $comment->comment = $request->comment ?? null;
                        $comment->status = 1;
                        $comment->save();
                        if(isset($request->is_reply) && $request->is_reply == 1)
                            return successMsg('Replied successfully.');
                        else return successMsg('Comment posted successfully.');
                    } else return errorMsg('Please follow community first.');
                } else return errorMsg('Post not found');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to edit the comment on a post
    public function postEditComment(Request $request) {
        try{
            $validator = Validator::make($request->all(), [
                'comment_id' => 'required', 
                'comment' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $data = Comment::where('id', $request->comment_id)->first();
                if(isset($data->id)){
                    if($data->user_id == auth()->user()->id){
                        $data->comment = $request->comment ?? null;
                        $data->save();
                        return successMsg('Comment updated successfully.');
                    } else return errorMsg("This comment is not posted by you.");
                } else return errorMsg('Comment not found');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to delete the comment on a post
    public function postDeleteComment(Request $request) {
        try{
            $validator = Validator::make($request->all(), [
                'id' => 'required', 
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $data = Comment::where('id', $request->id)->first();
                if(isset($data->id)){
                    if($data->user_id == auth()->user()->id){
                        Comment::where('id', $request->id)->delete();
                        return successMsg('Comment deleted successfully.');
                    } else return errorMsg("This comment is not posted by you.");
                } else return errorMsg('Comment not found');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to getting the list of report reasons
    public function reportReason(Request $request) {
        try{
            $list = ReportReason::where('status', 1)->orderByDesc('id')->get();
            return successMsg('Report reasons', $list);
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to report a post
    public function postReport(Request $request) {
        try{
            if(isset($request->reason_id)) $valid = array('id' => 'required', 'reason_id' => 'required');
            else $valid = array('id' => 'required', 'other_reason' => 'required');
            $validator = Validator::make($request->all(), $valid);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $post = Post::where('id', $request->id)->first();
                if(isset($post->id)){
                    $ufc = UserFollowedCommunity::where('community_id', $post->community_id)->where('userid', auth()->user()->id)->first();
                    $data = Community::where('id', $post->community_id)->first();
                    if(isset($ufc) || (isset($data->id) && ($data->created_by == auth()->user()->id))){
                        $already = PostReport::where('user_id', auth()->user()->id)->where('post_id', $request->id)->first();
                        if(isset($already->id)) return errorMsg('Already reported to this post');
                        $report = new PostReport;
                        $report->user_id = auth()->user()->id;
                        $report->post_id = $post->id;
                        $report->reason_id = $request->reason_id ?? null;
                        $report->other_reason = $request->other_reason ?? null;
                        $report->status = 1;
                        $report->save();
                        return successMsg('Post reported successfully.');
                    } else return errorMsg('Please follow community first.');
                } else return errorMsg('Post not found');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
