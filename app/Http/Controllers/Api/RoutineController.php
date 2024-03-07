<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Routine;
use App\Models\RoutineCategory;
use App\Models\Schedule;
use App\Models\ScheduleInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoutineController extends Controller
{
    public function routineCategory(Request $request){
        try{
            $category = RoutineCategory::orderByDesc('id')->get();
            $response = array();
            foreach($category as $val){
                $temp['id'] = $val->id;
                $temp['name'] = $val->name;
                $temp['code'] = $val->code;
                $temp['status'] = $val->status;
                $temp['logo'] = assets('assets/images/'.$val->logo);
                $temp['created_at'] = date('d M, Y h:i A', strtotime($val->created_at));
                $response[] = $temp;
            }
            return successMsg('Routines category', $response);
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    public function routine(Request $request){
        try{
            $routines = Routine::where('created_by', auth()->user()->id)->orderby('id','desc')->get();
            $response = array();
            foreach($routines as $key => $myroutine) {   
                $category = RoutineCategory::where('id','=',$myroutine->category_id)->first();
                $temp['routineid'] = $myroutine->id;
                $temp['routinename'] = $myroutine->name;
                $temp['routinesubtitle'] = $myroutine->subtitle;
                $temp['description'] = $myroutine->description;
                $temp['created_by'] = $myroutine->created_by;
                $temp['routinetype'] = ($myroutine->privacy == 'P') ? 'Public Routine' : 'Private Routine';
                $temp['date'] = $myroutine->created_date;
                $temp['category_name'] = $category->name;
                $temp['category_logo'] = assets('assets/images/'.$category->logo);
                $temp['createdBy'] = ($myroutine->created_by == auth()->user()->id) ? 'mySelf' : 'shared';
                $response[] = $temp;
            }
            return successMsg('My routines', $response);
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
    
    public function createRoutine(Request $request)
    {
        try {
            if ($request->frequency == 'T'){
                $validRequest = ['name' => 'required', 'description' => 'required', 'category_id' => 'required', 'frequency' => 'required', 'schedule_time' => 'required|array', 'date' => 'required', 'schedule_startdate' => 'required', 'schedule_enddate' => 'required'];
            } elseif ($request->frequency == 'C') {
                $validRequest = ['name' => 'required', 'description' => 'required', 'category_id' => 'required', 'frequency' => 'required', 'schedule_time' => 'required|array', 'custom' => 'required|array'];
            } else {
                $validRequest = ['name' => 'required', 'description' => 'required', 'category_id' => 'required', 'frequency' => 'required', 'schedule_time' => 'required|array'];
            }
            $validator = Validator::make($request->all(), $validRequest);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $routine = new Routine;
                $routine->name = $request->name;
                $routine->subtitle = $request->subtitle ?? null;
                $routine->description = $request->description;
                $routine->priority = $request->priority ?? 'L';
                $routine->category_id = $request->category_id;
                $routine->created_by = auth()->user()->id;
                $routine->status = 1;
                $routine->save();

                $schedule = new Schedule;
                $schedule->routines_id = $routine->id;
                $schedule->frequency = $request->frequency;
                $schedule->is_enable = 1;
                $schedule->save();

                if ($request->frequency == 'O') {
                    foreach ($request->schedule_time as $key5 => $scheduletime) {
                        $interval = new ScheduleInterval;
                        $interval->interval_time = Carbon::parse($scheduletime)->format('H:i');
                        $interval->schedule_id = $schedule->id;
                        $interval->save();
                    }
                } elseif ($request->frequency == 'D') {
                    foreach ($request->schedule_time as $key5 => $scheduletime) {
                        $interval = new ScheduleInterval;
                        $interval->interval_time = Carbon::parse($scheduletime)->format('H:i');
                        $interval->schedule_id = $schedule->id;
                        $interval->save();
                    }
                } elseif ($request->frequency == 'T') {
                    $schedule = Schedule::find($schedule->id);
                    $schedule->schedule_time = $request->date;
                    $schedule->update();
                    foreach ($request->schedule_time as $key5 => $scheduletime) {
                        $interval = new ScheduleInterval;
                        $interval->interval_time = Carbon::parse($scheduletime)->format('H:i');
                        $interval->schedule_id = $schedule->id;
                        $interval->save();
                    }
                } elseif ($request->frequency == 'C') {
                    $schedule = Schedule::find($schedule->id);
                    $schedule->schedule_startdate = $request->schedule_startdate;
                    $schedule->schedule_enddate = $request->schedule_enddate;
                    $schedule->update();
                    foreach ($request->custom as $key3 => $customs) {
                        foreach ($request->schedule_time as $key5 => $scheduletime) {
                            $interval = new ScheduleInterval;
                            $interval->interval_time = Carbon::parse($scheduletime)->format('H:i');
                            $interval->interval_weak = $customs;
                            $interval->schedule_id = $schedule->id;
                            $interval->save();
                        }
                    }
                }
                return successMsg('Routine Created Successfully');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
