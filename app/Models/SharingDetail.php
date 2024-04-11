<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SharingDetail extends Model
{
    use HasFactory;
    protected $table = 'sharing_details';
    protected $id = 'id';

    public function user(){
        return $this->belongsTo(User::class, 'shared_to', 'id');
    }
    public function routine(){
        return $this->belongsTo(User::class, 'routine_id', 'id');
    }
}
