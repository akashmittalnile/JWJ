<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
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
                        $user->email = $request->email;
                        $user->otp = $code;
                        $user->role = 1;
                        $user->status = -1;
                    }
                    $data['subject'] = 'Email Verification OTP';
                    $data['to_mail'] = $request->email;
                    $data['body'] = $code;
                    sendMail($data);
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
                        $user->mobile = $request->mobile ?? null;
                        $user->password = Hash::make($request->password);
                        $user->role = 1;
                        $user->status = 1;
                        $user->updated_at = date('Y-m-d H:i:s');
                        $user->save();
                        return successMsg('Registered successfully.');
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
                            $response = array('user' => [
                                'id' => $user->id,
                                'name' => $user->name,
                                'user_name' => $user->user_name,
                                'email' => $user->email,
                                'country_code' => $user->country_code,
                                'mobile' => $user->mobile,
                                'profile_image' => isset($user->profile) ? assets('uploads/profile/'.$user->profile) : null,
                                'role' => $user->role,
                                'status' => $user->status,
                                'created_at' => date('d M, Y h:i A', strtotime($user->created_at)),
                            ], 'access_token' => $token);
                            return successMsg('Logged In Successfully.', $response);
                        } else  return errorMsg('Invalid Email or Password!');  
                    } else return errorMsg('Your account was temporarily inactive by administrator!');
                } else return errorMsg('Invalid Email or Password!');
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
                    $data['site_title'] = 'Forgot Password OTP';
                    $data['subject'] = 'Forgot Password OTP';
                    $data['view'] = 'pages.user.email.send-otp';
                    $data['to_email'] = $request->email;
                    $data['otp'] = $code;
                    sendEmail($data);
                    $user->save();
                    return successMsg('OTP sended to your email address.', ['otp' => $code]);
                } else return errorMsg('Invalid Email or Password!');
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
                    return successMsg('Password reset successfully.');
                } else return errorMsg('Wrong OTP Entered!');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to getting the particular user all details
    public function profile() {
        try{
            $user = Auth::user();
            $response = [
                'id' => $user->id,
                'name' => $user->name,
                'user_name' => $user->user_name,
                'email' => $user->email,
                'country_code' => $user->country_code ?? null,
                'mobile' => $user->mobile ?? null,
                'role' => $user->role,
                'status' => $user->status,
                'profile_image' => isset($user->profile) ? assets('uploads/profile/'.$user->profile) : null,
                'created_at' => date('d M, Y h:i A', strtotime($user->created_at))
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
                'file' => 'required|mimes:jpeg,png,jpg|image',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $user = User::where('id', auth()->user()->id)->first();
                if(isset($user->id)) {
                    if ($request->hasFile("file")) {
                        $file = $request->file('file');
                        $name = "JWJ_" .  time() . rand() . "." . $file->getClientOriginalExtension();

                        $link = public_path() . "/uploads/profile/" . $user->profile;
                        if (file_exists($link)) {
                            unlink($link);
                        }

                        $user->profile = $name;
                        $file->move("public/uploads/profile", $name);
                    }
                    $user->name = ucwords($request->name);
                    $user->user_name = strtolower($request->user_name);
                    $user->country_code = $request->country_code;
                    $user->mobile = $request->mobile;
                    $user->updated_at = date('Y-m-d H:i:s');
                    $user->save();
                    return successMsg('Updated successfully.');
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
                                return successMsg('Password changes successfully.');
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
