<?php

namespace App\Http\Controllers;

use App\Models\User;
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
                $data = User::where('role', 1)->whereIn('status', [1,2]);
                if($request->filled('search')){
                    $data->where('name', 'like', '%' . $request->search . '%')->orWhere('email', 'like', '%'. $request->search .'%')->orWhere('mobile', 'like', '%'. $request->search .'%');
                }
                $data = $data->orderByDesc('id')->paginate(config('constant.paginatePerPage'));
                
                if($data->total() < 1) return errorMsg("No users found");

                $html = '';
                foreach($data as $key => $val)
                {
                    $pageNum = $data->currentPage();
                    $index = ($pageNum == 1) ? ($key + 1) : ($key + 1 + (config('constant.paginatePerPage') * ($pageNum - 1)));
                    $phone = isset($val->mobile) ? $val->country_code .' '. $val->mobile : 'NA';
                    $html .= "<tr>
                    <td>
                        <div class='sno'>$index</div>
                    </td>
                    <td>
                        $val->user_name
                    </td>
                    <td>
                        $val->name
                    </td>

                    <td>
                        $val->email
                    </td>
                    <td>
                        $phone
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
            return view('pages.admin.user.details')->with(compact('user', 'plan', 'list'));
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
