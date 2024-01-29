<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login()
    {
        try {
            return view('auth.login');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

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
                        return response()->json(['status' => true, 'message' => 'Logged In Successfully.', 'route' => route("admin.dashboard")]);
                    } else {
                        return errorMsg('Invalid Email or Password');
                    }
                } else {
                    return errorMsg('Invalid Email or Password');
                }
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    public function profile()
    {
        try {
            return view('pages.admin.profile');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    public function updateData(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'name' => 'required',
                'mobile' => 'required',
                'address' => 'required',
                'zipcode' => 'required',
                'file' => 'mimes:jpeg,png,jpg|image'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $user = User::where('id', auth()->user()->id)->first();

                if ($request->hasFile("file")) {
                    $file = $request->file('file');
                    $name = "JWJ_" .  time() . "." . $file->getClientOriginalExtension();
                    if(isset($user->profile)){
                       $link = public_path() . "/uploads/profile/" . $user->profile;
                        if(File::exists($link)) {
                            unlink($link);
                        } 
                    }
                    $user->profile = $name;
                    $file->move("public/uploads/profile", $name);
                }

                $user->name = ucwords($request->name);
                $user->email = $request->email;
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
                    return successMsg("Password changed Successfully.");
                } else {
                    return errorMsg('Please enter correct current password.');
                }
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

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
