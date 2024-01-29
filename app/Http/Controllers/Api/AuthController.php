<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use function PHPUnit\Framework\returnSelf;

class AuthController extends Controller
{
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
                    $data['site_title'] = 'Email Verification OTP';
                    $data['subject'] = 'Email Verification OTP';
                    $data['view'] = 'pages.user.email.send-otp';
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
                    if (date("Y-m-d H:i:s", strtotime("+600 sec", strtotime($user->created_at))) >= date('Y-m-d H:i:s')){
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

    public function register(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'name' => 'required',
                'mobile' => 'required',
                'country_code' => 'required',
                'password' => 'required',
                'file' => 'required|mimes:jpeg,png,jpg|image',
                'email' => 'required|email',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                if(!emailExist($request->email)){
                    $user = User::where('id', $request->id)->where('email', $request->email)->where('email_verified_at', 1)->first();
                    if(isset($user->id)) {
                        if ($request->hasFile("file")) {
                            $file = $request->file('file');
                            $name = "JWJ_" .  time() . "." . $file->getClientOriginalExtension();
                            $user->profile = $name;
                            $file->move("public/uploads/profile", $name);
                        }
                        $user->name = ucwords($request->name);
                        $user->country_code = $request->country_code;
                        $user->mobile = $request->mobile;
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
                    } else return errorMsg('Your account was temporily inactive by administrator!');
                } else return errorMsg('Invalid Email or Password!');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
