<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalSearchCriteria extends Model
{
    use HasFactory;
    protected $table = 'journals_search_criteria_mapping';
    protected $id = 'id';
}
