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
}