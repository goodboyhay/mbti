<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CandidateStore;
use Illuminate\Support\Facades\Validator;
use Dirape\Token\Token;
use App\Results;
use App\Candidates;
use App\Questions;

class CandidateController extends Controller
{

    public function getAnswers($candidate_id){
        $data = ['candidate_id'=>$candidate_id,];
        $rules = [
            'candidate_id'=>['required', 'integer', 'exists:candidates,candidate_id'],
        ];
        $validator = Validator::make($data, $rules);
        if($validator->fails()){
            return response()->json([
                'response_code'=> 400,
                'success' => false,
                'message' => $validator->errors(),
                'data' => [],
            ], 400);
        } else {
            $candidate = Candidates::where('candidate_id',$data['candidate_id'])->first()->makeHidden(['created_at','updated_at']);
            $result = Results::where('candidate_id',$data['candidate_id'])->latest('created_at')->first(['answer_result','subject_id']);
            $questions = Questions::where('subject_id',$result['subject_id'])->get()->makeHidden(['created_at','updated_at','type','subject_id']);
            $answer = json_decode($result['answer_result'], true);
            for($i=1;$i<=35;$i++){
                $answer[$i][0]==1?$answer[$i]='A':$answer[$i]='B';
            };
            $responseData = ["candidate_info"=>$candidate,"subject_id"=>$result['subject_id'],"questions"=>$questions,"candidate_answer"=>$answer,];
            return response()->json([
                'response_code'=> 200,
                'success' => true,
                'message' => 'Successful.',
                'data' => $responseData,
            ], 200);
        };
    }

    public function index(){
        $candidates=Candidates::sortable()
                    ->join('results', 'candidates.candidate_id','=','results.candidate_id')
                    ->join('characters', 'results.mbti_result','=','characters.name')
                    ->select(
                        'candidates.candidate_id as candidate_id',
                        'candidates.name as candidateName',
                        'candidates.email',
                        'candidates.updated_at',
                        'results.mbti_result',
                        'characters.name as characterName',
                        'characters.nickname',
                        'candidates.position'
                    )
                    ->paginate(6);
        return response()->json([
            'response_code'=> 200,
            'success' => true,
            'message' => 'Successful.',
            'data' => $candidates,
        ], 200);
    }

    public function store(CandidateStore $request)
    {
        $data = $request->all();
        $name = preg_replace('!\s+!', ' ', $data['name']);
        $candidate=Candidates::create([
            'name'=>$name,
            'email'=>$data['email'], 
            'dob'=>$data['dob'], 
            'position'=>$data['position'], 
            'token_key'=>(new Token())->Unique('candidates','token_key',60),
            ])->latest('candidate_id')->first();
        return response()->json([
            'response_code'=> 200,
            'success' => true,
            'message' => 'Successful.',
            'data' => $candidate,
        ],200);
    }

    public function delete($candidate_id){
        $candidate = Candidates::where('candidate_id', $candidate_id)->delete();;
        return response()->json([
            'response_code'=> 200,
            'success' => true,
            'message' => 'Successful Delete Candidate.',
            'data' => []
        ],200);
    }

    public function search(Request $request){
        $search=$request->get('search');
        $candidates =Candidates::query()->join('results', 'candidates.candidate_id','=','results.candidate_id')
                                    ->join('characters', 'results.mbti_result','=','characters.name')
                                    ->select(
                                        'candidates.candidate_id as candidate_id',
                                        'candidates.name as candidateName',
                                        'candidates.email',
                                        'candidates.updated_at',
                                        'results.mbti_result',
                                        'characters.name as characterName',
                                        'characters.nickname',
                                        'candidates.position'
                                    )
                                    ->where('candidates.name','regexp',"$search")
                                    ->orwhere('candidates.dob','regexp',"$search")
                                    ->orwhere('candidates.position','regexp',"$search")
                                    ->paginate(10);
        $jsonFormat = json_encode($candidates);
        $total =  json_decode($jsonFormat, true );
        if($total['total']==0){
            return response()->json([
                'response_code'=> 400,
                'success' => false,
                'message' => "Not found similar result.",
                'data' => []
            ],400);
        } else{
            return response()->json([
                'response_code'=> 200,
                'success' => true,
                'message' => 'Successful.',
                'data' => $candidates,
            ],200);
        }
    }

    public function sort(Request $request){
        $sortBy=$request->get('sort_by');
        $direction=$request->get('direction');
        $candidates =Candidates::query()->join('results', 'candidates.candidate_id','=','results.candidate_id')
                                    ->join('characters', 'results.mbti_result','=','characters.name')
                                    ->select(
                                        'candidates.candidate_id as candidate_id',
                                        'candidates.name as candidateName',
                                        'candidates.email',
                                        'candidates.updated_at',
                                        'results.mbti_result',
                                        'characters.name as characterName',
                                        'characters.nickname',
                                        'candidates.position'
                                    )
                                    ->orderBy($sortBy, $direction)
                                    ->paginate(10);
        return response()->json([
            'response_code'=> 200,
            'success' => true,
            'message' => 'Successful.',
            'data' => $candidates
        ],200);
    }
}
