<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Community;
use App\Models\CommunityImage;
use App\Models\Notify;
use App\Models\Plan;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\User;
use App\Models\UserFollowedCommunity;
use App\Models\UserLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class CommunityController extends Controller
{
    // Dev name : Dishant Gupta
    // This function is used to show the listing of all communities
    public function communityManagement(Request $request)
    {
        try {
            $plan = Plan::where('status', 1)->get();
            if($request->ajax()){
                $data = Community::join('users as u', 'u.id', '=', 'communities.created_by')->select('communities.*', 'u.role')->orderByDesc('communities.id');
                if($request->filled('search')){
                    $data->whereRaw("(`communities`.`name` LIKE '%" . $request->search . "%' or `u`.`name` LIKE '%" . $request->search . "%' or `u`.`email` LIKE '%" . $request->search . "%' or `u`.`mobile` LIKE '%" . $request->search . "%')");
                }
                if($request->filled('role')){
                    $data->where('u.role', $request->role);
                }
                if($request->filled('ustatus')){
                    $data->where('communities.status', $request->ustatus);
                } else $data->whereIn('communities.status', [1,2]);

                $data = $data->paginate(config('constant.communityPerPage'));
                $html = '';
                foreach($data as $val)
                {
                    $image = CommunityImage::where('community_id', $val->id)->orderByDesc('id')->first();
                    $imgs = CommunityImage::where('community_id', $val->id)->get();
                    $image_html = "";
                    foreach($imgs as $name){
                        $image_html .= "<div class='item'>
                        <div class='community-media'>
                                <a data-fancybox='' href='".assets("uploads/community/$name->name")."'>
                                    <img src='".assets("uploads/community/$name->name")."'>
                                </a>
                            </div>
                        </div>";
                    }
                    if($image_html == "") {
                        $image_html = "<div class='item'>
                        <div class='community-media'>
                                <img src='".assets('assets/images/no-image.jpg')."'>
                            </div>
                        </div>";
                    }
                    
                    if(isset($val->plan_id)){
                        $plan = Plan::where('id', $val->plan_id)->first();
                        if(isset($plan->id)){
                            $plan_type = (($plan->name=='Plan A') ? 'freeplan.svg' : ($plan->name=='Plan B' ? 'goldplan.svg' : 'platinumplan.svg'));
                            $plan_html = "<div class='community-plan-info'>
                                <img src='".assets("assets/images/$plan_type")."'>$plan->name
                            </div>";
                        } else $plan_html = '';
                    } else  $plan_html = '';

                    $followcount = UserFollowedCommunity::where('community_id', $val->id)->count();
                    $follow = UserFollowedCommunity::where('community_id', $val->id)->orderByDesc('id')->limit(3)->get();
                    if(count($follow) > 0){
                        $mem_html = "";
                        $count = 1;
                        foreach($follow as $items){
                            $followedUser = User::where('id', $items->userid)->first();
                            $asset = (isset($followedUser->profile) && File::exists(public_path("uploads/profile/$followedUser->profile"))) ? assets("uploads/profile/$followedUser->profile") : assets('assets/images/no-image.jpg');
                            $mem_html .= "<span class='jwjcard-member-image image$count'>
                                <img src='".$asset."'>
                            </span>";
                            $count++;
                        }
                    } else {
                        $mem_html = "";
                    }

                    $post = Post::where('community_id', $val->id)->count();

                    $role = ($val->role==2) ? 'Admin' : 'User';
                    $checked = ($val->status==1) ? 'checked' : '';
                    
                    $html .= "<div class='col-md-6'>
                    <div class='jwj-community-card'>
                        <div class='jwjcard-head'>
                            <div class='jwjcard-group-card'>
                                <div class='jwjcard-group-avtar'>
                                    <img src='".assets("uploads/community/$image->name")."'>
                                </div>
                                <div class='jwjcard-group-text'>
                                    <h4 class='text-capitalize'>$val->name</h4>
                                </div>
                            </div>
                            <div class='jwjcard-group-action'>
                                <a class='managecommunity-btn' href='".route('admin.community-management.details', encrypt_decrypt('encrypt', $val->id))."'>Manage Community</a>
                            </div>
                        </div>
                        <div class='jwjcard-body'>
                            <div class='admincommunity-text'>$role Community</div>
                            <div id='communitycarousel' class='communitycarousels owl-carousel owl-theme'>
                                $image_html
                            </div>

                            <div class='row'>
                                <div class='col-md-6'>
                                    <div class='User-contact-info'>
                                        <div class='User-contact-info-content'>
                                            <h2>Status</h2>
                                            <div class='switch-toggle'>
                                                <p style='color: #8C9AA1;'>Inactive</p>
                                                <div class=''>
                                                    <label class='toggle' for='myToggle".$val->id."'>
                                                        <input data-id='".encrypt_decrypt('encrypt', $val->id)."' class='toggle__input' name='status".$val->id."' $checked type='checkbox' id='myToggle".$val->id."'>
                                                        <div class='toggle__fill'></div>
                                                    </label>
                                                </div>
                                                <p style='color: #8C9AA1;'>Active</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class='col-md-6'>
                                    <div class='service-shift-card'>
                                        <div class='service-shift-card-image'>
                                            <img src='".assets('assets/images/up-stock.svg')."' height='14px'>
                                        </div>
                                        <div class='service-shift-card-text'>
                                            <h2>Total Posts</h2>
                                            <p>$post</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class='jwjcard-foot'>
                            <div class='jwjcard-member-item'>
                                <div class='jwjcard-member-info'>
                                    $mem_html
                                </div>
                                <p>$followcount Followers</p>
                            </div>
                            <div class='service-shift-card'>
                                <div class='service-shift-card-image'>
                                    <img src='".assets('assets/images/calendar.svg')."' height='14px'>
                                </div>
                                <div class='service-shift-card-text'>
                                    <h2>Created On </h2>
                                    <p>".date('d M, Y h:iA', strtotime($val->created_at))."</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>";
                }
                
                if($data->total() < 1) return errorMsg("No communities found");
                $response = array(
                    'currentPage' => $data->currentPage(),
                    'lastPage' => $data->lastPage(),
                    'total' => $data->total(),
                    'html' => $html,
                );
                return successMsg('Community list', $response);
            }
            return view('pages.admin.community.community-management')->with(compact('plan'));
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to create a new community by admin
    public function communityStoreData(Request $request)
    {
        try {
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
                $community->description = $request->description;
                $community->status = 1;
                $community->created_by = auth()->user()->id ?? null;
                $community->save();
                
                if ($request->hasFile("file")) {
                    foreach ($request->file('file') as $value) {
                        $name = fileUpload($value, "/uploads/community/");
                        $communityImage = new CommunityImage;
                        $communityImage->community_id = $community->id;
                        $communityImage->name = $name;
                        $communityImage->type = 'image';
                        $communityImage->save();
                    }
                }

                $data['type'] = 'COMMUNITY';
                $data['image_id'] = $community->id;
                $data['title'] = 'New Community';
                $data['message'] = 'Journey with journals administrator has created a new community "' .$request->title . '"';
                $data['user_id'] = auth()->user()->id;
                notifyUsers($data);

                return successMsg('New community created successfully.');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to change the status of community like active, inactive & reject
    public function changeCommunityStatus(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'status' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $id = encrypt_decrypt('decrypt', $request->id);
                $user = Community::where('id', $id)->update([
                    'status'=> $request->status,
                    'reject_reason'=> $request->reason ?? null
                ]);
                $community = Community::where('id', $id)->first();
                $notify = new Notify;
                $notify->sender_id = auth()->user()->id;
                $notify->receiver_id = $community->user->id ?? null;
                $notify->type = 'COMMUNITY';
                $notify->image_id = $community->id;
                $notify->title = ($request->status == 1) ? 'Community Approved' : 'Community Rejected';
                $notify->message = ($request->status == 1) ? 'Congratulations, Your "' . $community->name .'" community is approved' : 'Sorry, Your "' . $community->name .'"  community is rejected. Please contact administrator';
                $notify->save();
                $pushData = array(
                    'msg' => ($request->status == 1) ? 'Congratulations, Your "' . $community->name .'" community is approved' : 'Sorry, Your "' . $community->name .'"  community is rejected. Please contact administrator',
                    'title' => ($request->status == 1) ? 'Community Approved' : 'Community Rejected'
                );
                sendNotification($community->user->fcm_token, $pushData);
                $msg = ($request->status == 1) ? 'Community approved' : 'Community rejected';
                return successMsg("$msg successfully");
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to getting the data of particular community
    public function communityManagementDetails($id, Request $request)
    {
        try {
            $id = encrypt_decrypt('decrypt', $id);
            $postCount = Post::where('community_id', $id)->count();
            if($request->ajax()){
                $post = Post::where('community_id', $id);
                if($request->filled('search')){
                    $post->whereRaw("(`title` LIKE '%" . $request->search . "%')");
                }
                $post = $post->orderByDesc('id')->paginate(config('constant.postPerPage'));
                $html = '';
                foreach($post as $val)
                {
                    $proImg = (isset($val->user->profile) && File::exists(public_path('uploads/profile/'.$val->user->profile)) ) ? assets('uploads/profile/'.$val->user->profile) : assets('assets/images/no-image.jpg'); 

                    $isLiked = UserLike::where('user_id', auth()->user()->id)->where('status', 1)->where('object_id', $val->id)->where('object_type', 'post')->count();
                    $likeImg = $isLiked ? assets('assets/images/like1.svg') : assets('assets/images/like.svg');

                    $imgs = PostImage::where('post_id', $val->id)->get();
                    $image_html = "";
                    foreach($imgs as $name){
                        $image_html .= "<div class='item'>
                        <div class='community-media' style='height: 210px'>
                                <a data-fancybox='' href='".assets("uploads/community/post/$name->name")."'>
                                    <img src='".assets("uploads/community/post/$name->name")."'>
                                </a>
                            </div>
                        </div>";
                    }
                    if($image_html == "") {
                        $image_html = "<div class='item'>
                        <div class='community-media' style='height: 210px'>
                                <img src='".assets('assets/images/no-image.jpg')."'>
                            </div>
                        </div>";
                    }

                    $planName = isset($val->user->plan->name) ? $val->user->plan->name : 'Plan A';
                    $planImg = isset($val->user->plan->image) ? assets('assets/images/'.$val->user->plan->image) : assets('assets/images/freeplan.svg');
                    $planHtml = ($val->user->role == 2) ? "Administrator" : "<img src='$planImg'> $planName Member";

                    $commentCount = 0;
                    foreach($val->comments() as $key => $value){
                        $reply = Comment::join('users as u', 'u.id', '=', 'comments.user_id')->where('u.status', 1)->where('object_id', $val->id)->where('object_type', 'post')->where('parent_id', $value->id)->count();
                        $commentCount++;
                        $commentCount+=$reply;
                    };

                    $html .= "
                        <div class='jwj-posts-posts-card'>
                            <div class='jwj-posts-head'>
                                <div class='post-member-item'>
                                    <div class='post-member-image'>
                                        <img src='$proImg'>
                                    </div>
                                    <div class='post-member-text'>
                                        <h3>".$val->user->name."</h3>
                                        <div class='post-member-plan'>$planHtml</div>
                                    </div>
                                </div>
                                <div class='jwjcard-group-action d-flex'>
                                    <div class='mx-2'>
                                        <a class='Reply-btn' href='". route('admin.community-management.post.details', encrypt_decrypt('encrypt', $val->id)) ."'> <i class='las la-eye'></i> View</a>
                                    </div>
                                    <div>
                                        <a class='delete-btn1' data-postid='$val->id' id='delete-button' href='javacsript:void(0)'> <img src='". assets('assets/images/trash.svg') ."'> Delete Post</a>
                                    </div>
                                </div>
                            </div>
                            <div class='jwj-posts-body'>
                                <div class='row g-1'>
                                    <div class='col-md-5'>
                                        <div id='communitycarouselpost1' class='communitycarouselpost1 owl-carousel owl-theme'>
                                            $image_html
                                        </div>
                                    </div>
                                    <div class='col-md-7'>
                                        <div class='jwjcard-body'>
                                            <div class='community-post-description'>
                                                <h3>$val->title</h3>
                                                <div class='post-date-info'>
                                                    <img src='". assets('assets/images/calendar.svg') ."'> Submitted On: ".date('d M, Y h:iA', strtotime($val->created_at))."
                                                </div>
                                                <p>$val->post_description</p> 
                                                <div class='community-post-action'>
                                                    <a class='Like-btn'><img src='".$likeImg."'> ". $val->likeCount()." likes</a>
                                                    <a class='Comment-btn'><img src='".assets('assets/images/comment.svg')."'> ". $commentCount ." Comments</a>
                                                </div>
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                        </div>";
                }
                if($post->total() < 1) return errorMsg("No post found");
                $response = array(
                    'currentPage' => $post->currentPage(),
                    'lastPage' => $post->lastPage(),
                    'total' => $post->total(),
                    'html' => $html,
                );
                return successMsg('Post created successfully', $response);
            } else {
                $data = Community::join('users as u', 'u.id', '=', 'communities.created_by')->select('communities.*', 'u.role', 'u.name as user_name', 'u.profile as user_image')->where('communities.id', $id)->first();
                $imgs = CommunityImage::where('community_id', $id)->get();
                $follow = UserFollowedCommunity::where('community_id', $id)->orderByDesc('id')->get();
            }
            $id = encrypt_decrypt('encrypt', $id);
            return view('pages.admin.community.details')->with(compact('data', 'follow', 'imgs', 'id', 'postCount'));
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to show the listing of all pending communities. which is created by user
    public function communityApproval(Request $request)
    {
        try {
            if($request->ajax()){
                $data = Community::join('users as u', 'u.id', '=', 'communities.created_by')->select('communities.*', 'u.role', 'u.name as user_name', 'u.profile as user_image')->where('communities.status', 0);
                if($request->filled('search')){
                    $data->whereRaw("(`communities`.`name` LIKE '%" . $request->search . "%' or `u`.`name` LIKE '%" . $request->search . "%' or `u`.`email` LIKE '%" . $request->search . "%' or `u`.`mobile` LIKE '%" . $request->search . "%')");
                }
                if($request->filled('role')){
                    $data->where('u.role', $request->role);
                }

                $data = $data->orderByDesc('communities.id')->paginate(config('constant.communityPerPage'));
                $html = '';
                foreach($data as $val)
                {
                    $userProfileImage = (isset($val->user_image) && File::exists(public_path('uploads/profile/'.$val->user->profile)) ) ? assets("uploads/profile/$val->user_image") : assets("assets/images/no-image.jpg");
                    $imgs = CommunityImage::where('community_id', $val->id)->get();
                    $image_html = "";
                    foreach($imgs as $name){
                        $image_html .= "<div class='item'>
                        <div class='community-approval-media'>
                                <a data-fancybox='' href='".assets("uploads/community/$name->name")."'>
                                    <img src='".assets("uploads/community/$name->name")."'>
                                </a>
                            </div>
                        </div>";
                    }
                    if($image_html == "") {
                        $image_html = "<div class='item'>
                        <div class='community-approval-media'>
                                <img src='".assets('assets/images/no-image.jpg')."'>
                            </div>
                        </div>";
                    }

                    $html .= "<div class='col-md-4'>
                    <div class='jwj-community-approval-card'>
                        <div class='jwjcard-head'>
                            <div class='jwjcard-member-item'>
                                <div class='jwjcard-member-info'>
                                    <span class='jwjcard-member-image image1'>
                                        <img src='".$userProfileImage."'>
                                    </span>
                                </div>
                                <p>$val->user_name</p>
                            </div>
                            <div class='jwjcard-group-action'>
                                <a class='managecommunity-btn' href='".route('admin.community-management.approval-details', encrypt_decrypt('encrypt', $val->id))."'>View Community</a>
                            </div>
                        </div>
                        <div id='communitycarousel1' class='communitycarousel1 owl-carousel owl-theme'>
                            $image_html
                        </div>
                        <div class='jwjcard-body'>
                            <div class='admincommunity-text'>User Community</div>
                            <div class='community-description'>
                                <h3>$val->name</h3>
                                <p>$val->description</p>
                            </div>
                        </div>
                    </div>
                </div>";
                }
                if($data->total() < 1) return errorMsg("No communities found");
                $response = array(
                    'currentPage' => $data->currentPage(),
                    'lastPage' => $data->lastPage(),
                    'total' => $data->total(),
                    'html' => $html,
                );
                return successMsg('Community list', $response);
            }
            return view('pages.admin.community.approval');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to getting the data of particular community
    public function communityDetails($id)
    {
        try {
            $id = encrypt_decrypt('decrypt', $id);
            $data = Community::join('users as u', 'u.id', '=', 'communities.created_by')->join('community_images as ci', 'ci.community_id', '=', 'communities.id')->select('communities.*', 'u.role', 'ci.name as image_name', 'u.name as user_name', 'u.profile as user_image')->where('communities.id', $id)->with('user')->first();
            $imgs = CommunityImage::where('community_id', $id)->get();
            return view('pages.admin.community.approval-details')->with(compact('data', 'imgs'));
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
                $id = encrypt_decrypt('decrypt', $request->id);
                $community = Community::where('id', $id)->first();
                if(isset($community->id)){
                    $commImage = CommunityImage::where('community_id', $community->id)->get();
                    foreach($commImage as $key => $val){
                        // fileRemove("/uploads/community/$val->name");
                    }
                    $posts = Post::where('community_id', $community->id)->get();
                    foreach($posts as $key => $val){
                        $postImage = PostImage::where('post_id', $val->id)->get();
                        foreach($postImage as $key1 => $val1){
                            // fileRemove("/uploads/community/post/$val1->name");
                        }
                        PostImage::where('post_id', $val->id)->delete();
                        $likes = UserLike::where('object_id', $val->id)->where('object_type', 'post')->delete();
                        $comment = Comment::where('object_id', $val->id)->where('object_type', 'post')->delete();
                    }
                    CommunityImage::where('community_id', $community->id)->delete();
                    Post::where('community_id', $community->id)->delete();
                    UserFollowedCommunity::where('community_id', $community->id)->delete();
                    Community::where('id', $id)->delete();
                    return redirect()->route('admin.community-management.list')->with('success', 'Community deleted successfully.');
                } else return redirect()->back()->with('error', 'Community not found');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to show the listing of all pending communities. which is created by user
    public function communityRejected(Request $request)
    {
        try {
            if($request->ajax()){
                $data = Community::join('users as u', 'u.id', '=', 'communities.created_by')->select('communities.*', 'u.role', 'u.name as user_name', 'u.profile as user_image')->where('communities.status', 3)->orderByDesc('communities.id');
                if($request->filled('search')){
                    $data->whereRaw("(`communities`.`name` LIKE '%" . $request->search . "%' or `u`.`name` LIKE '%" . $request->search . "%' or `u`.`email` LIKE '%" . $request->search . "%' or `u`.`mobile` LIKE '%" . $request->search . "%')");
                }
                if($request->filled('role')){
                    $data->where('u.role', $request->role);
                }

                $data = $data->paginate(config('constant.communityPerPage'));
                $html = '';
                foreach($data as $val)
                {
                    $imgs = CommunityImage::where('community_id', $val->id)->get();
                    $image_html = "";
                    foreach($imgs as $name){
                        $image_html .= "<div class='item'>
                        <div class='community-approval-media'>
                                <a data-fancybox='' href='".assets("uploads/community/$name->name")."'>
                                    <img src='".assets("uploads/community/$name->name")."'>
                                </a>
                            </div>
                        </div>";
                    }
                    if($image_html == "") {
                        $image_html = "<div class='item'>
                        <div class='community-approval-media'>
                                <img src='".assets('assets/images/no-image.jpg')."'>
                            </div>
                        </div>";
                    }
                    $userProfileImage = (isset($val->user_image) && File::exists(public_path('uploads/profile/'.$val->user->profile)) ) ? assets("uploads/profile/$val->user_image") : assets("assets/images/no-image.jpg");
                    $role = ($val->role==2) ? 'Admin' : 'User';
                    $checked = ($val->status==1) ? 'checked' : '';
                    $plan_type = (($val->plan_name=='Plan A') ? 'freeplan.svg' : ($val->plan_name=='Plan B' ? 'goldplan.svg' : 'platinumplan.svg'));
                    $html .= "<div class='col-md-4'>
                    <div class='jwj-community-approval-card Rejected-community-card'>
                        <div class='jwjcard-head'>
                            <div class='jwjcard-member-item'>
                                <div class='jwjcard-member-info'>
                                    <span class='jwjcard-member-image image1'>
                                        <img src='".$userProfileImage."'>
                                    </span>
                                </div>
                                <p>$val->user_name</p>
                            </div>
                            <div class='jwjcard-group-action'>
                                <a class='managecommunity-btn' href='".route('admin.community-management.approval-details', encrypt_decrypt('encrypt', $val->id))."'>View Community</a>
                            </div>
                        </div>
                        <div id='communitycarousel1' class='communitycarousel1 owl-carousel owl-theme'>
                            $image_html
                        </div>
                        <div class='jwjcard-body'>
                            <div class='admincommunity-text'>User Community</div>
                            <div class='community-description'>
                                <h3>$val->name</h3>
                                <p>$val->description</p>
                            </div>
                            <div class='Rejected-status'>Rejected</div>
                        </div>
                    </div>
                </div>";
                }
                if($data->total() < 1) return errorMsg("No communities found");
                $response = array(
                    'currentPage' => $data->currentPage(),
                    'lastPage' => $data->lastPage(),
                    'total' => $data->total(),
                    'html' => $html,
                );
                return successMsg('Community list', $response);
            }
            return view('pages.admin.community.rejected');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used tp create post by admin
    public function createPost(Request $request)
    {
        try {

            // Validate incoming request data
            $validator = Validator::make($request->all(), [
                'title' => 'required|string',
                'post_description' => 'required|string',
                'images.*' => 'image', // Validate each image uploaded
                'community_id' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()], 400);
            } else {
                $post = new Post();
                $post->plan_id = null;
                $post->community_id = encrypt_decrypt('decrypt', $request->community_id);
                $post->title = $request->input('title');
                $post->post_description = $request->input('post_description');
                $post->created_by = auth()->user()->id ?? null;
                $post->save();
                
                if ($request->hasFile("images")) {
                    foreach ($request->file('images') as $value) {
                        $name = fileUpload($value, "/uploads/community/post/");
                        $postImage = new PostImage;
                        $postImage->post_id = $post->id;
                        $postImage->name = $name;
                        $postImage->type = 'image';
                        $postImage->save();
                    }
                }

                $data = Community::where('id', encrypt_decrypt('decrypt', $request->community_id))->first();
                if(auth()->user()->id != $data->created_by){
                    $notify = new Notify;
                    $notify->sender_id = auth()->user()->id;
                    $notify->receiver_id = $data->created_by;
                    $notify->type = 'POST';
                    $notify->image_id = $post->id;
                    $notify->title = 'New Post';
                    $notify->message = '"'. auth()->user()->name .'" posted on your "'. $data->name .'" community';
                    $notify->save();
                    $pushData = array(
                        'msg' => '"'. auth()->user()->name .'" posted on your "'. $data->name .'" community',
                        'title' => 'New Post'
                    );
                    sendNotification($data->user->fcm_token, $pushData);
                }
                return redirect()->back()->with('success', 'Post created successfully');
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Exception => ' . $e->getMessage()], 500);
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to delete a post
    public function deletePost(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'postId' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $post = Post::where('id', $request->postId)->first();
                if(isset($post->id)){
                    $postImg = PostImage::where('post_id', $request->postId)->get();
                    foreach($postImg as $key => $val){
                        // fileRemove("/uploads/community/post/$val->name");
                    }
                    PostImage::where('post_id', $request->postId)->delete();
                    $likes = UserLike::where('object_id', $request->postId)->where('object_type', 'post')->delete();
                    $comment = Comment::where('object_id', $request->postId)->where('object_type', 'post')->delete();
                    Post::where('id', $request->postId)->delete();
                    if($request->filled('postDetail')) return redirect()->route('admin.community-management.details', encrypt_decrypt('encrypt', $post->community_id))->with('success', 'Post deleted successfully');
                    return redirect()->back()->with('success', 'Post deleted successfully');
                } else return redirect()->back()->with('error', 'Post not found');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to delete a comment
    public function deleteComment(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $id = encrypt_decrypt('decrypt', $request->id);
                Comment::where('id', $id)->delete();
                return redirect()->back()->with('success', 'Comment deleted successfully');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to create a new comment
    public function createComment(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'comment' => 'required',
                'post_id' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $id = encrypt_decrypt('decrypt', $request->post_id);
                $post = Post::where('id', $id)->first();
                $comment = new Comment;
                $comment->user_id = auth()->user()->id;
                $comment->object_id = $id;
                $comment->parent_id = isset($request->comment_id) ? encrypt_decrypt('decrypt', $request->comment_id) : null;
                $comment->object_type = 'post';
                $comment->comment = $request->comment ?? null;
                $comment->status = 1;
                $comment->save();

                if(auth()->user()->id != $post->created_by){
                    $notify = new Notify;
                    $notify->sender_id = auth()->user()->id;
                    $notify->receiver_id = $post->created_by;
                    $notify->type = 'COMMENT';
                    $notify->title = 'New Comment';
                    $notify->message = '"'. auth()->user()->name .'" comment on your "'. $post->title .'" post';
                    $notify->save();

                    $pushData = array(
                        'msg' => '"'. auth()->user()->name .'" comment on your "'. $post->title .'" post',
                        'title' => 'New Comment'
                    );
                    sendNotification($post->user->fcm_token, $pushData);
                }

                if(isset($request->comment_id)) return successMsg('Replied successfully.');
                return successMsg('Comment posted successfully.');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to edit a comment
    public function editComment(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'comment' => 'required',
                'comment_id' => 'required',
                'post_id' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $id = encrypt_decrypt('decrypt', $request->post_id);
                $commentId = encrypt_decrypt('decrypt', $request->comment_id);
                $comment = Comment::where('id', $commentId)->where('object_id', $id)->where('object_type', 'post')->first();
                $comment->comment = $request->comment ?? null;
                $comment->save();
                return successMsg('Comment updated successfully.');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to edit a comment
    public function postLikeUnlike(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $id = encrypt_decrypt('decrypt', $request->id);
                $post = Post::where('id', $id)->first();
                if(isset($post->id)){
                    $like = UserLike::where('user_id', auth()->user()->id)->where('object_id', $id)->where('object_type', 'post')->first();
                        if(isset($like->id)){
                            $like->status = ($like->status == 0) ? 1 : 0;
                            $like->updated_at = date('Y-m-d H:i:s');
                            $like->save();
                            $msg = ($like->status == 1) ? "You have liked the post" : "You have unliked the post";
                            return successMsg($msg);
                        } else {
                            $like = new UserLike;
                            $like->object_id = $id;
                            $like->object_type = 'post';
                            $like->user_id = auth()->user()->id;
                            $like->status = 1;
                            $like->save();
                            return successMsg("You have liked the post");
                        }
                } else return errorMsg('Post not found');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to get the details of post
    public function postDetails($id)
    {
        try{
            $id = encrypt_decrypt('decrypt', $id);
            $post = Post::where('id', $id)->first();
            $commentArr = array();
            $likesCount = UserLike::join('users as u', 'u.id', '=', 'user_likes.user_id')->where('u.status', 1)->where('user_likes.status', 1)->where('object_id', $id)->where('object_type', 'post')->count();
            $isLiked = UserLike::where('user_id', auth()->user()->id)->where('status', 1)->where('object_id', $id)->where('object_type', 'post')->count();
            $commentCount = 0;
            foreach($post->comments() as $key => $value){
                $reply = Comment::join('users as u', 'u.id', '=', 'comments.user_id')->where('u.status', 1)->where('object_id', $id)->where('object_type', 'post')->where('parent_id', $value->id)->select('comments.*')->get();
                $replyArr = array();
                $commentCount++;
                foreach($reply as $key1 => $value1){
                    $commentCount++;
                    $temp1['reply_id'] = $value1->id;
                    $temp1['reply_comment'] = $value1->comment;
                    $temp1['reply_posted_date'] = date('d M, Y h:i A', strtotime($value1->created_at));
                    $temp1['reply_posted_by'] = $value1->user->name ?? 'NA';
                    $temp1['reply_my_comment'] = $value1->user_id == auth()->user()->id ? true : false;
                    $temp1['reply_posted_by_user_name'] = $value1->user->user_name ?? 'NA';
                    $temp1['reply_posted_by_profile_image'] = $value1->user->profile;
                    $replyArr[] = $temp1;
                }
                $temp['comment_id'] = $value->id;
                $temp['comment'] = $value->comment;
                $temp['reply'] = $replyArr;
                $temp['my_comment'] = $value->user_id == auth()->user()->id ? true : false;
                $temp['posted_date'] = date('d M, Y h:i A', strtotime($value->created_at));
                $temp['posted_by'] = $value->user->name ?? 'NA';
                $temp['posted_by_user_name'] = $value->user->user_name ?? 'NA';
                $temp['posted_by_profile_image'] = $value->user->profile;
                $commentArr[] = $temp;
            };
            // dd($commentArr);
            return view('pages.admin.community.post-details')->with(compact('post', 'commentArr', 'commentCount', 'likesCount', 'isLiked'));
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }   
}
