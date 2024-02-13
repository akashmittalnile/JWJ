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
    public function revenueManagement()
    {
        try {
            $paymentReceived = PaymentDetail::sum('amount');
            $list = UserPlan::join('payment_details as pd', 'pd.user_payment_method_id', '=', 'user_plans.payment_id')->join('plan', 'plan.id', '=', 'user_plans.plan_id')->join('users as u', 'user_plans.user_id', '=', 'u.id')->select('pd.amount', 'plan.name', 'user_plans.activated_date', 'user_plans.renewal_date', 'user_plans.transaction_id', 'u.name as user_name')->get();
            return view('pages.admin.revenue.revenue-management')->with(compact('list', 'paymentReceived'));
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to show & sync the listing of all plans. which is created in stripe product
    public function subscriptionPlan()
    {
        try {
            $stripe = new \Stripe\StripeClient(env("STRIPE_TEST_KEY"));
            $plans = $stripe->products->all(['limit' => 3]);
            // dd($plans);
            foreach ($plans as $item) {
                $product_id = $item["id"];
                $price = $stripe->prices->all(['product' => $product_id]);
                $plan = Plan::where("product_id", $product_id)->first();
                // dd($plan, $item);
                if ($plan) {
                    foreach ($price->data as $val) {
                        if ($val->recurring->interval == 'month') {
                            $plan->monthly_price = $val->unit_amount / 100;
                        } else {
                            $plan->anually_price = $val->unit_amount / 100;
                        }
                        $plan->currency = $val->currency;
                    }
                    $plan->name = $item->name;
                    $plan->status = 1;
                    $plan->save();
                } else {
                    $plan = new Plan();
                    $plan->monthly_price = $price->unit_amount / 100;
                    $plan->name = $item->name;
                    $plan->currency = $price->currency;
                    $plan->product_id = $product_id;
                    $plan->status = 1;
                    $plan->save();
                }
            }
            $plan = Plan::orderBy('monthly_price')->get();
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
                $plan = Plan::where('id', $id)->first();
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
                'entries' => 'required',
                'words' => 'required',
                'picture' => 'required',
                'routine' => 'required',
                'community' => 'required',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $id = encrypt_decrypt('decrypt', $request->id);
                $plan = Plan::where('id', $id)->first();
                if(isset($plan->id)) {
                    $plan->entries_per_day = $request->entries;
                    $plan->words = $request->words;
                    $plan->picture_per_day = $request->picture;
                    $plan->community = $request->community;
                    $plan->routines = $request->routine;
                    $plan->updated_at = date('Y-m-d H:i:s');
                    $plan->save();
                    return successMsg('Plan update successfully.');
                } else return errorMsg('Plan not found');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
