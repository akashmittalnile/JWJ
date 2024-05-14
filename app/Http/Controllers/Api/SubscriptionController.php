<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\User;
use App\Models\UserPlan;
use Stripe\Stripe;
use Stripe\Customer;
use Laravel\Cashier\Subscription as Subscribe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SubscriptionController extends Controller
{
    // Dev name : Dishant Gupta
    // This function is used to show all the plans
    public function plans(Request $request)
    {
        try {
            $plan = Plan::orderBy('monthly_price')->get();
            $response = array();
            foreach ($plan as $val) {
                $temp['id'] = $val->id;
                $temp['name'] = $val->name;
                $temp['monthly_price'] = $val->monthly_price;
                $temp['monthly_price_id'] = $val->monthly_price_id;
                $temp['anually_price'] = $val->anually_price;
                $temp['anually_price_id'] = $val->anually_price_id;
                $temp['currency'] = $val->currency;
                $temp['current_plan'] = ($val->monthly_price == 0) ? true : false;
                $temp['point1'] = $val->entries_per_day . ' Entry Per Day / ' . $val->words . ' Words';
                $temp['point2'] = $val->routines . ' Routines With Ability To Share';
                $temp['point3'] = 'Add ' . $val->picture_per_day . ' Picture Per Day';
                $temp['point4'] = (($val->community == 3) ? 'Submit Your Own Communities/ App Approval Required' : ($val->community == 2 ? 'Participate In Communities' : 'View Community'));
                $response[] = $temp;
            }
            return successMsg('Plans list', $response);
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to purchase a plan
    public function buyPlan(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'plan_id' => 'required',
                'plan_timeperiod' => 'required',
                'price_id' => 'required',
                'price' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $stripe = new \Stripe\StripeClient(env("STRIPE_SECRET"));
                Stripe::setApiKey(env("STRIPE_SECRET"));
                $user = User::where('id', auth()->user()->id)->first();
                $plan = Plan::where('id', $request->plan_id)->first();
                if(isset($plan->id)){
                    $customer = null;
                    if ($user->customer_id) {
                        $customer =  Customer::retrieve(
                            $user->customer_id,
                            []
                        );
                    } else {
                        if (!(isset($request->stripeToken))) return errorMsg('Stripe token must be required');
                        $customer = Customer::create([
                            'email' => $user->email,
                            'name' => $user->name,
                            "source" => $request->stripeToken
                        ]);
                    }
                    if ($customer && $request->price_id) {
                        
                        $subscription =  $stripe->subscriptions->create([
                            'customer' => $customer->id,
                            'items' => [
                                ['price' => $request->price_id],
                            ],
                        ]);
                    } else return errorMsg('Customer could not be created in stripe');
                    if (isset($subscription->id)) {
                        $user->subscription_id = $subscription->id;
                        $user->customer_id = $customer->id;
                        $user->plan_id = $request->plan_id;
                        $user->save();
                        $msg = "Subscription has been done successfully.";
                        $userPlanExist = UserPlan::where("user_id", $user->id)->where("status", 1)->count();
                        if ($userPlanExist) {
                            $userPlanExists = UserPlan::where("user_id", $user->id)->where("status", 1)->first();
                            $userPlanExists->status = 2;
                            $userPlanExists->save();
                            $stripe->subscriptions->cancel($userPlanExists->subscription_id, []);
                            $msg = "Subscription has been updated successfully.";
                        }

                        $userPlan = new UserPlan;
                        $userPlan->user_id = auth()->user()->id;
                        $userPlan->plan_id = $plan->id;
                        $userPlan->plan_timeperiod = $request->plan_timeperiod;
                        $userPlan->price_id = $request->price_id;
                        $userPlan->price = $request->price;
                        $userPlan->subscription_id = $subscription->id;
                        $userPlan->status = 1;
                        $userPlan->activated_date = date('Y-m-d H:i:s');
                        $userPlan->save();
                        return successMsg($msg);
                    } else return errorMsg('Something went wrong with subscription process');
                } else return errorMsg('Invalid plan');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
