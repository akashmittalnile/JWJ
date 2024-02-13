<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFollowedCommunity extends Model
{
    use HasFactory;
    protected $table = 'user_followed_community';
    protected $id = 'id';
}
