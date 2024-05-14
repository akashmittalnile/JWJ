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
    public function revenueManagement()
    {
        try {
            $paymentReceived = UserPlan::sum('price');
            $list = UserPlan::join('plan', 'plan.id', '=', 'user_plans.plan_id')->join('users as u', 'user_plans.user_id', '=', 'u.id')->select('plan.name', 'plan.image', 'user_plans.plan_timeperiod', 'user_plans.activated_date', 'user_plans.renewal_date', 'user_plans.transaction_id', 'u.name as user_name', 'user_plans.price as paid_amount')->get();
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
            $stripe = new \Stripe\StripeClient(env("STRIPE_SECRET"));
            $plans = $stripe->products->all(['active' => true]);
            // dd($plans);
            foreach ($plans as $item) {
                $product_id = $item["id"];
                $price = $stripe->prices->all(['product' => $product_id]);
                $plan = Plan::where("product_id", $product_id)->first();
                // dd($price->data);
                if ($plan) {
                    foreach ($price->data as $val) {
                        if ($val->recurring->interval == 'month') {
                            $plan->monthly_price = $val->unit_amount / 100;
                            $plan->monthly_price_id = $val->id ?? null;
                        } else {
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
                    $plan->monthly_price = $price->unit_amount / 100;
                    $plan->monthly_price_id = $price->id ?? null;
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
