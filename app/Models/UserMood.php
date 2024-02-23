<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMood extends Model
{
    use HasFactory;
    protected $table = 'user_mood';
    protected $id = 'id';
}
