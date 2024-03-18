<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\CommunityImage;
use App\Models\Plan;
use App\Models\Post;
use App\Models\User;
use App\Models\UserFollowedCommunity;
use Illuminate\Http\Request;
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
                $data = Community::join('users as u', 'u.id', '=', 'communities.created_by')->select('communities.*', 'u.role')->whereIn('communities.status', [1,2])->orderByDesc('communities.id');
                if($request->filled('search')){
                    $data->where('communities.name', 'like', '%' . $request->search . '%')->orWhere('u.name', 'like', '%' . $request->search . '%')->orWhere('u.email', 'like', '%' . $request->search . '%')->orWhere('u.mobile', 'like', '%' . $request->search . '%');
                }
                if($request->filled('role')){
                    $data->where('u.role', $request->role);
                }

                $data = $data->paginate(config('constant.communityPerPage'));
                $html = '';
                foreach($data as $val)
                {
                    $image = CommunityImage::where('community_id', $val->id)->orderByDesc('id')->first();
                    $imgs = CommunityImage::where('community_id', $val->id)->get();
                    $image_html = "";
                    foreach($imgs as $name){
                        if($name->name != $image->name){
                            $image_html .= "<div class='item'>
                            <div class='community-media'>
                                    <img src='".assets("uploads/community/$name->name")."'>
                                </div>
                            </div>";
                        }
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
                            $asset = isset($followedUser->profile) ? assets("uploads/profile/$followedUser->profile") : assets('assets/images/no-image.jpg');
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
                                                <p>Inactive</p>
                                                <div class=''>
                                                    <label class='toggle' for='myToggle".$val->id."'>
                                                        <input data-id='".encrypt_decrypt('encrypt', $val->id)."' class='toggle__input' name='status".$val->id."' $checked type='checkbox' id='myToggle".$val->id."'>
                                                        <div class='toggle__fill'></div>
                                                    </label>
                                                </div>
                                                <p>Active</p>
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
                                <p>$followcount Member Follows</p>
                            </div>
                            $plan_html
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
                'subscription' => 'required',
                'file' => 'required',
                'description' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $community = new Community;
                $community->name = $request->title;
                $community->plan_id = $request->subscription;
                $community->description = $request->description;
                $community->status = 1;
                $community->created_by = auth()->user()->id ?? null;
                $community->save();
                
                if ($request->hasFile("file")) {
                    foreach ($request->file('file') as $value) {
                        $name = "JWJ_" .  time() . rand() . "." . $value->getClientOriginalExtension();
                        $value->move("public/uploads/community", $name);
                        $communityImage = new CommunityImage;
                        $communityImage->community_id = $community->id;
                        $communityImage->name = $name;
                        $communityImage->type = 'image';
                        $communityImage->save();
                    }
                }

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
                return successMsg('Status changed successfully');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to getting the data of particular community
    public function communityManagementDetails($id)
    {
        try {
            $id = encrypt_decrypt('decrypt', $id);
            $data = Community::join('users as u', 'u.id', '=', 'communities.created_by')->select('communities.*', 'u.role', 'u.name as user_name', 'u.profile as user_image')->where('communities.id', $id)->first();
            $imgs = CommunityImage::where('community_id', $id)->get();
            $follow = UserFollowedCommunity::where('community_id', $id)->orderByDesc('id')->get();
            return view('pages.admin.community.details')->with(compact('data', 'follow', 'imgs'));
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
                $data = Community::join('users as u', 'u.id', '=', 'communities.created_by')->select('communities.*', 'u.role', 'u.name as user_name', 'u.profile as user_image')->where('communities.status', 0)->orderByDesc('communities.id');
                if($request->filled('search')){
                    $data->where('communities.name', 'like', '%' . $request->search . '%')->orWhere('u.name', 'like', '%' . $request->search . '%')->orWhere('u.email', 'like', '%' . $request->search . '%')->orWhere('u.mobile', 'like', '%' . $request->search . '%');
                }
                if($request->filled('role')){
                    $data->where('u.role', $request->role);
                }

                $data = $data->paginate(config('constant.communityPerPage'));
                $html = '';
                foreach($data as $val)
                {
                    $userProfileImage = isset($val->user_image) ? assets("uploads/profile/$val->user_image") : assets("assets/images/no-image.jpg");
                    $imgs = CommunityImage::where('community_id', $val->id)->get();
                    $image_html = "";
                    foreach($imgs as $name){
                        $image_html .= "<div class='item'>
                        <div class='community-approval-media'>
                                <img src='".assets("uploads/community/$name->name")."'>
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
            $data = Community::join('users as u', 'u.id', '=', 'communities.created_by')->join('community_images as ci', 'ci.community_id', '=', 'communities.id')->select('communities.*', 'u.role', 'ci.name as image_name', 'u.name as user_name', 'u.profile as user_image')->where('communities.id', $id)->first();
            $imgs = CommunityImage::where('community_id', $id)->get();
            return view('pages.admin.community.approval-details')->with(compact('data', 'imgs'));
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
                    $data->where('communities.name', 'like', '%' . $request->search . '%')->orWhere('u.name', 'like', '%' . $request->search . '%')->orWhere('u.email', 'like', '%' . $request->search . '%')->orWhere('u.mobile', 'like', '%' . $request->search . '%');
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
                                <img src='".assets("uploads/community/$name->name")."'>
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
                    $userProfileImage = isset($val->user_image) ? assets("uploads/profile/$val->user_image") : assets("assets/images/no-image.jpg");
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

    public function communityPostDetails($id)
    {
        try {
            $id = encrypt_decrypt('decrypt', $id);
            return view('pages.admin.community.post-details');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
