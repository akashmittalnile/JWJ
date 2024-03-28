<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    use HasFactory;
    protected $table = 'journals';
    protected $id = 'id';

    public function images(){
        return $this->hasMany(JournalImage::class, 'journal_id', 'id');
    }

    public function searchCriteria(){
        return $this->hasMany(JournalSearchCriteria::class, 'journal_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'created_by', 'id')->withDefault(['name' => null, 'mobile'=> null, 'email'=>null, 'profile'=> null]);
    }

    public function mood(){
        return $this->belongsTo(MoodMaster::class, 'mood_id', 'id');
    }
}
