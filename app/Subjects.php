<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subjects extends Model
{
    protected $fillable=[
        'title'
    ];
    
    protected $primaryKey='subject_id';
}
