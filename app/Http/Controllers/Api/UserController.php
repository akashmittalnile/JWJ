<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MoodMaster;
use App\Models\Plan;
use App\Models\Rating;
use App\Models\UserMood;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // Dev name : Dishant Gupta
    // Home api
    public function home(Request $request) {
        try{
            $mood = MoodMaster::where('status', 1)->get();
            $moods = array();
            foreach($mood as $val){
                $temp['id'] = $val->id;
                $temp['name'] = $val->name;
                $temp['logo'] = isset($val->logo) ? assets('assets/images/'.$val->logo) : null;
                $temp['status'] = $val->status;
                $temp['created_at'] = date('d M, Y h:i A', strtotime($val->created_at));
                $temp['updated_at'] = date('d M, Y h:i A', strtotime($val->updated_at));
                $moods[] = $temp;
            }
            $user = Auth::user();
            $mydata = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'country_code' => $user->country_code,
                'mobile' => $user->mobile,
                'role' => $user->role,
                'status' => $user->status,
                'profile_image' => isset($user->profile) ? assets('uploads/profile/'.$user->profile) : null,
                'created_at' => date('d M, Y h:i A', strtotime($user->created_at))
            ];
            $plan = Plan::where('monthly_price', '0')->first();
            $response = array(['mood' => $moods, 'user' => $mydata, 'current_plan' => $plan->name]);
            return successMsg('Home', $response);
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to capture the mood of user in daily basis like :- happy, sad etc
    public function moodCapture(Request $request) {
        try{
            $validator = Validator::make($request->all(), [
                'mood_id' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $mood = new UserMood;
                $mood->mood_id = $request->mood_id;
                $mood->user_id = auth()->user()->id;
                $mood->save();
                return successMsg('Mood captured');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to submit the rating
    public function submitRating(Request $request) {
        try{
            $validator = Validator::make($request->all(), [
                'rating' => 'required',
                'description' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $isExist = Rating::where('userid', auth()->user()->id)->first();
                if(isset($isExist->id)) return errorMsg('You already submitted the rating.');
                $rating = new Rating;
                $rating->userid = auth()->user()->id;
                $rating->rating = $request->rating;
                $rating->description = $request->description;
                $rating->status = 1;
                $rating->save();
                return successMsg('Rating submitted successfully.');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
