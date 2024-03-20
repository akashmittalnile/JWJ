<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHideTask extends Model
{
    use HasFactory;
    protected $table = 'user_hide_tasks';
    protected $id = 'id';
}
