<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Questions extends Model
{
    protected $fillable=[
        'subject_id', 
        'title', 
        'answer_a',
        'answer_b', 
        'type', 
        'index_number'
    ];
    
    protected $primaryKey='question_id';
}
