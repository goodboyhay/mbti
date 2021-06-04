<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Results;
use App\Candidates;
use App\Character;

class ResultController extends Controller
{
    protected function resultScore($myArray,$myKey){  
        $arrayTemp      =   array();                
        $sumOne         =   0;                      
        $sumTwo         =   0;
        foreach($myArray as $array){                
            $arrayTemp[]=   $myKey[$array];
        }
        for($i=0;$i<count($arrayTemp);$i++){
            $sumOne     +=  $arrayTemp[$i][0];
            $sumTwo     +=  $arrayTemp[$i][1];
        }
        return [$sumOne,$sumTwo];
    }

    protected function pointCalculation2($formatedAnswer){
        $myKey              =   array();
        $myMBTI             =   array();
        $typeOne            =   ['1','8','15','22','29'];
        $typeTwo            =   ['2','3','9','10','16','17','23','24','30','31'];
        $typeThree          =   ['4','5','11','12','18','19','25','26','32','33'];
        $typeFour           =   ['6','7','13','14','20','21','27','28','34','35'];
        $arrayJson[]        =   json_decode($formatedAnswer, true);
        foreach( $arrayJson as $myKey){
            $myKey[]=$arrayJson;
        }
        $resultScoreOne     =   $this->resultScore($typeOne,$myKey);
        $resultScoreTwo     =   $this->resultScore($typeTwo,$myKey);
        $resultScoreThree   =   $this->resultScore($typeThree,$myKey);
        $resultScoreFour    =   $this->resultScore($typeFour,$myKey);
        $dataChart=[
            'E'             =>  $resultScoreOne[0],
            'I'             =>  $resultScoreOne[1],
            'S'             =>  $resultScoreTwo[0],
            'N'             =>  $resultScoreTwo[1],
            'T'             =>  $resultScoreThree[0],
            'F'             =>  $resultScoreThree[1],
            'J'             =>  $resultScoreFour[0],
            'P'             =>  $resultScoreFour[1]
        ];

        $myMBTI[0]          =   $resultScoreOne[0]>$resultScoreOne[1] ? "E": "I";
        $myMBTI[1]          =   $resultScoreTwo[0]>$resultScoreTwo[1] ? "S": "N";
        $myMBTI[2]          =   $resultScoreThree[0]>$resultScoreThree[1] ? "T": "F";
        $myMBTI[3]          =   $resultScoreFour[0]>$resultScoreFour[1] ? "J": "P";
        return ['mbti_result'=>implode($myMBTI),'summary'=>$dataChart];
    }

    protected function surveySubmit(Request $request){
        $data = json_decode($request->getContent(), true);
        $rules = [
            'candidate_id'=>['required', 'integer', 'exists:candidates,candidate_id'], 
            'subject_id'=>['required','integer', 'exists:subjects,subject_id'], 
            'answer_result' => ['required'],
            'answer_result.*'=>['required','string','regex:/^[A-B]+$/u'],
            'token_key'=>['required']
        ];
        $validator = Validator::make($data, $rules);
        if($validator->fails()){
            return response()->json([
                'response_code'=> 400,
                'success' => false,
                'message' => $validator->errors()->getMessages(),
                'data' => [],
            ], 400);
        } else {
            $keys = array_keys($data['answer_result']);
            foreach($keys as $key){
                if($key<=0 || $key>=36 || count($keys)!==35) {
                    return response()->json([
                        'response_code'=> 400,
                        'success' => false,
                        'message' => 'answer_result is not valid',
                        'data' => [],
                    ], 400);
                }
            }
            for($i=1;$i<=35;$i++){
                $data['answer_result'][$i]=='A'?$data['answer_result'][$i]=[1,0]:$data['answer_result'][$i]=[0,1];
            };
            $token = Candidates::where('candidate_id',$data['candidate_id'])->first('token_key');
            if($data['token_key']!==$token['token_key']){
                return response()->json([
                    'response_code'=> 400,
                    'success' => false,
                    'message' => 'Token is invalid.',
                    'data' => [],
                ], 400);
            }
            $formatedAnswer = json_encode($data['answer_result']);
            $pointCalculated = $this->pointCalculation2($formatedAnswer);
            Results::insert([
                'candidate_id' => $data['candidate_id'],
                'subject_id' => $data['subject_id'],
                'answer_result'=> $formatedAnswer,
                'mbti_result'=> $pointCalculated['mbti_result'],
                'summary'=> json_encode($pointCalculated['summary']),
                'created_at' => date('y-m-d H:i:s'),
                'updated_at' => date('y-m-d H:i:s'),
            ]);
            return response()->json([
                'response_code'=> 200,
                'success' => true,
                'message' => 'Successful.',
                'data' => [],
            ], 200);
        };
    }

    protected function showResult(Request $request){
        $data = json_decode($request->getContent(), true);
        $rules = [
            'candidate_id'=>['required', 'integer', 'exists:candidates,candidate_id'], 
            'token_key'=>['required']
        ];
        $validator = Validator::make($data, $rules);
        if($validator->fails()){
            return response()->json([
                'response_code'=> 400,
                'success' => false,
                'message' => $validator->errors()->getMessages(),
                'data' => [],
            ], 400);
        } else {
            $token = Candidates::where('candidate_id',$data['candidate_id'])->first('token_key');
            if($data['token_key']!==$token['token_key']){
                return response()->json([
                    'response_code'=> 400,
                    'success' => false,
                    'message' => 'Token is invalid.',
                    'data' => [],
                ], 400);
            }
            $myResult           =   Results::where('candidate_id', $data['candidate_id'])->latest('created_at')->first();
            $myUser             =   Candidates::where('candidate_id',$myResult->candidate_id)->latest('created_at')->first();
            $myCharacter        =   Character::where('name',$myResult->mbti_result)->first();
            return response()->json([
                'response_code'=> 200,
                'success' => true,
                'message' => 'Successful.',
                'data' => [
                    'candidate_id'=>$myUser->candidate_id,
                    'name'=>$myUser->name,
                    'dob'=>$myUser->dob,
                    'position'=>$myUser->position,
                    'email'=>$myUser->email,
                    'mbti_result'=>$myResult->mbti_result,
                    'nickname'=>$myCharacter->nickname,
                    'overview'=>$myCharacter->overview,
                    'advantages'=>$myCharacter->advantages,
                    'weakness'=>$myCharacter->weakness,
                    'suitable_jobs'=>$myCharacter->suitable_jobs,
                    'summary'=>$myResult->summary
                ],
            ], 200);
        };
    }

    protected function hrShowResult($candidate_id){
        $data = ['candidate_id'=>$candidate_id,];
        $rules = [
            'candidate_id'=>['required', 'integer', 'exists:candidates,candidate_id'], 
        ];
        $validator = Validator::make($data, $rules);
        if($validator->fails()){
            return response()->json([
                'response_code'=> 400,
                'success' => false,
                'message' => $validator->errors()->getMessages(),
                'data' => [],
            ], 400);
        } else {
            $myResult           =   Results::where('candidate_id', $data['candidate_id'])->latest('created_at')->first();
            $myUser             =   Candidates::where('candidate_id',$myResult->candidate_id)->latest('created_at')->first();
            $myCharacter        =   Character::where('name',$myResult->mbti_result)->first();
            return response()->json([
                'response_code'=> 200,
                'success' => true,
                'message' => 'Successful.',
                'data' => [
                    'candidate_id'=>$myUser->candidate_id,
                    'name'=>$myUser->name,
                    'dob'=>$myUser->dob,
                    'position'=>$myUser->position,
                    'email'=>$myUser->email,
                    'mbti_result'=>$myResult->mbti_result,
                    'nickname'=>$myCharacter->nickname,
                    'overview'=>$myCharacter->overview,
                    'advantages'=>$myCharacter->advantages,
                    'weakness'=>$myCharacter->weakness,
                    'suitable_jobs'=>$myCharacter->suitable_jobs,
                    'summary'=>$myResult->summary
                ],
            ], 200);
        };
    }
}
