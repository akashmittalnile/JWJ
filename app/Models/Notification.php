<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $table = 'notification';
    protected $id = 'id';

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id', 'id')->withDefault(['name' => null, 'monthly_price' => null, 'anually_price' => null, 'currency' => null]);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withDefault(['name' => null, 'user_name' => null, 'email' => null, 'profile' => null, 'mobile' => null]);
    }
}
