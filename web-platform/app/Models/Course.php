<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
   protected $fillable = [

        'dataset_index',
        'title',
        'platform',
        'level',
        'description',
        'url',
        'skills',
    ];
}
