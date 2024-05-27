<?php

namespace App\Http\Controllers;

use App\Mail\DefaultMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Dev name : Dishant Gupta
    // This function is used to show admin login page
    public function login()
    {
        try {
            return view('auth.login');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to show admin forgot password page
    public function forgotPassword()
    {
        try {
            return view('auth.forgot-password');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to send the otp to entered email address if its register
    public function sendOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $user = User::where('email', $request->email)->where('status', 1)->where('role', 2)->first();
                if (isset($user->id)) {
                    $code = rand(1000, 9999);
                    $data['subject'] = 'Reset Your Journey with journals password';
                    $data['site_title'] = 'Reset Your Journey with journals password';
                    $data['view'] = 'pages.user.email.send-otp';
                    $data['to_email'] = $request->email;
                    $data['otp'] = $code;
                    $data['customer_name'] = $user->name;
                    sendEmail($data);
                    User::where('email', $request->email)->where('status', 1)->where('role', 2)->update([
                        'otp' => $code
                    ]);
                    $user_email = encrypt_decrypt('encrypt',$request->email);
                    return redirect()->route('admin.otp.verification', $user_email);
                } else {
                    return redirect()->back()->with('error', 'This email is not registered with us')->withInput();
                }
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to show admin otp verification page
    public function otpVerification($email)
    {
        try {
            return view('auth.otp-verification')->with(compact('email'));
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to send the otp to entered email address if its register
    public function sendVerify(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required',
                'otp1' => 'required',
                'otp2' => 'required',
                'otp3' => 'required',
                'otp4' => 'required',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $email = encrypt_decrypt('decrypt', $request->email);
                $otp = $request['otp1'].''.$request['otp2'].$request['otp3'].$request['otp4'];
                $user = User::where('email', $email)->where('status', 1)->where('role', 2)->first();
                if (isset($user->id)) {
                    $verify = User::where('id', $user->id)->where('otp', $otp)->first();
                    if(isset($verify->id)){
                        User::where('id', $user->id)->update([
                            'otp' => null
                        ]);
                        return redirect()->route('admin.reset.password', $request->email);
                    } else {
                        return redirect()->back()->with('error', 'Invalid OTP');
                    }
                } else {
                    return redirect()->back()->with('error', 'This email is not registered with us');
                }
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to show admin otp verification page
    public function resetPassword($email)
    {
        try {
            return view('auth.change-password')->with(compact('email'));
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

        // Dev name : Dishant Gupta
    // This function is used to send the otp to entered email address if its register
    public function changePassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required',
                'password' => 'required',
                'cnf_password' => 'required',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $email = encrypt_decrypt('decrypt', $request->email);
                $user = User::where('email', $email)->where('status', 1)->where('role', 2)->first();
                if (isset($user->id)) {
                    User::where('email', $email)->where('status', 1)->where('role', 2)->update([
                        'password' => Hash::make($request->password)
                    ]);
                    return redirect()->route('admin.login')->with('success', 'Password reset successfully');
                } else {
                    return redirect()->back()->with('error', 'This email is not registered with us');
                }
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to check authentication credentials
    public function checkUser(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $user = User::where("email", $request->email)->first();
                if (isset($user->id)) {
                    if (auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
                        return response()->json(['status' => true, 'message' => 'Successfully loggedin.', 'route' => route("admin.dashboard")]);
                    } else {
                        return errorMsg('Invalid Email or Password');
                    }
                } else {
                    return errorMsg('This email is not registered with us');
                }
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to show profile page
    public function profile()
    {
        try {
            return view('pages.admin.profile');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to update admin profile details
    public function updateData(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'mobile' => 'required',
                'address' => 'required',
                'zipcode' => 'required',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $user = User::where('id', auth()->user()->id)->first();

                if ($request->hasFile("file")) {
                    $name = fileUpload($request->file, "/uploads/profile/");
                    if(isset($user->profile)){
                        // fileRemove("/uploads/profile/" . $user->profile);
                    }
                    $user->profile = $name;
                }

                $user->name = ucwords($request->name);
                $user->mobile = $request->mobile;
                $user->address = $request->address;
                $user->zipcode = $request->zipcode;
                $user->save();
                return successMsg('Profile updated successfully');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to check current password is matched with current password (input field) or not
    public function checkPassword(Request $request)
    {
        try{
            $user = User::where('id', auth()->user()->id)->first();
            if(!(Hash::check($request->current_password, $user->password))){
                echo json_encode("Old Password doesn't match with Current Password.");
            } else echo json_encode(true);
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to update admin login password
    public function updatePassword(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'password' => 'required',
                'current_password' => 'required',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $user = User::where('id', auth()->user()->id)->first();
                if (Hash::check($request->current_password, $user->password)) {
                    $user->password = Hash::make($request->password);
                    $user->save();
                    return successMsg("Password updated successfully");
                } else {
                    return errorMsg('Please enter correct current password.');
                }
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to admin logout
    public function logout(Request $request)
    {
        try{
            Auth::logout();
            return redirect('/admin');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
