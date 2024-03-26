<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notify extends Model
{
    use HasFactory;
    protected $table = 'notify';
    protected $id = 'id';

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id', 'id')->withDefault(['name' => null, 'user_name' => null, 'email' => null, 'profile' => null, 'mobile' => null]);
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id', 'id')->withDefault(['name' => null, 'user_name' => null, 'email' => null, 'profile' => null, 'mobile' => null]);
    }
}
