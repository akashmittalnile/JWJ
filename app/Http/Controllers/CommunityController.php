<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\CommunityImage;
use App\Models\Plan;
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
                $data = Community::join('users as u', 'u.id', '=', 'communities.created_by')->join('community_images as ci', 'ci.community_id', '=', 'communities.id')->join('plan as p', 'p.id', '=', 'communities.plan_id')->select('communities.*', 'u.role', 'ci.name as image_name', 'p.name as plan_name')->whereIn('communities.status', [1,2])->orderByDesc('communities.id');
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
                    $role = ($val->role==2) ? 'Admin' : 'User';
                    $checked = ($val->status==1) ? 'checked' : '';
                    $plan_type = (($val->plan_name=='Plan A') ? 'freeplan.svg' : ($val->plan_name=='Plan B' ? 'goldplan.svg' : 'platinumplan.svg'));
                    $html .= "<div class='col-md-6'>
                    <div class='jwj-community-card'>
                        <div class='jwjcard-head'>
                            <div class='jwjcard-group-card'>
                                <div class='jwjcard-group-avtar'>
                                    <img src='".assets("uploads/community/$val->image_name")."'>
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
                            <div id='communitycarousel' class=' communitycarousel owl-carousel owl-theme'>
                                <div class='item'>
                                    <div class='community-media'>
                                        <img src='".assets('assets/images/no-image.jpg')."'>
                                    </div>
                                </div>
                                <div class='item'>
                                    <div class='community-media'>
                                        <img src='".assets('assets/images/1.png')."'>
                                    </div>
                                </div>
                                <div class='item'>
                                    <div class='community-media'>
                                        <img src='".assets('assets/images/2.png')."'>
                                    </div>
                                </div>
                                <div class='item'>
                                    <div class='community-media'>
                                        <img src='".assets('assets/images/3.png')."'>
                                    </div>
                                </div>
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
                                            <p>0</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class='jwjcard-foot'>
                            <div class='jwjcard-member-item'>
                                <div class='jwjcard-member-info'>
                                    <span class='jwjcard-member-image image1'>
                                        <img src='".assets('assets/images/no-image.jpg')."'>
                                    </span>
                                    <span class='jwjcard-member-image image2'>
                                        <img src='".assets('assets/images/no-image.jpg')."'>
                                    </span>
                                    <span class='jwjcard-member-image image3'>
                                        <img src='".assets('assets/images/no-image.jpg')."'>
                                    </span>
                                </div>
                                <p>0 Member Follows</p>
                            </div>
                            <div class='community-plan-info'>
                                <img src='".assets("assets/images/$plan_type")."'>$val->plan_name
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
            $data = Community::join('users as u', 'u.id', '=', 'communities.created_by')->join('community_images as ci', 'ci.community_id', '=', 'communities.id')->join('plan as p', 'p.id', '=', 'communities.plan_id')->select('communities.*', 'u.role', 'ci.name as image_name', 'p.name as plan_name', 'u.name as user_name', 'u.profile as user_image')->where('communities.id', $id)->first();
            return view('pages.admin.community.details')->with(compact('data'));
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
                $data = Community::join('users as u', 'u.id', '=', 'communities.created_by')->join('community_images as ci', 'ci.community_id', '=', 'communities.id')->join('plan as p', 'p.id', '=', 'communities.plan_id')->select('communities.*', 'u.role', 'ci.name as image_name', 'p.name as plan_name', 'u.name as user_name', 'u.profile as user_image')->where('communities.status', 0)->orderByDesc('communities.id');
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
                    $role = ($val->role==2) ? 'Admin' : 'User';
                    $checked = ($val->status==1) ? 'checked' : '';
                    $plan_type = (($val->plan_name=='Plan A') ? 'freeplan.svg' : ($val->plan_name=='Plan B' ? 'goldplan.svg' : 'platinumplan.svg'));
                    $html .= "<div class='col-md-4'>
                    <div class='jwj-community-approval-card'>
                        <div class='jwjcard-head'>
                            <div class='jwjcard-member-item'>
                                <div class='jwjcard-member-info'>
                                    <span class='jwjcard-member-image image1'>
                                        <img src='".assets("uploads/profile/$val->user_image")."'>
                                    </span>
                                </div>
                                <p>$val->user_name</p>
                            </div>
                            <div class='jwjcard-group-action'>
                                <a class='managecommunity-btn' href='".route('admin.community-management.approval-details', encrypt_decrypt('encrypt', $val->id))."'>View Community</a>
                            </div>
                        </div>
                        <div id='communitycarous' class='communitycarous1 owl-carouse owl-them'>
                            <div class='item'>
                                <div class='community-approval-media'>
                                    <img src='".assets("uploads/community/$val->image_name")."'>
                                </div>
                            </div>
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
            $data = Community::join('users as u', 'u.id', '=', 'communities.created_by')->join('community_images as ci', 'ci.community_id', '=', 'communities.id')->join('plan as p', 'p.id', '=', 'communities.plan_id')->select('communities.*', 'u.role', 'ci.name as image_name', 'p.name as plan_name', 'u.name as user_name', 'u.profile as user_image')->where('communities.id', $id)->first();
            return view('pages.admin.community.approval-details')->with(compact('data'));
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
                $data = Community::join('users as u', 'u.id', '=', 'communities.created_by')->join('community_images as ci', 'ci.community_id', '=', 'communities.id')->join('plan as p', 'p.id', '=', 'communities.plan_id')->select('communities.*', 'u.role', 'ci.name as image_name', 'p.name as plan_name', 'u.name as user_name', 'u.profile as user_image')->where('communities.status', 3)->orderByDesc('communities.id');
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
                    $role = ($val->role==2) ? 'Admin' : 'User';
                    $checked = ($val->status==1) ? 'checked' : '';
                    $plan_type = (($val->plan_name=='Plan A') ? 'freeplan.svg' : ($val->plan_name=='Plan B' ? 'goldplan.svg' : 'platinumplan.svg'));
                    $html .= "<div class='col-md-4'>
                    <div class='jwj-community-approval-card Rejected-community-card'>
                        <div class='jwjcard-head'>
                            <div class='jwjcard-member-item'>
                                <div class='jwjcard-member-info'>
                                    <span class='jwjcard-member-image image1'>
                                        <img src='".assets("uploads/profile/$val->user_image")."'>
                                    </span>
                                </div>
                                <p>$val->user_name</p>
                            </div>
                            <div class='jwjcard-group-action'>
                                <a class='managecommunity-btn' href='".route('admin.community-management.approval-details', encrypt_decrypt('encrypt', $val->id))."'>View Community</a>
                            </div>
                        </div>
                        <div id='communitycarous' class='communitycarous1 owl-carouse owl-them'>
                            <div class='item'>
                                <div class='community-approval-media'>
                                    <img src='".assets("uploads/community/$val->image_name")."'>
                                </div>
                            </div>
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
