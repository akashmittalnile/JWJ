<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attachment;
use Carbon\Carbon;
use App\Models\Routine;
use App\Models\RoutineCategory;
use App\Models\RoutineSharingDetail;
use App\Models\Schedule;
use App\Models\ScheduleInterval;
use App\Models\SharingDetail;
use App\Models\TaskAssignMember;
use App\Models\UserHideTask;
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
                $temp['percentage'] = (($allRoutine < 1) || ($categoryCount < 1)) ? 0 : number_format((float)(($categoryCount / $allRoutine) * 100), 2, '.', '');
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
                $temp['routineid'] = $myroutine->id;
                $temp['routinename'] = $myroutine->name;
                $temp['routinesubtitle'] = $myroutine->subtitle;
                $temp['description'] = $myroutine->description;
                $temp['created_by'] = $myroutine->created_by;
                $temp['routinetype'] = ($myroutine->privacy == 'P') ? 'Public Routine' : 'Private Routine';
                $temp['date'] = $myroutine->created_at;
                $temp['category_name'] = $myroutine->category->name;
                $temp['category_logo'] = isset($myroutine->category->logo) ? assets('uploads/routine/' . $myroutine->category->logo) : assets("assets/images/no-image.jpg");
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
    // This function is used to delete a routine
    public function deleteRoutine(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required'
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $routine = Routine::where('id', $request->id)->where('type', 'R')->first();
                if(isset($routine->id)){
                    if($routine->created_by == auth()->user()->id){
                        $schedule = Schedule::where('routines_id', $routine->id)->first();
                        ScheduleInterval::where('schedule_id', $schedule->id)->delete();
                        Schedule::where('routines_id', $routine->id)->delete();
                        Routine::where('id', $request->id)->delete();
                        return successMsg('Routine deleted successfully');
                    } else return errorMsg('This routine is not created by you');
                } else return errorMsg('Routine not found');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to getting the details of particular routine
    public function routineDetail(Request $request, $id)
    {
        try {
            $routine = Routine::where('id', $id)->where('created_by', auth()->user()->id)->where('type', 'R')->first();
            if (isset($routine->id)) {
                $interval = array();
                foreach ($routine->schedule->interval as $key => $val) {
                    $temp['id'] = $val->id;
                    $temp['interval_weak_name'] = isset($val->interval_weak) ? config('constant.days')[$val->interval_weak] : null;
                    $temp['interval_weak'] = isset($val->interval_weak) ? $val->interval_weak : null;
                    $temp['interval_time'] = $val->interval_time;
                    $interval[] = $temp;
                }
                $share = array();
                if($routine->shared_by != null){
                    $share = array(
                        'shared_user_id' => $routine->sharedUser->id,
                        'shared_user_name' => $routine->sharedUser->name,
                        'shared_user_profile' => isset($routine->sharedUser->profile) ? assets('/uploads/profile/'.$routine->sharedUser->profile) : null,
                    );
                };
                $list = SharingDetail::where('user_id', auth()->user()->id)->where('routine_id', $routine->id)->get();
                $sharingList = array();
                foreach($list as $val){
                    $sha['id'] = $val->id;
                    $sha['share_to_user_id'] = $val->user->id ?? null;
                    $sha['share_to_user_name'] = $val->user->name ?? null;
                    $sha['share_to_user_profile'] = isset($val->user->profile) ? assets('/uploads/profile/'.$val->user->profile) : null;
                    $sha['shared_date'] = date('d M, Y h:i a', strtotime($val->created_at));
                    $sharingList[] = $sha;
                }
                $response = array(
                    'id' => $routine->id,
                    'name' => $routine->name,
                    'subtitle' => $routine->subtitle,
                    'description' => $routine->description,
                    'routinetype' => ($routine->privacy == 'P') ? 'Public Routine' : 'Private Routine',
                    'date' => date('d M, Y h:i A', strtotime($routine->created_at)),
                    'category_id' => $routine->category->id ?? null,
                    'category_name' => $routine->category->name ?? null,
                    'category_logo' => isset($routine->category->logo) ? assets('uploads/routine/' . $routine->category->logo) : assets("assets/images/no-image.jpg"),
                    'created_by' => ($routine->created_by == auth()->user()->id) ? 'mySelf' : 'shared',
                    'schedule_frequency_name' => config('constant.frequency')[$routine->schedule->frequency] ?? null,
                    'schedule_frequency' => $routine->schedule->frequency ?? null,
                    'schedule_date' => $routine->schedule->schedule_time ?? null,
                    'interval' => $interval ?? null,
                    $share,
                    'sharingList' => $sharingList
                );
                return successMsg('Routine detail', $response);
            } else return errorMsg('Routine not found');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }


    // Dev name : Dishant Gupta
    // This function is used to getting the share the routine to another user
    public function shareRoutine(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'user_id' => 'required'
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $oldroutine = Routine::where('id', $id)->where('created_by', auth()->user()->id)->where('type', 'R')->first();
                if (isset($oldroutine->id)) {
                    $routine = new Routine;
                    $routine->type = 'R';
                    $routine->name = $oldroutine->name;
                    $routine->subtitle = $oldroutine->subtitle ?? null;
                    $routine->description = $oldroutine->description;
                    $routine->priority = $oldroutine->priority ?? 'L';
                    $routine->category_id = $oldroutine->category_id;
                    $routine->created_by = $request->user_id;
                    $routine->shared_by = auth()->user()->id;
                    $routine->status = 1;
                    $routine->save();

                    $oldSchedule = Schedule::where('routines_id', $oldroutine->id)->first();
                    $schedule = new Schedule;
                    $schedule->routines_id = $routine->id;
                    $schedule->frequency = $oldSchedule->frequency;
                    $schedule->is_enable = 1;
                    $schedule->save();

                    $oldScheduleInterval = ScheduleInterval::where('schedule_id', $oldSchedule->id)->get();
                    if ($oldSchedule->frequency == 'O') {
                        foreach ($oldScheduleInterval as $key5 => $scheduletime) {
                            $interval = new ScheduleInterval;
                            $interval->interval_time = $scheduletime->interval_time;
                            $interval->schedule_id = $schedule->id;
                            $interval->save();
                        }
                    } elseif ($oldSchedule->frequency == 'D') {
                        foreach ($oldScheduleInterval as $key5 => $scheduletime) {
                            $interval = new ScheduleInterval;
                            $interval->interval_time = $scheduletime->interval_time;
                            $interval->schedule_id = $schedule->id;
                            $interval->save();
                        }
                    } elseif ($oldSchedule->frequency == 'T') {
                        $schedule = Schedule::find($schedule->id);
                        $schedule->schedule_time = $oldSchedule->date;
                        $schedule->update();
                        foreach ($oldScheduleInterval as $key5 => $scheduletime) {
                            $interval = new ScheduleInterval;
                            $interval->interval_time = $scheduletime->interval_time;
                            $interval->schedule_id = $schedule->id;
                            $interval->save();
                        }
                    } elseif ($oldSchedule->frequency == 'C') {
                        $schedule = Schedule::find($schedule->id);
                        $schedule->schedule_startdate = $oldSchedule->schedule_startdate ?? null;
                        $schedule->schedule_enddate = $oldSchedule->schedule_enddate ?? null;
                        $schedule->update();
                        foreach ($oldScheduleInterval as $key5 => $scheduletime) {
                            $interval = new ScheduleInterval;
                            $interval->interval_time = $scheduletime->interval_time;
                            $interval->interval_weak = $scheduletime->interval_weak;
                            $interval->schedule_id = $schedule->id;
                            $interval->save();
                        }
                    }

                    $share = new SharingDetail;
                    $share->user_id = auth()->user()->id;
                    $share->routine_id = $routine->id;
                    $share->shared_to = $request->user_id;
                    $share->status = 1;
                    $share->save();

                    return successMsg('Routine shared successfully');
                } else return errorMsg('Routine not found');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to update a routine
    public function editRoutine(Request $request)
    {
        try {
            if ($request->frequency == 'T') {
                $validRequest = ['id' => 'required', 'name' => 'required', 'description' => 'required', 'category_id' => 'required', 'frequency' => 'required', 'schedule_time' => 'required|array', 'date' => 'required'];
            } elseif ($request->frequency == 'C') {
                $validRequest = ['id' => 'required', 'name' => 'required', 'description' => 'required', 'category_id' => 'required', 'frequency' => 'required', 'schedule_time' => 'required|array', 'custom' => 'required|array'];
            } else {
                $validRequest = ['id' => 'required', 'name' => 'required', 'description' => 'required', 'category_id' => 'required', 'frequency' => 'required', 'schedule_time' => 'required|array'];
            }
            $validator = Validator::make($request->all(), $validRequest);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $routine = Routine::where('id', $request->id)->where('type', 'R')->first();
                if(isset($routine->id)){
                    if($routine->shared_by != null) return errorMsg("You can't edit this routine");
                    if($routine->created_by == auth()->user()->id){
                        $routine->name = $request->name;
                        $routine->subtitle = $request->subtitle ?? null;
                        $routine->description = $request->description;
                        $routine->priority = $request->priority ?? 'L';
                        $routine->category_id = $request->category_id;
                        $routine->updated_at = date('Y-m-d H:i:s');
                        $routine->save();
        
                        $schedule = Schedule::where('routines_id', $routine->id)->first();
                        ScheduleInterval::where('schedule_id',$schedule->id)->delete();
                        $schedule->frequency = $request->frequency;
        
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
                            $schedule->schedule_time = $request->date;
                            foreach ($request->schedule_time as $key5 => $scheduletime) {
                                $interval = new ScheduleInterval;
                                $interval->interval_time = Carbon::parse($scheduletime)->format('H:i');
                                $interval->schedule_id = $schedule->id;
                                $interval->save();
                            }
                        } elseif ($request->frequency == 'C') {
                            $schedule->schedule_startdate = $request->schedule_startdate ?? null;
                            $schedule->schedule_enddate = $request->schedule_enddate ?? null;
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
    
                        $schedule->save();
                        return successMsg('Routine updated successfully');
                    } else return errorMsg('This routine is not created by you');
                } else return errorMsg('Routine not found');
            }
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
                $routine->type = 'R';
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
                return successMsg('Routine created successfully');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to create a task
    public function createTask(Request $request)
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
                $task = new Routine();
                $task->type = 'T';
                $task->name = $request->name;
                $task->subtitle = $request->subtitle ?? null;
                $task->description = $request->description;
                $task->priority = $request->priority ?? 'L';
                $task->category_id = $request->category_id;
                $task->created_by = auth()->user()->id;
                $task->status = 1;
                $task->save();

                if ($request->hasfile('images')) {
                    foreach ($request->file('images') as $key => $file) {
                        $attachement = new Attachment;
                        $attachement->routine_id = $task->id;
                        $attachement->routine_type = 'T';
                        $name = fileUpload($file, "/uploads/task/");
                        $attachement->file = $name;
                        $attachement->status = 1;
                        $attachement->save();
                    }
                }

                // entry for schedule task according to repeat time
                $schedule = new Schedule();
                $schedule->routines_id = $task->id;
                $schedule->frequency = $request->frequency;
                $schedule->is_enable = 1;
                $schedule->save();

                if ($request->frequency == 'O') {
                    foreach ($request->schedule_time as $key5 => $scheduletime) {
                        $interval = new Scheduleinterval();
                        $interval->interval_time = Carbon::parse($scheduletime)->format('H:i');
                        $interval->schedule_id = $schedule->id;
                        $interval->save();
                    }
                } elseif ($request->frequency == 'D') {
                    foreach ($request->schedule_time as $key5 => $scheduletime) {
                        $interval = new Scheduleinterval();
                        $interval->interval_time = Carbon::parse($scheduletime)->format('H:i');
                        $interval->schedule_id = $schedule->id;
                        $interval->save();
                    }
                } elseif ($request->frequency == 'T') {
                    $schedule = Schedule::find($schedule->id);
                    $schedule->schedule_time = $request->date;
                    $schedule->update();
                    foreach ($request->schedule_time as $key5 => $scheduletime) {
                        $interval = new Scheduleinterval();
                        $interval->interval_time = Carbon::parse($scheduletime)->format('H:i');
                        $interval->schedule_id = $schedule->id;
                        $interval->save();
                    }
                } elseif ($request->frequency == 'C') {
                    foreach ($request->custom as $key3 => $customs) {
                        foreach ($request->schedule_time as $key5 => $scheduletime) {
                            $interval = new Scheduleinterval();
                            $interval->interval_time = Carbon::parse($scheduletime)->format('H:i');
                            $interval->interval_weak = $customs;
                            $interval->schedule_id = $schedule->id;
                            $interval->save();
                        }
                    }
                }

                // $tasksharing = new RoutineSharingDetail;
                // $tasksharing->routines_id = $task->id;
                // $tasksharing->user_id = $request->user_id;
                // $tasksharing->save();
                if ($request->taskassignmembers) {
                    if (count($request->taskassignmembers) > 0) {
                        foreach ($request->taskassignmembers as $key3 => $assignmembers) {
                            $taskassignmember  = new TaskAssignMember;
                            $taskassignmember->task_id = $task->id;
                            $taskassignmember->user_id = $assignmembers;
                            $taskassignmember->status = 1;
                            $taskassignmember->save();
                        }
                    }
                }
                $tasks['taskid'] = $task->id;
                $tasks['created_by'] = auth()->user()->id;
                $tasks['taskname'] = $task->name;
                return successMsg("Task created successfully");
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to getting the details of particular task
    public function taskDetail(Request $request, $id)
    {
        try {
            $task = Routine::where('id', $id)->where('created_by', auth()->user()->id)->where('type', 'T')->first();
            if (isset($task->id)) {
                $interval = array();
                foreach ($task->schedule->interval as $key => $val) {
                    $temp['id'] = $val->id;
                    $temp['interval_weak_name'] = isset($val->interval_weak) ? config('constant.days')[$val->interval_weak] : null;
                    $temp['interval_weak'] = isset($val->interval_weak) ? $val->interval_weak : null;
                    $temp['interval_time'] = $val->interval_time;
                    $interval[] = $temp;
                }
                $images = array();
                foreach ($task->images as $key => $val) {
                    $tempImg['id'] = $val->id;
                    $tempImg['image'] = isset($val->file) ? assets('/uploads/task/'.$val->file) : null;
                    $images[] = $tempImg;
                }
                $taskAssign = array();
                foreach ($task->taskAssignMember as $key => $val) {
                    $tempAssign['id'] = $val->id;
                    $tempAssign['user_id'] = $val->user_id;
                    $tempAssign['name'] = $val->user->name;
                    $tempAssign['profile'] = isset($val->user->profile) ? assets('/uploads/profile/'.$val->user->profile) : assets("assets/images/no-image.jpg");
                    $taskAssign[] = $tempAssign;
                }
                $response = array(
                    'id' => $task->id,
                    'name' => $task->name,
                    'subtitle' => $task->subtitle,
                    'description' => $task->description,
                    'tasktype' => ($task->privacy == 'P') ? 'Public Routine' : 'Private Routine',
                    'date' => date('d M, Y h:i A', strtotime($task->created_at)),
                    'category_id' => $task->category->id ?? null,
                    'category_name' => $task->category->name ?? null,
                    'category_logo' => isset($task->category->logo) ? assets('uploads/routine/' . $task->category->logo) : assets("assets/images/no-image.jpg"),
                    'created_by' => ($task->created_by == auth()->user()->id) ? 'mySelf' : 'shared',
                    'schedule_frequency_name' => config('constant.frequency')[$task->schedule->frequency] ?? null,
                    'schedule_frequency' => $task->schedule->frequency ?? null,
                    'schedule_date' => $task->schedule->schedule_time ?? null,
                    'interval' => $interval ?? null,
                    'images' => $images ?? null,
                    'taskAssign' => $taskAssign ?? null,
                );
                return successMsg('Task detail', $response);
            } else return errorMsg('Task not found');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to update a task
    public function editTask(Request $request)
    {
        try {
            if ($request->frequency == 'T') {
                $validRequest = ['id' => 'required', 'name' => 'required', 'description' => 'required', 'category_id' => 'required', 'frequency' => 'required', 'schedule_time' => 'required|array', 'date' => 'required'];
            } elseif ($request->frequency == 'C') {
                $validRequest = ['id' => 'required', 'name' => 'required', 'description' => 'required', 'category_id' => 'required', 'frequency' => 'required', 'schedule_time' => 'required|array', 'custom' => 'required|array'];
            } else {
                $validRequest = ['id' => 'required', 'name' => 'required', 'description' => 'required', 'category_id' => 'required', 'frequency' => 'required', 'schedule_time' => 'required|array'];
            }
            $validator = Validator::make($request->all(), $validRequest);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $task = Routine::where('id', $request->id)->where('type', 'T')->first();
                if(isset($task->id)){
                    if($task->created_by == auth()->user()->id){
                        if ($request->hasFile("images")) 
                            if(isInvalidExtension($request->images)) return errorMsg('Only JPG, JPEG & PNG format are allowed');

                        $task->type = 'T';
                        $task->name = $request->name;
                        $task->subtitle = $request->subtitle ?? null;
                        $task->description = $request->description;
                        $task->priority = $request->priority ?? 'L';
                        $task->category_id = $request->category_id;
                        $task->updated_at = date('Y-m-d H:i:s');
                        $task->save();
        
                        if(isset($request->deletefile) && count($request->deletefile) > 0){
                            foreach($request->deletefile as $val){
                                $taskImage = Attachment::where('id', $val)->where('routine_id', $task->id)->where('routine_type', 'T')->first();
                                fileRemove("/uploads/task/$taskImage->name");
                                Attachment::where('id', $val)->where('routine_id', $task->id)->where('routine_type', 'T')->delete();
                            }
                        }
                        if ($request->hasfile('images')) {
                            foreach ($request->file('images') as $key => $file) {
                                $attachement = new Attachment;
                                $attachement->routine_id = $task->id;
                                $attachement->routine_type = 'T';
                                $name = fileUpload($file, "/uploads/task/");
                                $attachement->file = $name;
                                $attachement->status = 1;
                                $attachement->save();
                            }
                        }

                        $schedule = Schedule::where('routines_id', $task->id)->first();
                        ScheduleInterval::where('schedule_id',$schedule->id)->delete();
                        $schedule->frequency = $request->frequency;
        
                        if ($request->frequency == 'O') {
                            foreach ($request->schedule_time as $key5 => $scheduletime) {
                                $interval = new Scheduleinterval();
                                $interval->interval_time = Carbon::parse($scheduletime)->format('H:i');
                                $interval->schedule_id = $schedule->id;
                                $interval->save();
                            }
                        } elseif ($request->frequency == 'D') {
                            foreach ($request->schedule_time as $key5 => $scheduletime) {
                                $interval = new Scheduleinterval();
                                $interval->interval_time = Carbon::parse($scheduletime)->format('H:i');
                                $interval->schedule_id = $schedule->id;
                                $interval->save();
                            }
                        } elseif ($request->frequency == 'T') {
                            $schedule = Schedule::find($schedule->id);
                            $schedule->schedule_time = $request->date;
                            $schedule->update();
                            foreach ($request->schedule_time as $key5 => $scheduletime) {
                                $interval = new Scheduleinterval();
                                $interval->interval_time = Carbon::parse($scheduletime)->format('H:i');
                                $interval->schedule_id = $schedule->id;
                                $interval->save();
                            }
                        } elseif ($request->frequency == 'C') {
                            foreach ($request->custom as $key3 => $customs) {
                                foreach ($request->schedule_time as $key5 => $scheduletime) {
                                    $interval = new Scheduleinterval();
                                    $interval->interval_time = Carbon::parse($scheduletime)->format('H:i');
                                    $interval->interval_weak = $customs;
                                    $interval->schedule_id = $schedule->id;
                                    $interval->save();
                                }
                            }
                        }
    
                        $schedule->save();
                        if ($request->taskassignmembers) {
                            if (count($request->taskassignmembers) > 0) {
                                TaskAssignMember::where('task_id', $task->id)->delete();
                                foreach ($request->taskassignmembers as $key3 => $assignmembers) {
                                    $taskassignmember  = new TaskAssignMember;
                                    $taskassignmember->task_id = $task->id;
                                    $taskassignmember->user_id = $assignmembers;
                                    $taskassignmember->status = 1;
                                    $taskassignmember->save();
                                }
                            }
                        }
                        return successMsg("Task updated successfully");
                    } else return errorMsg('This task is not created by you');
                } else return errorMsg('Task not found');
                
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to delete a task
    public function deleteTask(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required'
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $task = Routine::where('id', $request->id)->where('type', 'T')->first();
                if(isset($task->id)){
                    if($task->created_by == auth()->user()->id){
                        $schedule = Schedule::where('routines_id', $task->id)->first();
                        ScheduleInterval::where('schedule_id', $schedule->id)->delete();
                        Schedule::where('routines_id', $task->id)->delete();
                        TaskAssignMember::where('task_id', $task->id)->delete();
                        UserHideTask::where('task_id', $task->id)->delete();
                        $attach = Attachment::where('routine_id', $task->id)->where('routine_type', 'T')->get();
                        foreach($attach as $val){
                            fileRemove("/uploads/task/$val->file");
                        }
                        Attachment::where('routine_id', $task->id)->where('routine_type', 'T')->delete();
                        Routine::where('id', $request->id)->delete();
                        return successMsg('Task deleted successfully');
                    } else return errorMsg('This task is not created by you');
                } else return errorMsg('Task not found');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // public function sendroutinenotification(Request $request)
    // {
    //     $routine = Routine::where('task_type', 'R')->get();
    //     $routines = array();
    //     foreach ($routine as $key => $alltask) {
    //         $routines[$key]['id'] = $alltask->id;
    //         $routines[$key]['createdby'] = $alltask->created_by;
    //         $schedule = Schedule::where('routines_id', $alltask->id)->first();
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
