<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Candidates;

class SurveyController extends Controller
{
    public function candidateStart(){
        return view('candidate.start');
    }
    public function candidateInfo(){
        return view('candidate.info');
    }
    public function doSurvey(){
        return view('candidate.survey');
    }
    public function result(){
        return view('candidate.result');
    }
    public function login(){
        return view('hr.login');
    }
    public function candidatesList(){
        return view('hr.list');
    }
    public function candidateResult($id){
        return view('hr.candidate-result')->with('candidate_id',$id);
    }
    public function candidateAnswer($id){
        return view('hr.candidate-answer')->with('candidate_id',$id);
    }
}
