<?php

namespace App\Http\Controllers;

use App\Models\MoodMaster;
use App\Models\User;
use App\Models\UserMood;
use App\Models\UserPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

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
                    $userProfileImage = isset($val->profile) ? assets("uploads/profile/$val->profile") : assets("assets/images/no-image.jpg");
                    $status = ($val->status == 1) ? 'Active' : 'Inactive';
                    $html .= "<tr>
                    <td>
                        <div class='sno'>$index</div>
                    </td>
                    <td>
                        <img width='50' style='height: 50px; object-fit: cover; object-position: center; border-radius: 50%;' src=".$userProfileImage.">
                    </td>
                    <td>
                        $val->user_name
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
                                <a class='dropdown-item view-btn' href='javascript:void(0)'><i class='las la-eye'></i> Restrict</a>
                                <a class='dropdown-item view-btn' href='".route('admin.users.details', encrypt_decrypt('encrypt', $val->id))."'><i class='las la-eye'></i> View</a>
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
            $user = User::where('id', $id)->first();
            $plan = UserPlan::join('payment_details as pd', 'pd.user_payment_method_id', '=', 'user_plans.payment_id')->join('plan', 'plan.id', '=', 'user_plans.plan_id')->where('user_id', $id)->select('pd.amount', 'plan.name')->first();
            $list = UserPlan::join('payment_details as pd', 'pd.user_payment_method_id', '=', 'user_plans.payment_id')->join('plan', 'plan.id', '=', 'user_plans.plan_id')->where('user_id', $id)->select('pd.amount', 'plan.name', 'user_plans.activated_date', 'user_plans.renewal_date', 'user_plans.transaction_id')->get();
            $totalMood = UserMood::where('user_id', $id)->get();
            $happyCount = $sadCount = $anxietyCount = $angerCount = 0;
            foreach($totalMood as $val){
                $mood = MoodMaster::where('id', $val->mood_id)->first();
                if($mood->code=='happy') $happyCount ++;
                elseif($mood->code=='sad') $sadCount ++;
                elseif($mood->code=='anger') $angerCount ++;
                else $anxietyCount ++;
            }
            if(count($totalMood) > 0)
                $avgMood = ['happy' => number_format((float)($happyCount*100)/count($totalMood), 1, '.', ''), 'sad' => number_format((float)($sadCount*100)/count($totalMood), 1, '.', ''), 'anger' => number_format((float)($angerCount*100)/count($totalMood), 1, '.', ''), 'anxiety' => number_format((float)($anxietyCount*100)/count($totalMood), 1, '.', '')];
            else $avgMood = ['happy' => 0, 'sad' => 0, 'anger' => 0, 'anxiety' => 0];
            return view('pages.admin.user.details')->with(compact('user', 'plan', 'list', 'avgMood'));
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
    // This function is used to show user details page
    public function usersDownloadReport(Request $request)
    {
        try {
            $data = User::where('role', 1)->whereIn('status', [1,2]);
            if($request->filled('search')){
                $data->where('name', 'like', '%' . $request->search . '%')->orWhere('email', 'like', '%'. $request->search .'%')->orWhere('mobile', 'like', '%'. $request->search .'%');
            }
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
}
