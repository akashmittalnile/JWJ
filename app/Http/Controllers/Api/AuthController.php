<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MoodMaster;
use App\Models\User;
use App\Models\UserMood;
use App\Models\UserPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use function PHPUnit\Framework\returnSelf;

class AuthController extends Controller
{
    // Dev name : Dishant Gupta
    // This function is used to send otp to registered email address for email verification
    public function sendOtp(Request $request) {
        try{
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                if(!emailExist($request->email)){
                    $user = User::where('email', $request->email)->where('status', -1)->first();
                    $code = rand(1000,9999);
                    if(isset($user->id)){
                        $user->otp = $code;
                        $user->created_at = date('Y-m-d H:i:s');
                        $user->updated_at = date('Y-m-d H:i:s');
                    } else {
                        $user = new User;
                        $user->email = strtolower($request->email);
                        $user->otp = $code;
                        $user->role = 1;
                        $user->status = -1;
                    }
                    $data['subject'] = 'Verify Your Email on Journey with journals';
                    $data['site_title'] = 'Verify Your Email on Journey with journals';
                    $data['view'] = 'pages.user.email.email-verify';
                    $data['to_email'] = $request->email;
                    $data['otp'] = $code;
                    sendEmail($data);
                    $user->save();
                    return successMsg('OTP sended to your email address.', ['otp' => $code]);
                } else return errorMsg('This email is already exist!');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to verify OTP for email verification
    public function verifyOtp(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'otp' => 'required',
                'email' => 'required|email',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $user = User::where('email', $request->email)->where('otp', $request->otp)->where('status', -1)->first();
                if(isset($user->id)) {
                    if (date("Y-m-d H:i:s", strtotime("+600 sec", strtotime($user->updated_at))) >= date('Y-m-d H:i:s')){
                        User::where('id', $user->id)->update([
                            'email_verified_at' => 1,
                            'otp' => null,
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
                        return successMsg('OTP verified successfully.', ['id' => $user->id, 'email' => $user->email, 'email_verified' => true]);
                    } else return errorMsg('Timeout. Please try again...');
                } else return errorMsg('Wrong OTP Entered!');
            }   
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to register user
    public function register(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'user_name' => 'required',
                'name' => 'required',
                'password' => 'required',
                'email' => 'required|email',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                if(!emailExist($request->email)){
                    $user = User::where('id', $request->id)->where('email', $request->email)->where('email_verified_at', 1)->first();
                    if(isset($user->id)) {
                        // if ($request->hasFile("file")) {
                        //     $file = $request->file('file');
                        //     $name = "JWJ_" .  time() . rand() . "." . $file->getClientOriginalExtension();
                        //     $user->profile = $name;
                        //     $file->move("public/uploads/profile", $name);
                        // }
                        $user->name = ucwords($request->name);
                        $user->user_name = strtolower($request->user_name);
                        $user->country_code = $request->country_code ?? null;
                        $user->country_flag = $request->country_flag ?? null;
                        $user->mobile = $request->mobile ?? null;
                        $user->password = Hash::make($request->password);
                        $user->role = 1;
                        $user->status = 1;
                        $user->plan_id = planData(true);
                        $user->updated_at = date('Y-m-d H:i:s');
                        if(isset($request->fcm_token)){
                            $user->fcm_token = $request->fcm_token;
                        }
                        $user->save();
                        $data['subject'] = 'Welcome to Journey with journals';
                        $data['site_title'] = 'Welcome to Journey with journals';
                        $data['view'] = 'pages.user.email.registration-successful';
                        $data['to_email'] = $request->email;
                        $data['customer_name'] = $user->name;
                        sendEmail($data);
                        $token = $user->createToken("journey_with_journals")->plainTextToken;
                        return successMsg('Registered successfully.', ['access_token' => $token]);
                    } else return errorMsg('Email is not verified!');
                } else return errorMsg('This email is already exist!');
            }   
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to login in app
    public function login(Request $request) {
        try{
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $user = User::where('email', $request->email)->first();
                if(isset($user->id)){
                    if($user->status == 1){
                        if (Hash::check($request->password, $user->password)) {
                            $token = $user->createToken("journey_with_journals")->plainTextToken;
                            if(isset($request->fcm_token)){
                                User::where('email', $request->email)->where('id', $user->id)->update([
                                    'fcm_token' => $request->fcm_token
                                ]);
                            }
                            $response = array('user' => [
                                'id' => $user->id,
                                'name' => $user->name,
                                'user_name' => $user->user_name,
                                'email' => $user->email,
                                'country_code' => $user->country_code,
                                'country_flag' => $user->country_flag ?? null,
                                'mobile' => $user->mobile,
                                'profile_image' => isset($user->profile) ? assets('uploads/profile/'.$user->profile) : null,
                                'role' => $user->role,
                                'status' => $user->status,
                                'fcm_token' => $user->fcm_token,
                                'created_at' => date('d M, Y h:i A', strtotime($user->created_at)),
                            ], 'access_token' => $token);
                            return successMsg('Logged In Successfully.', $response);
                        } else  return errorMsg('Invalid Email or Password!');  
                    } else return errorMsg('Your account was temporarily inactive by administrator!');
                } else return errorMsg('This account is not registered with us!');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to sending the otp to email address for reset password
    public function forgotPassword(Request $request) {
        try{
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $user = User::where('email', $request->email)->first();
                if(isset($user->id)){
                    $code = rand(1000,9999);
                    $user->otp = $code;
                    $user->updated_at = date('Y-m-d H:i:s');
                    $data['subject'] = 'Reset Your Journey with journals password';
                    $data['site_title'] = 'Reset Your Journey with journals password';
                    $data['view'] = 'pages.user.email.send-otp';
                    $data['to_email'] = $request->email;
                    $data['otp'] = $code;
                    $data['customer_name'] = $user->name;
                    sendEmail($data);
                    $user->save();
                    return successMsg('OTP sent to your email address', ['otp' => $code]);
                } else return errorMsg('Email is not registered with us');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to verify the otp is correct or not for reset password
    public function otpVerification(Request $request) {
        try{
            $validator = Validator::make($request->all(), [
                'otp' => 'required',
                'email' => 'required|email',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $user = User::where('email', $request->email)->where('otp', $request->otp)->first();
                if(isset($user->id)) {
                    if (date("Y-m-d H:i:s", strtotime("+600 sec", strtotime($user->updated_at))) >= date('Y-m-d H:i:s')){
                        return successMsg('OTP verified successfully.');
                    } else return errorMsg('Timeout. Please try again...');
                } else return errorMsg('Wrong OTP Entered!');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to update the password
    public function resetPassword(Request $request) {
        try{
            $validator = Validator::make($request->all(), [
                'otp' => 'required',
                'email' => 'required|email',
                'password' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $user = User::where('email', $request->email)->where('otp', $request->otp)->first();
                if(isset($user->id)) {
                    $user->password = Hash::make($request->password);
                    $user->save();
                    return successMsg('Password has been changed successfully');
                } else return errorMsg('Wrong OTP Entered!');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to getting the particular user all details
    public function profile(Request $request) {
        try{
            $user = Auth::user();
            $totalMood = UserMood::where('user_id', auth()->user()->id)->whereMonth('created_at', date('m', strtotime($request->date)))->whereYear('created_at', date('Y', strtotime($request->date)))->get();
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

            $plan = UserPlan::join('plan as p', 'p.id', '=', 'user_plans.plan_id')->where('user_plans.status', 1)->where('user_plans.user_id', auth()->user()->id)->select('p.name', 'user_plans.plan_timeperiod', 'user_plans.activated_date', 'user_plans.price')->first();
            $current_plan = [
                'name' => $plan->name ?? 'NA',
                'price' => $plan->price ?? '0',
                'activated_date' => isset($plan->activated_date) ? date('d M, Y h:iA', strtotime($plan->activated_date)) : null,
                'renew_date' => isset($plan->activated_date) ? date('d M, Y h:iA', strtotime("+1 Month".$plan->activated_date)) : null,
                'plan_timeperiod' => isset($plan->plan_timeperiod) ? ($plan->plan_timeperiod == 1 ? 'Monthly' : 'Yearly') : null,
            ];

            $response = [
                'id' => $user->id,
                'name' => $user->name,
                'user_name' => $user->user_name,
                'email' => $user->email,
                'country_code' => $user->country_code ?? null,
                'country_flag' => $user->country_flag ?? null,
                'mobile' => $user->mobile ?? null,
                'role' => $user->role,
                'status' => $user->status,
                'fcm_token' => $user->fcm_token,
                'profile_image' => isset($user->profile) ? assets('uploads/profile/'.$user->profile) : null,
                'created_at' => date('d M, Y h:i A', strtotime($user->created_at)),
                'average_mood_data' => $avgMood,
                'current_plan' => $current_plan
            ];
            return successMsg('Profile data.', $response);
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to update the profile data of user
    public function updateProfile(Request $request) {
        try{
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'user_name' => 'required',
                'mobile' => 'required',
                'country_code' => 'required',
                'file' => 'mimes:jpeg,png,jpg|image',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $user = User::where('id', auth()->user()->id)->first();
                if(isset($user->id)) {
                    if ($request->hasFile("file")) {
                        if(isset($user->profile)){
                            fileRemove("/uploads/profile/" . $user->profile);
                        }
                        $name = fileUpload($request->file, "/uploads/profile/");
                        $user->profile = $name;
                    }
                    $user->name = ucwords($request->name);
                    $user->user_name = strtolower($request->user_name);
                    $user->country_code = $request->country_code;
                    $user->country_flag = $request->country_flag;
                    $user->mobile = $request->mobile;
                    $user->updated_at = date('Y-m-d H:i:s');
                    $user->save();
                    return successMsg('Profile updated successfully');
                } else return errorMsg('Invalid user!');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to change the password
    public function changePassword(Request $request) {
        try{
            $validator = Validator::make($request->all(), [
                'old_password' => 'required',
                'new_password' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $user = User::where('id', auth()->user()->id)->first();
                if (isset($user->id)) {
                    if (Hash::check($request->old_password, $user->password)) {
                        if (!Hash::check($request->new_password, $user->password)) {
                            $user->password = Hash::make($request->new_password);
                            if ($user->save()) {
                                return successMsg('Password has been changed successfully');
                            }
                        } else return errorMsg('New password cannot same as old password.');
                    } else  return errorMsg('Old password is incorrect.');
                } else return errorMsg('Invalid user!');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to user logging out
    public function testPushNotification(Request $request) {
        try{
            $data = array(
                'msg' => 'Journey With Journals',
                'title' => 'Testing'
            );
            sendNotification($request->token, $data);
            return successMsg('Test Notification.');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to user logging out
    public function logout() {
        try{
            User::where('id', auth()->user()->id)->update([
                'fcm_token' => null
            ]);
            Auth::user()->tokens()->delete();
            return successMsg('Logout successfully.');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to show error message when bearer token expired
    public function tokenExpire() {
        try{
            return errorMsg('Token expired! Please login....');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
