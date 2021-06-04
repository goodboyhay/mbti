<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;

class LoginAuthController extends Controller
{
    public function register(Request $request){
        if(!User::where('email',$request->email)->first()){
            $data[]=array();
            $data['name']=$request->name;
            $data['email']=$request->email;
            $data['password']=bcrypt($request->password);    
            $user=User::create($data);
            $token = $user->createToken('Api Token')->accessToken;
            return response()->json([
                'response_code'=> 200,
                'success' => true,
                'message' => "Successful.",
                'data' => [
                    'user'=>$user,
                    'token'=>$token
                ],
            ], 200);
        }
        return response()->json([
            'response_code'=> 400,
            'success' => false,
            'message'=>'Email already exists',
            'data'=>[],
        ], 400);
    }
    public function login(Request $request){
        $data=$request->validate([
            'email'=>'email|required',
            'password'=>'required'
        ]);
        if(!auth()->attempt($data)){
            return response()->json([
                'response_code'=> 400,
                'success' => false,
                'message'=>'Wrong credentials, please try again!',
                'data'=>[],
            ], 400);
        }
        $token=auth()->user()->createToken('Api Token')->accessToken;
        return response()->json([
            'response_code'=> 200,
            'success' => true,
            'message' => "Successful.",
            'data' => [
                'user'=>auth()->user(),
                'token'=>$token
            ],
        ],200);
    }
    public function logout(){
        $user = auth()->user()->token();
        $user->revoke();
        Auth::logout();
        return response()->json([
            'response_code'=> 200,
            'success' => true,
            'message' => 'Logged out successful.'
        ], 200);
    }
    public function forgotPassword(Request $request){
        $credentials = $request->validate(['email' => 'required|email']);
        Password::sendResetLink($credentials);
        return response()->json(["msg" => 'Reset password link sent on your email id.']);
    }
}
