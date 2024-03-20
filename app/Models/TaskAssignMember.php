<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskAssignMember extends Model
{
    use HasFactory;
    protected $table = 'task_assign_members';
    protected $id = 'id';
}
