<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Routine extends Model
{
    use HasFactory;
    protected $table = 'routines';
    protected $id = 'id';

    public function category()
    {
        return $this->belongsTo(RoutineCategory::class, 'category_id', 'id')->withDefault(['name' => null, 'description' => null, 'logo' => null, 'code' => null]);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'id', 'routines_id')->withDefault(['schedule_name' => null, 'frequency' => null, 'frequency_interval' => null, 'schedule_time' => null]);
    }

    public function images(){
        return $this->hasMany(Attachment::class, 'routine_id', 'id')->where('routine_type', 'T')->withDefault(['file'=> null, 'id'=> null]);
    }

    public function taskAssignMember() {
        return $this->hasMany(TaskAssignMember::class, 'task_id', 'id');
    }

    public function sharedUser(){
        return $this->belongsTo(User::class, 'shared_by', 'id')->withDefault(['name' => null, 'email' => null, 'profile' => null, 'mobile' => null]);
    }
}
