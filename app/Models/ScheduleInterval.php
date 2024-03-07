<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleInterval extends Model
{
    use HasFactory;
    protected $table = 'schedule_interval';
    protected $id = 'id';
}
