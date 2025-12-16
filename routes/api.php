<?php

use Illuminate\Http\Request;

use App\Models\pregnants as pregnants;
use App\Models\RecordOfPregnancy as RecordOfPregnancy;
use App\Models\sequents as sequents;
use App\Models\sequentsteps as sequentsteps;
use App\Models\users_register as users_register;
use App\Models\tracker as tracker;
use App\Models\question as question;
use App\Models\quizstep as quizstep;
use App\Models\reward as reward;
use App\Models\doctor as doctor;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::get('bot', function (Request $request) {
    logger("message request : ", $request->all());
});
Route::post('bot', ['as' => 'line.bot.message', 'uses' => 'GetMessageController@getmessage']);




//Route::get('peat_api','ApiController@api');
Route::get('peat_api', function (Request $request) {
    logger("message request : ", $request->all());
});
Route::post('peat_api', ['as' => 'peat_api', 'uses' => 'ApiController@api']);

Route::post('peat_localRegister', ['as' => 'peat_localRegister', 'uses' => 'ApiController@peat_localRegister']);
Route::post('peat_setGraphWeight', ['as' => 'peat_setGraphWeight', 'uses' => 'ApiController@peat_setGraphWeight']);



///api
/////doctor register 
Route::get('/doctor_register', 'ApiController@create');
Route::post('doctor_register', 'ApiController@doctor_register');
/////doctor login

Route::get('/doctor_login', function () {
    return view('login_doctor');
});
Route::post('/doctor_login/', 'ApiController@doctor_login');

///doctor get data users

Route::post('/doctor_get_datamom/', 'ApiController@doctor_get_userdata');

//weight warning
Route::post('/weight_warning','diaryController@weight_warning');

///mom get log message
Route::post('/get_log_message_mom/', 'ApiController@get_log_message_mom');

///mom get log message doctor to mom
Route::post('/get_log/', 'ApiController@get_log');

///mom get tracker message
Route::post('/get_tracker_mom/', 'ApiController@get_tracker_mom');

///mom get weight message
Route::post('/get_weight_mom/', 'ApiController@get_weight_mom');

///get profile doctor
Route::post('/get_profile_doctor/', 'ApiController@get_profile_doctor');

///get profile mom
Route::post('/get_profile_mom/', 'ApiController@get_profile_mom');

///get profile mom
Route::post('/list_user/', 'ApiController@list_user');

///get dashboard
Route::post('/get_dashboard/', 'ApiController@get_dashboard');

///get update_hospital_num
Route::put('/update_hospital_num/', 'ApiController@update_hospital_num');

///update user admin and doctor
Route::put('/update_user/', 'ApiController@update_user');
