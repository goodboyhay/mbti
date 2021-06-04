<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Results extends Model
{
    protected $fillable = [
        'candidate_id', 
        'subject_id', 
        'answer_result',
        'mbti_result',
        'summary'
    ];
    
    protected $primaryKey='result_id';
}
