<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'plan_id',
        'community_id',
        'title',
        'post_description',
        'post_image',
        'created_by',
        'created_at',
        'updated_at'
    ];

    protected $table = 'posts';
    protected $primaryKey = 'id';

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id')->withDefault(['name' => null, 'email' => null, 'profile' => null, 'mobile' => null]);
    }

    public function images(){
        return $this->hasMany(PostImage::class, 'post_id', 'id');
    }

    public function likeCount(){
        return $this->hasMany(UserLike::class, 'object_id', 'id')->where('object_type', 'post')->count();
    }

    public function commentCount(){
        return $this->hasMany(Comment::class, 'object_id', 'id')->where('object_type', 'post')->count();
    }

    public function comments(){
        return $this->hasMany(Comment::class, 'object_id', 'id')->whereNull('parent_id')->where('object_type', 'post')->orderByDesc('id')->get();
    }
}