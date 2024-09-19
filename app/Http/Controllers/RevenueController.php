<?php

namespace App\Http\Controllers;

use App\Models\PaymentDetail;
use Stripe\Stripe;
use App\Models\Plan;
use App\Models\UserPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RevenueController extends Controller
{
    // Dev name : Dishant Gupta
    // This function is used to getting the list of stripe plans. Which is created in stripe account... product catalog
    public function revenueManagement(Request $request)
    {
        try {
            $paymentReceived = UserPlan::sum('price');
            $plan = Plan::where('status', 1)->get();
            if($request->ajax()) {
                $data = UserPlan::join('plan', 'plan.id', '=', 'user_plans.plan_id')->join('users as u', 'user_plans.user_id', '=', 'u.id');
                if($request->filled('planDate')){
                    $request->planDate = \Carbon\Carbon::createFromFormat('m-d-Y', $request->planDate)->format('Y-m-d');
                    $data->whereDate('user_plans.activated_date', $request->planDate);
                }
                if($request->filled('status')) $data->where('user_plans.plan_id', $request->status);
                if($request->filled('search')){
                    $data->whereRaw("(`u`.`user_name` LIKE '%" . $request->search . "%' or `u`.`name` LIKE '%" . $request->search . "%' or `u`.`email` LIKE '%" . $request->search . "%' or `u`.`mobile` LIKE '%" . $request->search . "%')");
                }
                $data = $data->select('plan.name', 'plan.image', 'user_plans.plan_timeperiod', 'user_plans.activated_date', 'user_plans.renewal_date', 'user_plans.transaction_id', 'u.name as user_name', 'user_plans.price as paid_amount', 'user_plans.created_at')->where('plan.status', 1)->orderByDesc('user_plans.id')->paginate(config('constant.paginatePerPage'));

                $html = '';
                foreach($data as $key => $val) {
                    $planImg = isset($val->image) ? assets('assets/images/'.$val->image) : assets('assets/images/no-image.jpg');
                    $timePeriod = ($val->plan_timeperiod == 1 ? 'Monthly' : ($val->plan_timeperiod == 2 ? 'Yearly' : 'One Time'));

                    $html .= "<tr>
                        <td>
                            <div class='sno'>". $key+1 ."</div>
                        </td>
                        <td>
                            $val->user_name
                        </td>
                        <td>
                            <img src='$planImg' height='24'>
                            $val->name
                        </td>
                        <td>
                            $$val->paid_amount
                        </td>
                        <td>
                            $timePeriod
                        </td>
                        <td>
                            ".date('m-d-Y', strtotime($val->activated_date))."
                        </td>
                        <td>
                            ".date('m-d-Y', strtotime($val->activated_date))."
                        </td>
                    </tr>";
                }
                if($data->total() < 1) return errorMsg("No revenue found");
                $response = array(
                    'currentPage' => $data->currentPage(),
                    'lastPage' => $data->lastPage(),
                    'total' => $data->total(),
                    'html' => $html,
                );
                return successMsg('Revenue list', $response);
            }

            return view('pages.admin.revenue.revenue-management')->with(compact('paymentReceived', 'plan'));
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to download a revenue report
    public function revenueDownloadReport(Request $request)
    {
        try {
            $data = UserPlan::join('plan', 'plan.id', '=', 'user_plans.plan_id')->join('users as u', 'user_plans.user_id', '=', 'u.id');
            if($request->filled('planDate')) $data->whereDate('user_plans.activated_date', $request->planDate);
            if($request->filled('status')) $data->where('user_plans.plan_id', $request->status);
            if($request->filled('search')){
                $data->whereRaw("(`u`.`user_name` LIKE '%" . $request->search . "%' or `u`.`name` LIKE '%" . $request->search . "%' or `u`.`email` LIKE '%" . $request->search . "%' or `u`.`mobile` LIKE '%" . $request->search . "%')");
            }
            $data = $data->where('plan.status', 1)->select('plan.name', 'plan.image', 'user_plans.plan_timeperiod', 'user_plans.activated_date', 'user_plans.renewal_date', 'user_plans.transaction_id', 'u.name as user_name', 'user_plans.price as paid_amount')->orderByDesc('user_plans.id')->get();
            $this->downloadRevenueReportFunction($data);
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to download the csv file of journey with journals revenue report
    public function downloadRevenueReportFunction($data)
    {
        try {
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="Users Revenue List "' . time() . '.csv');
            $output = fopen("php://output", "w");

            fputcsv($output, array('S.No.', 'User Name', 'Subscription Plan', 'Amount Paid', 'Billing Type', 'Plan Activate On', 'Amount Received On'));

            if (count($data) > 0) {
                foreach ($data as $key => $row) {

                    $final = [
                        $key + 1,
                        $row->user_name,
                        $row->name,
                        $row->paid_amount,
                        ($row->plan_timeperiod == 1 ? 'Monthly' : ($row->plan_timeperiod == 2 ? 'Yearly' : 'One Time')),
                        date('m-d-Y', strtotime($row->activated_date)),
                        date('m-d-Y', strtotime($row->renewal_date)),
                    ];

                    fputcsv($output, $final);
                }
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to show & sync the listing of all plans. which is created in stripe product
    public function subscriptionPlan()
    {
        try {
            $stripe = new \Stripe\StripeClient(env("STRIPE_SECRET"));
            $plans = $stripe->products->all(['active' => true]);
            // dd($plans);
            foreach ($plans as $item) {
                $product_id = $item["id"];
                $price = $stripe->prices->all(['product' => $product_id]);
                $plan = Plan::where("product_id", $product_id)->where('status', 1)->first();
                // dd($price->data);
                if ($plan) {
                    foreach ($price->data as $val) {
                        if (isset($val->recurring->interval) && ($val->recurring->interval == 'month')) {
                            $plan->monthly_price = $val->unit_amount / 100;
                            $plan->monthly_price_id = $val->id ?? null;
                        } else {
                            $plan->monthly_price = $val->unit_amount / 100;
                            $plan->monthly_price_id = $val->id ?? null;
                            $plan->anually_price = $val->unit_amount / 100;
                            $plan->anually_price_id = $val->id ?? null;
                        }
                        $plan->currency = $val->currency;
                    }
                    $plan->name = $item->name;
                    $plan->image = $item->description ?? null;
                    $plan->status = 1;
                    $plan->save();
                } else {
                    $plan = new Plan();
                    foreach ($price->data as $val) {
                        if (isset($val->recurring->interval) && ($val->recurring->interval == 'month')) {
                            $plan->monthly_price = $val->unit_amount / 100;
                            $plan->monthly_price_id = $val->id ?? null;
                        } else {
                            $plan->monthly_price = $val->unit_amount / 100;
                            $plan->monthly_price_id = $val->id ?? null;
                            $plan->anually_price = $val->unit_amount / 100;
                            $plan->anually_price_id = $val->id ?? null;
                        }
                        $plan->currency = $val->currency;
                    }
                    $plan->name = $item->name;
                    $plan->image = $item->description ?? null;
                    $plan->product_id = $product_id;
                    $plan->status = 1;
                    $plan->save();
                }
            }
            $plan = Plan::orderBy('monthly_price')->where('status', 1)->get();
            return view('pages.admin.revenue.plan')->with(compact('plan'));
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to getting all the data of plan by their id
    public function planDetails(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $id = encrypt_decrypt('decrypt', $request->id);
                $plan = Plan::where('id', $id)->where('status', 1)->first();
                if(isset($plan->id)) return successMsg('Plan found', $plan);
                else return errorMsg('Plan not found');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to getting all the data of plan by their id
    public function updatePlan(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'journal' => 'required',
                'routine' => 'required',
                'image' => 'required',
                'community' => 'required',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $id = encrypt_decrypt('decrypt', $request->id);
                $plan = Plan::where('id', $id)->where('status', 1)->first();
                if(isset($plan->id)) {
                    $plan->entries_per_day = $request->journal;
                    $plan->community = $request->community;
                    $plan->routines = $request->routine;
                    $plan->picture_per_day = $request->image;
                    $plan->updated_at = date('Y-m-d H:i:s');
                    $plan->save();
                    return successMsg('Plan updated successfully.');
                } else return errorMsg('Plan not found');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
