<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use App\Models\Plan;
use Illuminate\Http\Request;

class RevenueController extends Controller
{
    public function revenueManagement()
    {
        try {
            return view('pages.admin.revenue.revenue-management');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

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
                    foreach($price->data as $val){
                        if($val->recurring->interval == 'month') {
                            $plan->monthly_price = $val->unit_amount / 100;
                        } else {
                            $plan->anually_price = $val->unit_amount / 100;
                        }
                        $plan->currency = $val->currency;
                        $plan->type = $val->recurring->interval;
                    }
                    $plan->name = $item->name;
                    $plan->price_id = $item["default_price"];
                    $plan->save();
                } else {
                    $plan = new Plan();
                    $plan->monthly_price = $price->unit_amount / 100;
                    $plan->name = $item->name;
                    $plan->currency = $price->currency;
                    $plan->product_id = $product_id;
                    $plan->price_id = $item["default_price"];
                    $plan->type = $price->recurring->interval;
                    $plan->save();
                }
            }
            $plan = Plan::orderByDesc('monthly_price')->get();
            return view('pages.admin.revenue.plan')->with(compact('plan'));
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
