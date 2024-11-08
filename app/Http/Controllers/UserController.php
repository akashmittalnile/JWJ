<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\Journal;
use App\Models\MoodMaster;
use App\Models\Notify;
use App\Models\Routine;
use App\Models\RoutineCategory;
use App\Models\SharingDetail;
use App\Models\User;
use App\Models\UserMood;
use App\Models\UserPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    // Dev name : Dishant Gupta
    // This function is used to show the registered users
    public function users(Request $request)
    {
        try {
            if($request->ajax()){
                $data = User::where('role', 1);
                if($request->filled('search')){
                    $data->whereRaw("(`name` LIKE '%" . $request->search . "%' or `email` LIKE '%" . $request->search . "%' or `mobile` LIKE '%" . $request->search . "%')");
                }
                if($request->filled('ustatus')){
                    $data->where('status', $request->ustatus);
                } else $data->whereIn('status', [1,2]);
                $data = $data->orderByDesc('id')->paginate(config('constant.paginatePerPage'));
                
                if($data->total() < 1) return errorMsg("No users found");

                $html = '';
                foreach($data as $key => $val)
                {
                    $pageNum = $data->currentPage();
                    $index = ($pageNum == 1) ? ($key + 1) : ($key + 1 + (config('constant.paginatePerPage') * ($pageNum - 1)));
                    $phone = isset($val->mobile) ? $val->country_code .' '. $val->mobile : 'NA';
                    $userProfileImage = (isset($val->profile) && File::exists(public_path('uploads/profile/'.$val->profile)) ) ? assets("uploads/profile/$val->profile") : assets("assets/images/no-image.jpg");
                    $status = ($val->status == 1) ? 'Active' : 'Inactive';
                    $userName = (isset($val->user_name) && $val->user_name != '' && $val->user_name != 'undefined') ? $val->user_name : 'NA';
                    $html .= "<tr>
                    <td>
                        <div class='sno'>$index</div>
                    </td>
                    <td>
                        <img width='50' style='height: 50px; object-fit: cover; object-position: center; border-radius: 50%;' src='".$userProfileImage."'>
                    </td>
                    <td>
                        $userName
                    </td>
                    <td>
                        $val->name
                    </td>

                    <td>
                        <a href='mailto:".$val->email."'><img width='20' src=".assets('assets/images/sms1.svg')."></a> $val->email
                    </td>
                    <td>
                        <a href='tel:".$phone."'><img width='20' src=".assets('assets/images/call1.svg')."></a> $phone
                    </td>
                    <td>
                        $status
                    </td>
                    <td>
                        <div class='action-btn-info'>
                            <a class='action-btn dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='false'>
                                <i class='las la-ellipsis-v'></i>
                            </a>
                            <div class='dropdown-menu'>
                                <a class='dropdown-item view-btn' href='".route('admin.users.details', encrypt_decrypt('encrypt', $val->id))."'><i class='las la-eye'></i> &nbsp; View</a>
                            </div>
                        </div>
                    </td>
                </tr>";
                }

                $response = array(
                    'currentPage' => $data->currentPage(),
                    'lastPage' => $data->lastPage(),
                    'total' => $data->total(),
                    'html' => $html,
                );
                return successMsg('Users list', $response);
            }
            return view('pages.admin.user.list');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to show user details page
    public function userDetails($id)
    {
        try {
            $id = encrypt_decrypt('decrypt', $id);
            $user = User::where('id', $id)->with('mood')->first();
            $plan = UserPlan::join('plan', 'plan.id', '=', 'user_plans.plan_id')->where('user_plans.user_id', $id)->where('user_plans.status', 1)->where('plan.status', 1)->select('plan.name', 'user_plans.price', 'user_plans.plan_timeperiod', 'plan.image')->first();
            $totalSum = UserPlan::where('user_plans.user_id', $id)->sum('price');
            $list = UserPlan::join('plan', 'plan.id', '=', 'user_plans.plan_id')->where('user_id', $id)->where('plan.status', 1)->select('plan.name', 'user_plans.activated_date', 'user_plans.renewal_date', 'user_plans.transaction_id', 'user_plans.plan_timeperiod', 'user_plans.price')->orderByDesc('user_plans.id')->get();
            $allMood = MoodMaster::get();
            $mood = array();
            foreach($allMood as $val){
                $total = count($user->mood) ?? 0;
                $subTotal = UserMood::where('mood_id', $val->id)->where('user_id', $id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count();
                $temp['id'] = $val->id;
                $temp['name'] = $val->name;
                $temp['code'] = $val->code;
                $temp['logo'] = $val->logo;
                $temp['total'] = $total;
                $temp['subTotal'] = $subTotal;
                $temp['avg'] = ($total==0) ? 0 : number_format((float)($subTotal*100)/$total, 1, '.', '');
                $mood[] = $temp;
            }
            // dd($mood);
            $totalCommunity = Community::where('created_by', $id)->count();
            $totalRoutine = Routine::where('created_by', $id)->count();
            $totalJournal = Journal::where('created_by', $id)->count();
            
            return view('pages.admin.user.details')->with(compact('user', 'plan', 'list', 'mood', 'totalCommunity', 'totalRoutine', 'totalJournal', 'totalSum'));
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to getting the details of user mood
    public function userChangeMoodData(Request $request, $id)
    {
        try {
            $id = encrypt_decrypt('decrypt', $id);
            $allMood = MoodMaster::get();
            $mood = array();
            foreach($allMood as $val){
                $total = UserMood::where('user_id', $id)->whereMonth('created_at', date('m', strtotime($request->date)))->whereYear('created_at', date('Y', strtotime($request->date)))->count();
                $subTotal = UserMood::where('mood_id', $val->id)->where('user_id', $id)->whereMonth('created_at', date('m', strtotime($request->date)))->whereYear('created_at', date('Y', strtotime($request->date)))->count();
                $temp['id'] = $val->id;
                $temp['name'] = $val->name;
                $temp['code'] = $val->code;
                $temp['logo'] = $val->logo;
                $temp['total'] = $total;
                $temp['subTotal'] = $subTotal;
                $temp['avg'] = ($total==0) ? 0 : number_format((float)($subTotal*100)/$total, 1, '.', '');
                $mood[] = $temp;
            }
            return successMsg('Change mood data', $mood);
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to change user status (active/inactive) by admin
    public function userChangeStatus(Request $request)
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
                $user = User::where('id', $id)->update([
                    'status'=> $request->status
                ]);
                return successMsg('Status changed successfully');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to change the notification from unseen to seen
    public function notifySeen(Request $request)
    {
        try {
            Notify::where('receiver_id', auth()->user()->id)->update(['is_seen' => '2']);
            return response()->json(['status' => true]);
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to clear all the notification
    public function clearNotification(Request $request)
    {
        try {
            Notify::where('receiver_id', auth()->user()->id)->delete();
            return redirect()->back()->with('success', 'All notifications cleared.');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to download a users report
    public function usersDownloadReport(Request $request)
    {
        try {
            $data = User::where('role', 1);
            if($request->filled('search')){
                $data->whereRaw("(`name` LIKE '%" . $request->search . "%' or `email` LIKE '%" . $request->search . "%' or `mobile` LIKE '%" . $request->search . "%')");
            }
            if($request->filled('ustatus')){
                $data->where('status', $request->ustatus);
            } else $data->whereIn('status', [1,2]);
            $data = $data->orderByDesc('id')->get();
            $this->downloadUserReportFunction($data);
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to download the csv file of journey with journals registered users
    public function downloadUserReportFunction($data)
    {
        try {
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="Users List "' . time() . '.csv');
            $output = fopen("php://output", "w");

            fputcsv($output, array('S.No.', 'Name', 'Email ID', 'Contact Number'));

            if (count($data) > 0) {
                foreach ($data as $key => $row) {

                    $final = [
                        $key + 1,
                        $row->name,
                        $row->email,
                        $row->country_code . ' ' . $row->mobile,
                    ];

                    fputcsv($output, $final);
                }
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    
    // Dev name : Dishant Gupta
    // This function is used to show the list of user routines
    public function userRoutines(Request $request, $id)
    {
        try {
            $id = encrypt_decrypt('decrypt', $id);
            $rCategory = RoutineCategory::where('status', 1)->orderByDesc('id')->get();
            if($request->ajax()){
                $data = Routine::where('created_by', $id);
                if($request->filled('search')){
                    $data->whereRaw("(`name` LIKE '%" . $request->search . "%' or `description` LIKE '%" . $request->search . "%')");
                }
                if($request->filled('ustatus')){
                    $data->where('category_id', $request->ustatus);
                }
                $data = $data->orderByDesc('id')->paginate(config('constant.paginatePerPage'));
                
                if($data->total() < 1) return errorMsg("No routine found");

                $html = '';
                foreach($data as $key => $val)
                {
                    $pageNum = $data->currentPage();
                    $index = ($pageNum == 1) ? ($key + 1) : ($key + 1 + (config('constant.paginatePerPage') * ($pageNum - 1)));
                    $phone = isset($val->mobile) ? $val->country_code .' '. $val->mobile : 'NA';
                    $image = isset($val->category->logo) ? assets("uploads/routine/".$val->category->logo) : assets("assets/images/no-image.jpg");
                    $description = (strlen($val->description) > 50) ? substr($val->description, 0, 50).'....' : $val->description;
                    $html .= "<tr>
                    <td>
                        <div class='sno'>$index</div>
                    </td>
                    <td>
                        <img width='50' style='height: 50px; object-fit: cover; object-position: center; border-radius: 50%;' src='".$image."'>
                    </td>
                    <td>
                        ".$val->category->name."
                    </td>
                    <td>
                        $val->name
                    </td>
                    <td>
                        $description
                    </td>
                    <td>
                        <div class='action-btn-info'>
                            <a class='action-btn dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='false'>
                                <i class='las la-ellipsis-v'></i>
                            </a>
                            <div class='dropdown-menu'>
                                <a class='dropdown-item view-btn' href='".route('admin.users.routine.details', encrypt_decrypt('encrypt', $val->id))."'><i class='las la-eye'></i> &nbsp; View</a>
                            </div>
                        </div>
                    </td>
                </tr>";
                }

                $response = array(
                    'currentPage' => $data->currentPage(),
                    'lastPage' => $data->lastPage(),
                    'total' => $data->total(),
                    'html' => $html,
                );
                return successMsg('Routine list', $response);
            }
            return view('pages.admin.user.routines')->with(compact('id', 'rCategory'));
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to getting the all details of routine
    public function userRoutineDetails($id)
    {
        try {
            $id = encrypt_decrypt('decrypt', $id);
            $data = Routine::where('id', $id)->first();
            $share = SharingDetail::where('routine_id', $id)->orderByDesc('id')->get();
            return view('pages.admin.user.routine-details')->with(compact('data', 'share'));
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
