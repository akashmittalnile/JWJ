<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\User;
use App\Models\UserPlan;
use Stripe;
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
            $plan = Plan::orderBy('monthly_price')->where('status', 1)->get();
            $response = array();
            $myPlan = UserPlan::where('user_id', auth()->user()->id)->where('status', 1)->first();
            foreach ($plan as $val) {
                if(isset($myPlan->id)){
                    if($myPlan->plan_id == $val->id) $currentPlan = true;
                    else $currentPlan = false;
                } else {
                    if(6 == $val->id) $currentPlan = true;
                    else $currentPlan = false;
                }
                $temp['id'] = $val->id;
                $temp['product_id'] = $val->product_id;
                $temp['name'] = $val->name;
                $temp['image'] = isset($val->image) ? assets('assets/images/'.$val->image) : assets('assets/images/no-image.jpg');
                $temp['monthly_price'] = $val->monthly_price;
                $temp['monthly_price_id'] = $val->monthly_price_id;
                $temp['anually_price'] = $val->anually_price;
                $temp['anually_price_id'] = $val->anually_price_id;
                $temp['currency'] = $val->currency;
                $temp['current_plan'] = $currentPlan;
                $temp['point1'] = $val->entries_per_day . ' Journals Per Day';
                $temp['point2'] = $val->routines . ' Routines Per Day';
                $temp['point3'] = $val->picture_per_day . ' Images Per Journal';
                $temp['point4'] = (($val->community == 3) ? 'Submit Your Own Communities/ App Approval Required' : ($val->community == 2 ? 'Participate In Communities' : 'View Community'));
                $response[] = $temp;
            }
            return successMsg('Plans list', $response);
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to cancel a plan
    public function cancelPlan(Request $request)
    {
        try {
            $stripe = new \Stripe\StripeClient(env("STRIPE_SECRET"));
            Stripe\Stripe::setApiKey(env("STRIPE_SECRET"));
            $userPlanExists = UserPlan::where("user_id", auth()->user()->id)->where("status", 1)->first();
            if (isset($userPlanExists->id)) {
                $userPlanExists->status = 2;
                $userPlanExists->save();
                $user = User::where('id', auth()->user()->id)->first();
                $user->subscription_id = null;
                $user->plan_id = null;
                $user->save();
                if($userPlanExists->type == 1)
                    $stripe->subscriptions->cancel($userPlanExists->subscription_id, []);
                return successMsg('Subscription cancelled successfully.');
            } else return errorMsg('Subscription not found!');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to getting the list of save card lists
    public function cardList(Request $request)
    {
        try {
            $stripe = new \Stripe\StripeClient(env("STRIPE_SECRET"));
            $user = User::where("id", auth()->user()->id)->where("status", 1)->first();
            if(isset($user->customer_id)){
                $list = $stripe->customers->allPaymentMethods($user->customer_id, []);
                if (count($list) > 0) {
                    $response = array();
                    foreach($list as $val){
                        $temp['card_id'] = $val['id'] ?? null;
                        $temp['brand'] = $val['card']['brand'] ?? null;
                        $temp['exp_month'] = $val['card']['exp_month'] ?? null;
                        $temp['exp_year'] = $val['card']['exp_year'] ?? null;
                        $temp['funding'] = $val['card']['funding'] ?? null;
                        $temp['last4'] = $val['card']['last4'] ?? null;
                        $temp['type'] = $val['type'] ?? null;
                        $response[] = $temp;
                    }
                    return successMsg('Card list', $response);
                } else return errorMsg('Card not found!');
            } else return errorMsg('Card not found!');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to set a default card
    public function setDefaultCard(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'card_id' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                // dd(auth()->user()->subscription_id);
                $stripe = new \Stripe\StripeClient(env("STRIPE_SECRET"));
                Stripe\Stripe::setApiKey(env("STRIPE_SECRET"));
                \Stripe\Subscription::update(
                    auth()->user()->subscription_id,
                    [
                      'default_payment_method' => $request->card_id,
                    ]
                );
                return successMsg('Default card changed successfully');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to add a new card
    public function addCard(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'stripeToken' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $stripe = new \Stripe\StripeClient(env("STRIPE_SECRET"));
                Stripe\Stripe::setApiKey(env("STRIPE_SECRET"));
                $user = User::where('id', auth()->user()->id)->first();
                $customer = null;
                if ($user->customer_id) {
                    $customer =  Customer::retrieve(
                        $user->customer_id,
                        []
                    );
                    if(isset($customer->id)){
                        $stripe->customers->createSource($customer->id, ['source' => $request->stripeToken]);
                    }
                } else {
                    $customer = Customer::create([
                        'email' => $user->email,
                        'name' => $user->name,
                        "source" => $request->stripeToken
                    ]);
                }
                if(isset($customer->id)){
                    $user->customer_id = $customer->id;
                    $user->save();
                }
                return response()->json([
                    'status' => true,
                    'message' => 'Card added successfully.',
                ]);
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    public function deleteCard(Request $request) {
        try{
            $validator = Validator::make($request->all(), [
                'card_id' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $stripe = new \Stripe\StripeClient(env("STRIPE_SECRET"));
                Stripe\Stripe::setApiKey(env("STRIPE_SECRET"));
                $user = User::where('id', auth()->user()->id)->first();
                if(isset($user->customer_id)){
                    $stripe->customers->deleteSource(
                        $user->customer_id,
                        $request->card_id,
                        []
                    );
                    return successMsg('Card deleted successfully');
                } else errorMsg('Customer not found');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to getting the list of transaction lists
    public function transactionList(Request $request)
    {
        try {
            $stripe = new \Stripe\StripeClient(env("STRIPE_SECRET"));
            $user = User::where("id", auth()->user()->id)->where("status", 1)->first();
            if(isset($user->customer_id)){
                $invoice = $stripe->invoices->all(['customer'=> $user->customer_id]);
                if (count($invoice) > 0) {
                    $response = array();
                    foreach($invoice as $val){
                        $temp['total'] = isset($val['total']) ? number_format((float)($val['total']/100), 2, '.', '') : null;
                        $temp['currency'] = $val['currency'] ?? null;
                        $temp['paid'] = $val['paid'] ?? null;
                        $temp['invoice_number'] = $val['number'] ?? null;
                        $temp['date'] = date('d M, Y h:iA', $val['created']);
                        $temp['invoice_download_url'] = $val['hosted_invoice_url'] ?? null;
                        $response[] = $temp;
                    }
                    return successMsg('Transaction list', $response);
                } else return errorMsg('Transaction not found!');
            } else return errorMsg('Transaction not found!');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to buy a free plan
    public function buyFreePlan(Request $request)
    {
        try {
            return errorMsg('This api is no longer exists!');
            // $validator = Validator::make($request->all(), [
            //     'plan_id' => 'required',
            //     'price' => 'required',
            // ]);
            // if ($validator->fails()) {
            //     return errorMsg($validator->errors()->first());
            // } else {
            //     $stripe = new \Stripe\StripeClient(env("STRIPE_SECRET"));
            //     Stripe::setApiKey(env("STRIPE_SECRET"));
            //     if($request->price != 0) return errorMsg('Please select free plan');
            //     $user = User::where('id', auth()->user()->id)->first();
            //     $userPlanExist = UserPlan::where("user_id", $user->id)->where("status", 1)->count();
            //     if ($userPlanExist) {
            //         $user->subscription_id = null;
            //         $user->plan_id = $request->plan_id;
            //         $user->save();

            //         UserPlan::where("user_id", $user->id)->where("status", 1)->update(['status' => 2]);
            //         $userPlanExists = UserPlan::where("user_id", $user->id)->where("status", 1)->get();
            //         foreach($userPlanExists as $plans){
            //             if($plans->type == 1)
            //                 $stripe->subscriptions->cancel($plans->subscription_id, []);
            //         }
                    
            //         $userPlan = new UserPlan;
            //         $userPlan->user_id = auth()->user()->id;
            //         $userPlan->plan_id = $request->plan_id;
            //         $userPlan->price = $request->price;
            //         $userPlan->subscription_id = null;
            //         $userPlan->status = 1;
            //         $userPlan->activated_date = date('Y-m-d H:i:s');
            //         $userPlan->save();
            //     }
            //     return successMsg("Plan changed successfully");
            // }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    public function iosBuyPlan(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'plan_id' => 'required',
                'plan_timeperiod' => 'required',
                'price_id' => 'required',
                'price' => 'required',
                'transaction_id' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $stripe = new \Stripe\StripeClient(env("STRIPE_SECRET"));
                Stripe\Stripe::setApiKey(env("STRIPE_SECRET"));
                $user = User::where('id', auth()->user()->id)->first();
                $plan = Plan::where('id', $request->plan_id)->where('status', 1)->first();
                if (isset($plan->id)) {
                    $user->subscription_id = null;
                    $user->plan_id = $request->plan_id;
                    $user->save();
                    $userPlanExist = UserPlan::where("user_id", $user->id)->where("status", 1)->count();
                    if ($userPlanExist) {
                        UserPlan::where("user_id", $user->id)->where("status", 1)->update(['status' => 2]);
                        $userPlanExists = UserPlan::where("user_id", $user->id)->where("status", 1)->get();
                        foreach($userPlanExists as $plans){
                            if($plans->type == 1)
                                $stripe->subscriptions->cancel($plans->subscription_id, []);
                        }
                    }
                    $userPlan = new UserPlan;
                    $userPlan->user_id = auth()->user()->id;
                    $userPlan->type = 2;
                    $userPlan->plan_id = $plan->id;
                    $userPlan->plan_timeperiod = $request->plan_timeperiod;
                    $userPlan->price_id = $request->price_id;
                    $userPlan->price = $request->price;
                    $userPlan->transaction_id = $request->transaction_id;
                    $userPlan->status = 1;
                    $userPlan->activated_date = date('Y-m-d H:i:s');
                    $userPlan->save();
                    return response()->json([
                        'status' => true,
                        'message' => 'Plan changed successfully',
                    ]);
                } else return errorMsg('Invalid plan');
            }
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
                'card_id' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $stripe = new \Stripe\StripeClient(env("STRIPE_SECRET"));
                Stripe\Stripe::setApiKey(env("STRIPE_SECRET"));
                $user = User::where('id', auth()->user()->id)->first();
                $plan = Plan::where('id', $request->plan_id)->where('status', 1)->first();
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
                        if($plan->monthly_price == $plan->anually_price){
                            $paymentIntent = $stripe->paymentIntents->create([
                                'customer' => $customer->id,
                                'amount' => $request->price*100,
                                'currency' => 'usd',
                                'payment_method_types' => ['card'],
                                'payment_method' => $request->card_id,
                            ]);
                            $subscription = $stripe->paymentIntents->confirm(
                                $paymentIntent->id,
                            );
                            $isRecurr = false;
                        } else {
                            $subscription = $stripe->subscriptions->create([
                                'customer' => $customer->id,
                                'items' => [
                                    ['price' => $request->price_id],
                                ],
                                [
                                    'default_payment_method' => $request->card_id,
                                ]
                            ]);
                            $isRecurr = true;
                        }
                    } else return errorMsg('Customer could not be created in stripe');
                    if (isset($subscription->id)) {
                        if($isRecurr)
                            $user->subscription_id = $subscription->id;
                        $user->customer_id = $customer->id;
                        $user->plan_id = $request->plan_id;
                        $user->save();
                        $msg = "Plan subscribed successfully";
                        $userPlanExist = UserPlan::where("user_id", $user->id)->where("status", 1)->count();
                        if ($userPlanExist) {
                            $msg = "Plan changed successfully";
                            UserPlan::where("user_id", $user->id)->where("status", 1)->update(['status' => 2]);
                            $userPlanExists = UserPlan::where("user_id", $user->id)->where("status", 1)->get();
                            foreach($userPlanExists as $plans){
                                if($plans->type == 1)
                                    $stripe->subscriptions->cancel($plans->subscription_id, []);
                            }
                        }

                        $userPlan = new UserPlan;
                        $userPlan->user_id = auth()->user()->id;
                        $userPlan->type = 1;
                        $userPlan->plan_id = $plan->id;
                        $userPlan->plan_timeperiod = ($plan->monthly_price == $plan->anually_price) ? 3 : $request->plan_timeperiod;
                        $userPlan->price_id = $request->price_id;
                        $userPlan->price = $request->price;
                        if($isRecurr)
                            $userPlan->subscription_id = $subscription->id;
                        else
                            $userPlan->transaction_id = $subscription->latest_charge ?? $subscription->id;
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
