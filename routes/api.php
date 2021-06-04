<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['prefix' => 'auth'], function(){
    Route::post('login', 'Api\LoginAuthController@login');
    // Route::post('signup', 'Api\LoginAuthController@register');
    Route::get('logout', 'Api\LoginAuthController@logout')->name('logout');
});
Route::namespace('Api')->group(function () {
    Route::get('get-questions', 'QuestionsController@getQuestions');
    Route::post('submit-answers', 'ResultController@surveySubmit');
    Route::post('candidate-result-test','ResultController@showResult');
    Route::post('create-candidate', 'CandidateController@store')->name('store-candidate');
    
    Route::middleware('auth:api')->group(function(){
        Route::delete('candidate-delete/{id}', 'CandidateController@delete');
        Route::post('search', 'CandidateController@search');
        Route::get('candidates-list', 'CandidateController@index');
        Route::get('sort', 'CandidateController@sort');
        Route::get('candidate-detail/{id}','ResultController@hrShowResult');
        Route::get('candidates/survey/{id}','CandidateController@getAnswers');
    });
});


