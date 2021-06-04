<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Questions as AppQuestions;
use Illuminate\Http\Request;
use App\Questions;
use App\Subjects;

class QuestionsController extends Controller
{
    public function getQuestions(){
        $subjects = Subjects::where('subject_id' ,'>' ,0)->pluck('subject_id')->toArray();
        $randomSubjectId = $subjects[array_rand($subjects)];
        return response()->json([
            'response_code'=> 200,
            'success' => true,
            'message' => "Successful.",
            'data' => [
                'subject_id'=>$randomSubjectId, 
                'questions'=> Questions::where("subject_id",$randomSubjectId)->get()->makeHidden(['created_at','updated_at','type','subject_id'])
            ],
        ], 200);
    }
}
