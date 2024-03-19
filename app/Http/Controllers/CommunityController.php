<?php

namespace App\Http\Controllers;

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
                                <img src='".assets("uploads/community/$name->name")."'>
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
                        $name = fileUpload($value, "/uploads/community/");
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
                $data = Community::join('users as u', 'u.id', '=', 'communities.created_by')->select('communities.*', 'u.role', 'u.name as user_name', 'u.profile as user_image')->where('communities.status', 0);
                if($request->filled('search')){
                    $data->whereRaw("(`communities`.`name` LIKE '%" . $request->search . "%' or `u`.`name` LIKE '%" . $request->search . "%' or `u`.`email` LIKE '%" . $request->search . "%' or `u`.`mobile` LIKE '%" . $request->search . "%')");
                }
                if($request->filled('role')){
                    $data->where('u.role', $request->role)->orderByDesc('communities.id');
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


    public function createPost(Request $request)
    {
        try {
            // Validate incoming request data
            $validator = Validator::make($request->all(), [
                'title' => 'required|string',
                'post_description' => 'required|string',
                'subscription_plan' => 'required|string|in:A,B,C',
                'images.*' => 'image|max:2048', // Validate each image uploaded
                'community_id' => 'required|string',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()], 400);
            } else {
                // Define plan IDs using an associative array for constant lookup time
                $planIds = [
                    'A' => 6,
                    'B' => 5,
                    'C' => 4
                ];
                // Handle file uploads if provided
                $imagePaths = []; // Initialize array to store image paths
                if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $image) {
                        $imageName = "post_" . time() . '_' . $image->getClientOriginalName();
                        $image->move(public_path('uploads/community/post'), $imageName);
                        $imagePaths[] = 'uploads/community/post/' . $imageName;
                    }
                }
                // Create new post instance
                $post = new Post();
                $post->plan_id = $planIds[$request->input('subscription_plan')];
                $post->community_id = encrypt_decrypt('decrypt', $request->community_id);
                $post->title = $request->input('title');
                $post->post_description = $request->input('post_description');
                $post->created_by = auth()->user()->id ?? null;
                $post->save();
                // Save post images to post_images table
                foreach ($imagePaths as $path) {
                    $postImage = new PostImage;
                    $postImage->post_id = $post->id;
                    $postImage->name = $path;
                    $postImage->type = 'image';
                    $postImage->save();
                }
                return response()->json(['message' => 'Post created successfully'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Exception => ' . $e->getMessage()], 500);
        }
    }

    public function communityPosts($id)
    {
        try {
            $id = encrypt_decrypt('decrypt', $id);
            $community = Community::findOrFail($id);
            $posts = Post::where('community_id', $id)->get();
            return view('pages.admin.community.details', compact('community', 'posts'));
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
    
    public function getCommunityPosts(Request $request)
    {
        try {
            // Decrypt the community ID
            $communityId = encrypt_decrypt('decrypt', $request->id);
            // Retrieve the community
            $community = Community::findOrFail($communityId);
            // Retrieve posts associated with the community along with the user's name and post images
            $posts = Post::where('community_id', $communityId)
                ->leftJoin('users', 'posts.created_by', '=', 'users.id')
                ->leftJoin('post_images', 'posts.id', '=', 'post_images.post_id')
                ->select('posts.id', 'posts.title', 'posts.post_description', 'posts.created_at', 'posts.updated_at', 'users.name as user_name', 'post_images.name as image_path', 'posts.plan_id') // Include plan_id in the select statement
                ->groupBy('posts.id', 'posts.title', 'posts.post_description', 'posts.created_at', 'posts.updated_at', 'users.name', 'post_images.name', 'posts.plan_id');
            // Apply search filters
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $posts->where(function ($query) use ($searchTerm) {
                    $query->where('posts.title', 'like', '%' . $searchTerm . '%')
                        ->orWhere('posts.post_description', 'like', '%' . $searchTerm . '%')
                        ->orWhere('users.name', 'like', '%' . $searchTerm . '%');
                });
            }
            // Get the posts after applying filters
            $posts = $posts->get();
            // Format posts and their images
            $formattedPosts = [];
            foreach ($posts as $post) {
                $postId = $post->id;
                if (!isset($formattedPosts[$postId])) {
                    $formattedPosts[$postId] = [
                        'id' => $postId,
                        'title' => $post->title,
                        'post_description' => $post->post_description,
                        'user_name' => $post->user_name,
                        'created_at' => $post->created_at,
                        'updated_at' => $post->updated_at,
                        'images' => [], // Initialize an empty array for images
                        'plan_id' => $post->plan_id // Add plan_id to the formatted post data
                    ];
                }
                // Add the image to the post's images array
                if (!empty($post->image_path)) {
                    $formattedPosts[$postId]['images'][] = $post->image_path;
                }
            }
            // Return the view with community and formatted posts data
            return response()->json(['status' => true, 'data' => ['community' => $community, 'posts' => array_values($formattedPosts)]]);
        } catch (\Exception $e) {
            // Handle any exceptions and return an error message
            return response()->json(['status' => false, 'message' => 'Exception => ' . $e->getMessage()]);
        }
    }
    public function deletePost(Request $request)
    {
        try {
            if ($request->ajax()) {
                // Check if post ID is present and not empty
                if ($request->has('postId') && $request->postId !== '') {
                    $postId = $request->postId;
                    // Load the post by ID
                    $post = Post::findOrFail($postId);
                    // Delete the post
                    $post->delete();
                    // Return success response
                    return response()->json([
                        'status' => true,
                        'message' => 'Post deleted successfully.'
                    ]);
                } else {
                    // Return error if post ID is missing
                    return response()->json([
                        'status' => false,
                        'error' => 'Post ID is missing in the request.',
                    ]);
                }
            } else {
                // Return error if not an AJAX request
                return response()->json([
                    'status' => false,
                    'error' => 'This endpoint only accepts AJAX requests.',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'Error deleting post: ' . $e->getMessage(),
            ]);
        }
    }
}
