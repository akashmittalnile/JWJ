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
    // Dev name : Dishant Gupta
    // This function is getting the list of routine category like health, wealth, wellness etc
    public function routineCategory(Request $request)
    {
        try {
            $category = RoutineCategory::orderByDesc('id')->get();
            $response = array();
            foreach ($category as $val) {
                $categoryCount = Routine::where('category_id', $val->id)->where('created_by', auth()->user()->id)->count();
                $allRoutine = Routine::where('category_id', $val->id)->where('created_by', auth()->user()->id)->count();
                $temp['id'] = $val->id;
                $temp['name'] = $val->name;
                $temp['code'] = $val->code;
                $temp['status'] = $val->status;
                $temp['percentage'] = (($allRoutine < 1) || ($categoryCount < 1)) ? 0 : number_format((float)(($categoryCount/$allRoutine)*100), 2, '.', '');
                $temp['logo'] = assets('assets/images/' . $val->logo);
                $temp['created_at'] = date('d M, Y h:i A', strtotime($val->created_at));
                $response[] = $temp;
            }
            return successMsg('Routines category', $response);
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to getting the list of routines
    public function routine(Request $request)
    {
        try {
            $routines = Routine::where('created_by', auth()->user()->id)->orderby('id', 'desc')->get();
            $response = array();
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
                $response[] = $temp;
            }
            return successMsg('My routines', $response);
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to create a routine
    public function createRoutine(Request $request)
    {
        try {
            if ($request->frequency == 'T') {
                $validRequest = ['name' => 'required', 'description' => 'required', 'category_id' => 'required', 'frequency' => 'required', 'schedule_time' => 'required|array', 'date' => 'required'];
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
                    $schedule->schedule_startdate = $request->schedule_startdate ?? null;
                    $schedule->schedule_enddate = $request->schedule_enddate ?? null;
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

    // public function sendroutinenotification(Request $request)
    // {
    //     $task = Task::where('task_type', 'R')->get();
    //     $tasks = array();
    //     foreach ($task as $key => $alltask) {
    //         $tasks[$key]['id'] = $alltask->id;
    //         $tasks[$key]['createdby'] = $alltask->created_by;
    //         $schedule = Schedule::where('task_id', $alltask->id)->first();
    //         if ($schedule) {
    //             if ($schedule->frequency == 'D') {
    //                 $times = explode(',', $schedule->frequency_interval);
    //                 foreach ($times as $key => $scheduletime) {
    //                     if ($scheduletime != null) {
    //                         $time =  date('H:i:s', strtotime($scheduletime));
    //                         $currenttime =  date('H:i:s');
    //                         $start_datetime = new DateTime($time);
    //                         $diff = $start_datetime->diff(new DateTime($currenttime));
    //                         $total_minutes = ($diff->days * 24 * 60);
    //                         $total_minutes += ($diff->h * 60);
    //                         $total_minutes += $diff->i;
    //                         if ($total_minutes < 10) {
    //                             $user = User::find($alltask->created_by);
    //                             $fcmtoken = $user->token;
    //                             $fullname = explode(' ', $user->full_name);
    //                             $firstname = $fullname[0];
    //                             $message = "Hi " . $firstname . ", Complete your " . $alltask->name . " routine";
    //                             $load = array();
    //                             $load['title']  = env('APP_NAME');
    //                             $load['msg']    = $message;
    //                             $load['action'] = 'CONFIRMED';
    //                             $this->android_push($fcmtoken, $load);
    //                             $notification = new Notification();
    //                             $notification->user_id = $user->id;
    //                             $notification->message = $message;
    //                             $notification->created_at = date('Y-m-d H:i:s');
    //                             $notification->updated_at = date('Y-m-d H:i:s');
    //                             $notification->save();
    //                         }
    //                     }
    //                 }
    //             } elseif ($schedule->frequency == 'T') {
    //                 $gettime = $schedule->schedule_time;
    //                 $explodetime = explode(' ', $gettime);
    //                 $todaydate = date('Y-m-d');
    //                 if ($schedule->frequency_interval == $todaydate) {
    //                     $times = explode(',', $explodetime[1]);
    //                     foreach ($times as $key1 => $scheduletime) {
    //                         if ($scheduletime != null) {
    //                             $time =  date('H:i:s', strtotime($scheduletime));
    //                             $currenttime =  date('H:i:s');
    //                             $start_datetime = new DateTime($time);
    //                             $diff = $start_datetime->diff(new DateTime($currenttime));
    //                             $total_minutes = ($diff->days * 24 * 60);
    //                             $total_minutes += ($diff->h * 60);
    //                             $total_minutes += $diff->i;
    //                             if ($total_minutes < 10) {
    //                                 $user = User::find($alltask->created_by);
    //                                 $fcmtoken = $user->token;
    //                                 $fullname = explode(' ', $user->full_name);
    //                                 $firstname = $fullname[0];
    //                                 $message = "Hi " . $firstname . ", Complete your " . $alltask->name . " routine";
    //                                 $load = array();
    //                                 $load['title']  = env('APP_NAME');
    //                                 $load['msg']    = $message;
    //                                 $load['action'] = 'CONFIRMED';
    //                                 $this->android_push($fcmtoken, $load);
    //                                 $notification = new Notification();
    //                                 $notification->user_id = $user->id;
    //                                 $notification->message = $message;
    //                                 $notification->created_at = date('Y-m-d H:i:s');
    //                                 $notification->updated_at = date('Y-m-d H:i:s');
    //                                 $notification->save();
    //                             }
    //                         }
    //                     }
    //                 }
    //             } elseif ($schedule->frequency == 'C') {
    //                 $todaydate = date('Y-m-d');
    //                 // $todaydate = '2022-12-04';
    //                 $todayday = Carbon::createFromFormat('Y-m-d', $todaydate)->format('l');
    //                 $customdays = explode(',', $schedule->frequency_interval);
    //                 $arraycustomdays = array();
    //                 for ($x = 0; $x < (count($customdays) - 1); $x++) {
    //                     if ($customdays[$x] == 'M') {
    //                         $arraycustomdays[$x] = 'Monday';
    //                     } elseif ($customdays[$x] == 'T') {
    //                         $arraycustomdays[$x] = 'Tuesday';
    //                     } elseif ($customdays[$x] == 'W') {
    //                         $arraycustomdays[$x] = 'Wednesday';
    //                     } elseif ($customdays[$x] == 'TH') {
    //                         $arraycustomdays[$x] = 'Thursday';
    //                     } elseif ($customdays[$x] == 'F') {
    //                         $arraycustomdays[$x] = 'Friday';
    //                     } elseif ($customdays[$x] == 'SA') {
    //                         $arraycustomdays[$x] = 'Saturday';
    //                     } elseif ($customdays[$x] == 'SU') {
    //                         $arraycustomdays[$x] = 'Sunday';
    //                     }
    //                 }
    //                 if (in_array($todayday, $arraycustomdays)) {
    //                     $times = explode(',', $schedule->schedule_time);
    //                     foreach ($times as $key3 => $scheduletime) {
    //                         if ($scheduletime != null) {
    //                             $time =  date('H:i:s', strtotime($scheduletime));
    //                             $currenttime =  date('H:i:s');
    //                             $start_datetime = new DateTime($time);
    //                             $diff = $start_datetime->diff(new DateTime($currenttime));
    //                             $total_minutes = ($diff->days * 24 * 60);
    //                             $total_minutes += ($diff->h * 60);
    //                             $total_minutes += $diff->i;
    //                             if ($total_minutes < 10) {
    //                                 $user = User::find($alltask->created_by);
    //                                 $fcmtoken = $user->token;
    //                                 $fullname = explode(' ', $user->full_name);
    //                                 $firstname = $fullname[0];
    //                                 $message = "Hi " . $firstname . ", Complete your " . $alltask->name . " routine";
    //                                 $load = array();
    //                                 $load['title']  = env('APP_NAME');
    //                                 $load['msg']    = $message;
    //                                 $load['action'] = 'CONFIRMED';
    //                                 $this->android_push($fcmtoken, $load);
    //                                 $notification = new Notification();
    //                                 $notification->user_id = $user->id;
    //                                 $notification->message = $message;
    //                                 $notification->created_at = date('Y-m-d H:i:s');
    //                                 $notification->updated_at = date('Y-m-d H:i:s');
    //                                 $notification->save();
    //                             }
    //                         }
    //                     }
    //                 }
    //             } elseif ($schedule->frequency == 'O') {
    //                 $getdate = date('Y-m-d', strtotime($alltask->created_date));
    //                 $todaydate = date('Y-m-d');
    //                 if ($getdate == $todaydate) {
    //                     $times = $schedule->frequency_interval;
    //                     foreach ($times as $key => $scheduletime) {
    //                         $time =  date('H:i:s', strtotime($scheduletime));
    //                         $currenttime =  date('H:i:s');
    //                         $start_datetime = new DateTime($time);
    //                         $diff = $start_datetime->diff(new DateTime($currenttime));
    //                         $total_minutes = ($diff->days * 24 * 60);
    //                         $total_minutes += ($diff->h * 60);
    //                         $total_minutes += $diff->i;
    //                         if ($total_minutes < 10) {
    //                             $user = User::find($alltask->created_by);
    //                             $fcmtoken = $user->token;
    //                             $fullname = explode(' ', $user->full_name);
    //                             $firstname = $fullname[0];
    //                             $message = "Hi " . $firstname . ", Complete your " . $alltask->name . " routine";
    //                             $load = array();
    //                             $load['title']  = env('APP_NAME');
    //                             $load['msg']    = $message;
    //                             $load['action'] = 'CONFIRMED';
    //                             $this->android_push($fcmtoken, $load);
    //                             $notification = new Notification();
    //                             $notification->user_id = $user->id;
    //                             $notification->message = $message;
    //                             $notification->created_at = date('Y-m-d H:i:s');
    //                             $notification->updated_at = date('Y-m-d H:i:s');
    //                             $notification->save();
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     }
    //     return response(["status" => 1, "message" => "Notification send successfully"]);
    // }
}
