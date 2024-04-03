<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\CommunityImage;
use App\Models\Journal;
use App\Models\JournalImage;
use App\Models\JournalSearchCriteria;
use App\Models\MoodMaster;
use Carbon\Carbon;
use App\Models\Plan;
use App\Models\Post;
use App\Models\Rating;
use App\Models\Routine;
use App\Models\RoutineCategory;
use App\Models\User;
use App\Models\UserFollowedCommunity;
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
            $now = Carbon::now();
            $mood = MoodMaster::where('status', 1)->get();
            $moods = array();
            foreach($mood as $val){
                $moodtemp['id'] = $val->id;
                $moodtemp['name'] = $val->name;
                $moodtemp['logo'] = isset($val->logo) ? assets('assets/images/'.$val->logo) : null;
                $moodtemp['status'] = $val->status;
                $moodtemp['created_at'] = date('d M, Y h:i A', strtotime($val->created_at));
                $moodtemp['updated_at'] = date('d M, Y h:i A', strtotime($val->updated_at));
                $moods[] = $moodtemp;
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
            $journal = Journal::where('journals.created_by', auth()->user()->id)->whereDate('created_at', $now)->select('journals.*')->orderByDesc('journals.id')->limit(5)->distinct('journals.id')->get();
            $journals = array();
            foreach($journal as $val){
                $imgs = JournalImage::where('journal_id', $val->id)->get();
                $criteria = JournalSearchCriteria::join('search_criteria as sc', 'sc.id', '=', 'journals_search_criteria_mapping.search_id')->where('journal_id', $val->id)->select('sc.id', 'sc.name')->get();
                $mood = MoodMaster::where('id', $val->mood_id)->first();
                $path = array();
                foreach($imgs as $item){
                    $journaltemp1['id'] = $item->id;
                    $journaltemp1['img_path'] = isset($item->name) ? assets('uploads/journal/'.$item->name) : null;
                    $path[] = $journaltemp1;
                }
                $search = array();
                foreach($criteria as $item){
                    $journaltemp2['id'] = $item->id;
                    $journaltemp2['name'] = $item->name;
                    $search[] = $journaltemp2;
                }
                $journaltemp['id'] = $val->id;
                $journaltemp['title'] = $val->title;
                $journaltemp['content'] = $val->content;
                $journaltemp['status'] = $val->status;
                $journaltemp['mood_id'] = $val->mood_id;
                $journaltemp['mood_name'] = $mood->name;
                $journaltemp['mood_logo'] = isset($mood->logo) ? assets('assets/images/'.$mood->logo) : null;
                $journaltemp['images'] = $path;
                $journaltemp['search_criteria'] = $search;
                $journaltemp['created_at'] = date('d M, Y h:i A', strtotime($val->created_at));
                $journaltemp['updated_at'] = date('d M, Y h:i A', strtotime($val->updated_at));
                $journals[] = $journaltemp;
            }
            $data = Community::where('communities.status', 1)->limit(5)->orderByDesc('communities.id')->get();
            $community = [];
            foreach($data as $val){
                $ufc = UserFollowedCommunity::where('community_id', $val->id)->where('userid', auth()->user()->id)->first();
                $followCount = UserFollowedCommunity::where('community_id', $val->id)->count();
                $follow = UserFollowedCommunity::where('community_id', $val->id)->orderByDesc('id')->limit(3)->get();
                $memberImage = array();
                foreach($follow as $items){
                    $followedUser = User::where('id', $items->userid)->first();
                    array_push($memberImage, isset($followedUser->profile) ? assets("uploads/profile/$followedUser->profile") : assets('assets/images/no-image.jpg'));
                }
                $imgs = CommunityImage::where('community_id', $val->id)->get();
                $images = array();
                foreach($imgs as $item){
                    array_push($images, isset($item->name) ? assets("uploads/community/".$item->name) : null);
                }
                $planComm = Plan::where('id', $val->plan_id)->first();
                $postCount = Post::where('community_id', $val->id)->count();
                $communitytemp['id'] = $val->id;
                $communitytemp['name'] = $val->name;
                $communitytemp['description'] = $val->description;
                $communitytemp['status'] = $val->status;
                $communitytemp['image'] = $images;
                $communitytemp['follow'] = isset($ufc->id) ? true : false;
                $communitytemp['member_follow_count'] = $followCount ?? 0;
                $communitytemp['member_image'] = $memberImage;
                $communitytemp['post_count'] = $postCount ?? 0;
                $communitytemp['plan_name'] = $planComm->plan_name ?? null;
                $communitytemp['plan_monthly_price'] = $planComm->monthly_price ?? null;
                $communitytemp['plan_anually_price'] = $planComm->anually_price ?? null;
                $communitytemp['plan_price_currency'] = $planComm->currency ?? null;
                $community[] = $communitytemp;
            }
            $plan = Plan::where('monthly_price', '0')->first();
            $moodCalen = UserMood::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->where('user_id', auth()->user()->id)->get();
            $calender = array();
            $happyCount = $sadCount = $anxietyCount = $angerCount = 0;
            foreach($moodCalen as $val){
                $mood = MoodMaster::where('id', $val->mood_id)->first();
                if($mood->code=='happy') $happyCount ++;
                elseif($mood->code=='sad') $sadCount ++;
                elseif($mood->code=='anger') $angerCount ++;
                else $anxietyCount ++;
                $calendertemp['id'] = $val->id;
                $calendertemp['mood_id'] = $val->mood_id;
                $calendertemp['mood_name'] = $mood->name;
                $calendertemp['mood_image'] = isset($mood->logo) ? assets('assets/images/'.$mood->logo) : null;
                $calendertemp['date'] = date('d', strtotime($val->created_at));
                $calendertemp['month'] = date('m', strtotime($val->created_at));
                $calender[] = $calendertemp;
            }
            if(count($moodCalen) > 0)
                $avgMood = ['happy' => number_format((float)($happyCount*100)/count($moodCalen), 2, '.', ''), 'sad' => number_format((float)($sadCount*100)/count($moodCalen), 2, '.', ''), 'anger' => number_format((float)($angerCount*100)/count($moodCalen), 2, '.', ''), 'anxiety' => number_format((float)($anxietyCount*100)/count($moodCalen), 2, '.', '')];
            else $avgMood = ['happy' => 0, 'sad' => 0, 'anger' => 0, 'anxiety' => 0];

            $routines = Routine::where('created_by', auth()->user()->id)->whereDate('created_at', $now)->limit(5)->orderByDesc('id')->get();
            $routineArr = array();
            foreach ($routines as $key => $myroutine) {
                $interval = array();
                foreach ($myroutine->schedule->interval as $key => $val) {
                    $temp['id'] = $val->id;
                    $temp['interval_weak_name'] = isset($val->interval_weak) ? config('constant.days')[$val->interval_weak] : null;
                    $temp['interval_weak'] = isset($val->interval_weak) ? $val->interval_weak : null;
                    $temp['interval_time'] = $val->interval_time;
                    $interval[] = $temp;
                }
                $tempRoutine['routineid'] = $myroutine->id;
                $tempRoutine['routinename'] = $myroutine->name;
                $tempRoutine['routinesubtitle'] = $myroutine->subtitle;
                $tempRoutine['description'] = $myroutine->description;
                $tempRoutine['created_by'] = $myroutine->created_by;
                $tempRoutine['routinetype'] = ($myroutine->privacy == 'P') ? 'Public Routine' : 'Private Routine';
                $tempRoutine['date'] = $myroutine->created_at;
                $tempRoutine['category_name'] = $myroutine->category->name;
                $tempRoutine['category_logo'] = isset($myroutine->category->logo) ? assets('uploads/routine/' . $myroutine->category->logo) : assets("assets/images/no-image.jpg");
                $tempRoutine['createdBy'] = ($myroutine->created_by == auth()->user()->id) ? 'mySelf' : 'shared';
                $tempRoutine['created_at'] = date('d M, Y h:i A', strtotime($myroutine->created_at));
                $tempRoutine['schedule_frequency_name'] = config('constant.frequency')[$myroutine->schedule->frequency] ?? null;
                $tempRoutine['schedule_frequency'] = $myroutine->schedule->frequency ?? null;
                $tempRoutine['schedule_date'] = $myroutine->schedule->schedule_time ?? null;
                $tempRoutine['schedule_start_date'] = $myroutine->schedule->schedule_startdate ?? null;
                $tempRoutine['schedule_end_date'] = $myroutine->schedule->schedule_enddate ?? null;
                $tempRoutine['interval'] = $interval ?? null;
                $routineArr[] = $tempRoutine;
            }

            $response = array(['mood' => $moods, 'user' => $mydata, 'current_plan' => $plan->name, 'my_journal' => $journals, 'community' => $community, 'mood_calender' => $calender, 'average_mood' => $avgMood, 'my_routine' => $routineArr]);
            return successMsg('Home', $response);
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to getting the result acc. to search
    public function search(Request $request) {
        try{
            $journal = Journal::where('journals.created_by', auth()->user()->id);
            if($request->filled('search')) $journal->whereRaw("(`journals`.`title` LIKE '%" . $request->search . "%' or `journals`.`content` LIKE '%" . $request->search . "%')");
            else if($request->filled('date')) $journal->whereDate('journals.created_at', date('Y-m-d', strtotime($request->date)));
            $journal = $journal->where('journals.status', 1)->select('journals.*')->orderByDesc('journals.id')->distinct('journals.id')->get();
            $journals = array();
            foreach($journal as $val){
                $imgs = JournalImage::where('journal_id', $val->id)->get();
                $criteria = JournalSearchCriteria::join('search_criteria as sc', 'sc.id', '=', 'journals_search_criteria_mapping.search_id')->where('journal_id', $val->id)->select('sc.id', 'sc.name')->get();
                $mood = MoodMaster::where('id', $val->mood_id)->first();
                $path = array();
                foreach($imgs as $item){
                    $journaltemp1['id'] = $item->id;
                    $journaltemp1['img_path'] = isset($item->name) ? assets('uploads/journal/'.$item->name) : null;
                    $path[] = $journaltemp1;
                }
                $search = array();
                foreach($criteria as $item){
                    $journaltemp2['id'] = $item->id;
                    $journaltemp2['name'] = $item->name;
                    $search[] = $journaltemp2;
                }
                $journaltemp['id'] = $val->id;
                $journaltemp['title'] = $val->title;
                $journaltemp['content'] = $val->content;
                $journaltemp['status'] = $val->status;
                $journaltemp['mood_id'] = $val->mood_id;
                $journaltemp['mood_name'] = $mood->name;
                $journaltemp['mood_logo'] = isset($mood->logo) ? assets('assets/images/'.$mood->logo) : null;
                $journaltemp['images'] = $path;
                $journaltemp['search_criteria'] = $search;
                $journaltemp['created_at'] = date('d M, Y h:i A', strtotime($val->created_at));
                $journaltemp['updated_at'] = date('d M, Y h:i A', strtotime($val->updated_at));
                $journals[] = $journaltemp;
            }
            $data = Community::join('users as u', 'u.id', '=', 'communities.created_by')->join('community_images as ci', 'ci.community_id', '=', 'communities.id')->join('plan as p', 'p.id', '=', 'communities.plan_id')->select('communities.*', 'u.role', 'ci.name as image_name', 'p.name as plan_name', 'p.monthly_price', 'p.anually_price', 'p.currency');
            if($request->filled('search')) $data->whereRaw("(`communities`.`name` LIKE '%" . $request->search . "%' or `communities`.`description` LIKE '%" . $request->search . "%')");
            else if($request->filled('date')) $data->whereDate('communities.created_at', date('Y-m-d', strtotime($request->date)));
            $data = $data->where('communities.created_by', auth()->user()->id)->where('communities.status', 1)->orderByDesc('communities.id')->get();
            $community = [];
            foreach($data as $val){
                $ufc = UserFollowedCommunity::where('community_id', $val->id)->where('userid', auth()->user()->id)->first();
                $followCount = UserFollowedCommunity::where('community_id', $val->id)->count();
                $follow = UserFollowedCommunity::where('community_id', $val->id)->orderByDesc('id')->limit(3)->get();
                $memberImage = array();
                foreach($follow as $items){
                    $followedUser = User::where('id', $items->userid)->first();
                    array_push($memberImage, isset($followedUser->profile) ? assets("uploads/profile/$followedUser->profile") : assets('assets/images/no-image.jpg'));
                }
                $imgs = CommunityImage::where('community_id', $val->id)->get();
                $images = array();
                foreach($imgs as $item){
                    array_push($images, isset($item->name) ? assets("uploads/community/".$item->name) : null);
                }
                $postCount = Post::where('community_id', $val->id)->count();
                $communitytemp['id'] = $val->id;
                $communitytemp['name'] = $val->name;
                $communitytemp['description'] = $val->description;
                $communitytemp['status'] = $val->status;
                $communitytemp['image'] = $images;
                $communitytemp['follow'] = isset($ufc->id) ? true : false;
                $communitytemp['member_follow_count'] = $followCount ?? 0;
                $communitytemp['member_image'] = $memberImage;
                $communitytemp['post_count'] = $postCount ?? 0;
                $communitytemp['plan_name'] = $val->plan_name;
                $communitytemp['plan_monthly_price'] = $val->monthly_price;
                $communitytemp['plan_anually_price'] = $val->anually_price;
                $communitytemp['plan_price_currency'] = $val->currency;
                $community[] = $communitytemp;
            }
            $routines = Routine::where('created_by', auth()->user()->id);
            if($request->filled('search')) $routines->whereRaw("(`name` LIKE '%" . $request->search . "%' or `description` LIKE '%" . $request->search . "%' or `subtitle` LIKE '%" . $request->search . "%')");
            else if($request->filled('date')) $routines->whereDate('created_at', date('Y-m-d', strtotime($request->date)));
            $routines = $routines->where('created_by', auth()->user()->id)->orderby('id', 'desc')->get();
            $routine = array();
            foreach ($routines as $key => $myroutine) {
                $category = RoutineCategory::where('id', '=', $myroutine->category_id)->first();
                $temp['routineid'] = $myroutine->id;
                $temp['routinename'] = $myroutine->name;
                $temp['routinesubtitle'] = $myroutine->subtitle;
                $temp['description'] = $myroutine->description;
                $temp['created_by'] = $myroutine->created_by;
                $temp['routinetype'] = ($myroutine->privacy == 'P') ? 'Public Routine' : 'Private Routine';
                $temp['date'] = $myroutine->created_date;
                $temp['category_name'] = $category->name;
                $temp['category_logo'] = assets('assets/images/' . $category->logo);
                $temp['createdBy'] = ($myroutine->created_by == auth()->user()->id) ? 'mySelf' : 'shared';
                $temp['created_at'] = date('d M, Y h:i A', strtotime($myroutine->created_at));
                $routine[] = $temp;
            }
            $response = array(['journal' => $journals, 'community' => $community, 'routine' => $routine]);
            return successMsg('Search', $response);
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
                $now = Carbon::now();
                $isExist = UserMood::where('user_id', auth()->user()->id)->whereDate('created_at', $now)->first();
                if(isset($isExist->id)){
                    $isExist->mood_id = $request->mood_id;
                    $isExist->save();
                    return successMsg('Mood captured');
                }
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
    // This function is used to capture the mood of user in daily basis like :- happy, sad etc
    public function moodCalender(Request $request) {
        try{
            $validator = Validator::make($request->all(), [
                'month' => 'required',
                'year' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $days = getMonthDate($request->month, date('Y', strtotime($request->year)));
                
                $response = array();
                for($i=1; $i<=$days; $i++){
                    $date = $request->year.'-'.$request->month.'-'.$i;
                    $mood = UserMood::join('mood_master as mm', 'mm.id', '=', 'user_mood.mood_id')->whereDate('user_mood.created_at', date('Y-m-d', strtotime($date)))->whereMonth('user_mood.created_at', $request->month)->where('user_mood.user_id', auth()->user()->id)->select('mm.name as mname', 'mm.logo as mlogo')->first();
                    $temp['date'] = $i;
                    $temp['mood_name'] = $mood->mname ?? null;
                    $temp['mood_image'] = isset($mood->mlogo) ? assets('assets/images/'.$mood->mlogo) : null;
                    
                    $journal = Journal::whereDate('created_at', date('Y-m-d', strtotime($date)))->where('created_by', auth()->user()->id)->count();
                    $community = Community::whereDate('created_at', date('Y-m-d', strtotime($date)))->where('created_by', auth()->user()->id)->count();
                    $routine = Routine::whereDate('created_at', date('Y-m-d', strtotime($date)))->where('created_by', auth()->user()->id)->count();
                    if(($journal || $community || $routine)) $temp['data_available'] = true;
                    else $temp['data_available'] = false;
                    
                    $response[] = $temp;
                }
                return successMsg('Mood calender', $response);
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

    // Dev name : Dishant Gupta
    // This function is used to getting the list of users for sharing the routine
    public function userList(Request $request) {
        try{
            $user = User::where('id', '!=', auth()->user()->id)->where('role', 1);
            if($request->filled('name')) $user->whereRaw("(`user_name` LIKE '%" . $request->name . "%')");
            $user = $user->orderByDesc('id')->where('status', 1)->get();
            $users = array();
            foreach($user as $val){
                $temp['id'] = $val->id;
                $temp['user_name'] = $val->user_name;
                $temp['name'] = $val->name;
                $temp['profile'] = isset($val->profile) ? assets('/uploads/profile/'.$val->profile) : null;
                $users[] = $temp;
            }
            return successMsg('Users list', $users);
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
