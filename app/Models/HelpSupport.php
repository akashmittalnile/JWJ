<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpSupport extends Model
{
    use HasFactory;
    protected $table = 'help_and_supports';
    protected $id = 'id';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withDefault(['name' => null, 'email' => null, 'profile' => null, 'user_name' => null, 'mobile' => null]);
    }
}
