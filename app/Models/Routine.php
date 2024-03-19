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
        return $this->belongsTo(RoutineCategory::class, 'category_id', 'id')->withDefault(['name' => 'NA', 'description' => 'NA', 'logo' => 'NA', 'code' => 'NA']);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'id', 'routines_id')->withDefault(['schedule_name' => 'NA', 'frequency' => 'NA', 'frequency_interval' => 'NA', 'schedule_time' => 'NA']);
    }
}
