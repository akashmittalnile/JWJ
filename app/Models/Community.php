<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    use HasFactory;
    protected $table = 'communities';
    protected $id = 'id';

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id')->withDefault(['name' => null, 'email' => null, 'profile' => null, 'mobile' => null]);
    }

    public function communityImages()
    {
        return $this->hasMany(CommunityImage::class, 'community_id', 'id');
    }
}
