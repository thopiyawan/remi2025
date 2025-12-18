<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\noticeController;
use App\Http\Controllers\diaryController;
use App\Http\Controllers\testController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\SqlController;
use App\Http\Controllers\GetMessageController;

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


// Route::get('bot', function (Request $request) {
//     logger("message request : ", $request->all());
// });
// Route::post('bot', ['as' => 'line.bot.message', 'uses' => 'GetMessageController@getmessage']);

// //Route::get('peat_api','ApiController@api');
// Route::get('peat_api', function (Request $request) {
//     logger("message request : ", $request->all());
// });
// Route::post('peat_api', ['as' => 'peat_api', 'uses' => 'ApiController@api']);

// Route::post('peat_localRegister', ['as' => 'peat_localRegister', 'uses' => 'ApiController@peat_localRegister']);
// Route::post('peat_setGraphWeight', ['as' => 'peat_setGraphWeight', 'uses' => 'ApiController@peat_setGraphWeight']);



// ///api
// /////doctor register 
// Route::get('/doctor_register', 'ApiController@create');
// Route::post('doctor_register', 'ApiController@doctor_register');
// /////doctor login

// Route::get('/doctor_login', function () {
//     return view('login_doctor');
// });
// Route::post('/doctor_login/', 'ApiController@doctor_login');

// ///doctor get data users

// Route::post('/doctor_get_datamom/', 'ApiController@doctor_get_userdata');

// //weight warning
// Route::post('/weight_warning','diaryController@weight_warning');

// ///mom get log message
// Route::post('/get_log_message_mom/', 'ApiController@get_log_message_mom');

// ///mom get log message doctor to mom
// Route::post('/get_log/', 'ApiController@get_log');

// ///mom get tracker message
// Route::post('/get_tracker_mom/', 'ApiController@get_tracker_mom');

// ///mom get weight message
// Route::post('/get_weight_mom/', 'ApiController@get_weight_mom');

// ///get profile doctor
// Route::post('/get_profile_doctor/', 'ApiController@get_profile_doctor');

// ///get profile mom
// Route::post('/get_profile_mom/', 'ApiController@get_profile_mom');

// ///get profile mom
// Route::post('/list_user/', 'ApiController@list_user');

// ///get dashboard
// Route::post('/get_dashboard/', 'ApiController@get_dashboard');

// ///get update_hospital_num
// Route::put('/update_hospital_num/', 'ApiController@update_hospital_num');

// ///update user admin and doctor
// Route::put('/update_user/', 'ApiController@update_user');

Route::get('notice_breakfast', [noticeController::class, 'notice_breakfast']);
Route::get('notice_lunch', [noticeController::class, 'notice_lunch']);
Route::get('notice_dinner', [noticeController::class, 'notice_dinner']);

Route::get('food_diary/{id}', [diaryController::class, 'show_food']);
Route::get('vitamin_diary/{id}', [diaryController::class, 'show_vitamin']);
Route::get('exercise_diary/{id}', [diaryController::class, 'show_exercise']);
Route::get('weight_diary/{id}', [diaryController::class, 'show_weight']);

Route::get('personal_doctor', [diaryController::class, 'personal_doctor_confirm']);
Route::post('pdoctor', [diaryController::class, 'p_doctor'])->name('send_code');

Route::get('liff_register/{id}', [testController::class, 'liff_register']);
Route::get('disclaimer/{id}', [diaryController::class, 'disclaimer']);

Route::post('weight_warning', [diaryController::class, 'weight_warning']);

Route::get('record_diary/{id}', [diaryController::class, 'record_diary']);
Route::post('save_diary', [diaryController::class, 'savediary'])->name('savediary');
Route::post('savediary_vitexc', [diaryController::class, 'savediary_vitexc'])->name('savediary_vitexc');

Route::delete('remove_diary/{id}', [diaryController::class, 'remove_diary'])->name('delete_diary');

Route::get('record_sugar_blood', [diaryController::class, 'record_sugar_blood']);
Route::post('save_sugar_blood', [SqlController::class, 'insert_blood_sugar'])->name('sugar_blood');

Route::get('graph_sugar_blood/{id}', [diaryController::class, 'graph_sugar_blood']);
Route::delete('remove_bs/{id}', [diaryController::class, 'remove_bloodsugar'])->name('delete_bs');

Route::get('babykicks/{id}', [diaryController::class, 'fetal_movement']);
Route::get('getbabykick', [diaryController::class, 'getbabykick'])->name('getkick');
Route::post('save_babykicks', [diaryController::class, 'savebabykick'])->name('babykicks');

Route::get('birth', [diaryController::class, 'birth_noti']);
Route::post('save_birthdate', [diaryController::class, 'insert_birth_noti'])->name('birthdate');

Route::post('noti-fetalmove/{id}', [diaryController::class, 'noti_fetalmove'])->name('noti-fetalmove');

Route::post('submitcal', [diaryController::class, 'submitcal']);

Route::get('testWeb', function () {
    return 'Welcome REMI BOT';
});

Route::get('test/{user_id}', [ApiController::class, 'test_graph']);

Route::get('graph/{id}', [noticeController::class, 'graph']);

// Route::get('bot', function (Request $request) {
//     logger('message request : ', $request->all());
// });

// Route::post('bot', [GetMessageController::class, 'getmessage'])
//     ->name('line.bot.message');

Route::post('/bot', [GetMessageController::class, 'getmessage']);
Route::get('/bot', [GetMessageController::class, 'getmessage']);

Route::get('peat_api', function (Request $request) {
    logger('message request : ', $request->all());
});

Route::post('peat_api', [ApiController::class, 'api'])->name('peat_api');

Route::post('peat_localRegister', [ApiController::class, 'peat_localRegister']);
Route::post('peat_setGraphWeight', [ApiController::class, 'peat_setGraphWeight']);

Route::get('doctor_register', [ApiController::class, 'create']);
Route::post('doctor_register', [ApiController::class, 'doctor_register']);

Route::get('doctor_login', function () {
    return view('login_doctor');
});
Route::post('doctor_login', [ApiController::class, 'doctor_login']);

Route::post('doctor_get_datamom', [ApiController::class, 'doctor_get_userdata']);

Route::post('get_log_message_mom', [ApiController::class, 'get_log_message_mom']);
Route::post('get_log', [ApiController::class, 'get_log']);
Route::post('get_tracker_mom', [ApiController::class, 'get_tracker_mom']);
Route::post('get_weight_mom', [ApiController::class, 'get_weight_mom']);

Route::post('get_profile_doctor', [ApiController::class, 'get_profile_doctor']);
Route::post('get_profile_mom', [ApiController::class, 'get_profile_mom']);

Route::post('list_user', [ApiController::class, 'list_user']);
Route::post('get_dashboard', [ApiController::class, 'get_dashboard']);

Route::put('update_hospital_num', [ApiController::class, 'update_hospital_num']);
Route::put('update_user', [ApiController::class, 'update_user']);
