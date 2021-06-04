<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/','SurveyController@candidateStart')->name('candidate.start');
Route::get('/candidate-info','SurveyController@candidateInfo')->name('candidate.info');
Route::get('/survey','SurveyController@doSurvey')->name('candidate.survey');
Route::get('/result','SurveyController@result')->name('candidate.result');
Route::get('/hr-login','SurveyController@login')->name('login');

// These routes of HR Page need middleware
Route::get('/candidates-list','SurveyController@candidatesList')->name('hr.candidates-list');
Route::get('/candidate-result/{id}','SurveyController@candidateResult')->name('hr.candidate-result');
Route::get('/candidate-answer/{id}','SurveyController@candidateAnswer')->name('hr.candidate-answer');