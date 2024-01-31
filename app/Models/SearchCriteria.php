<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchCriteria extends Model
{
    use HasFactory;
    protected $table = 'search_criteria';
    protected $id = 'id';
}
