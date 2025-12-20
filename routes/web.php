<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\GetMessageController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\diaryController;
use App\Http\Controllers\noticeController;
use App\Http\Controllers\testController;
use App\Http\Controllers\SqlController;
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

// Route::get('/', function () {
//     return view('welcome');
// });
// Route::get('/{id}', 'testController@show');

// return redirect()->route('/', ['id' => 1]);

// Route::get('/graph/{id}', function($id)
// {

// 	return view('graph');
//     //    return $id;
// 	// return view('welcome');
// });

//////////////////////////////////////////////////////////////////////
Route::get('graph/{id}', [noticeController::class, 'graph']);
Route::get('notice_monday', [noticeController::class, 'notice_monday']);
Route::get('notice_day', [noticeController::class, 'notice_day']);
Route::get('api', [ApiController::class, 'api']);
Route::get('/index', [testController::class, 'index']);
Route::get('noti', [noticeController::class, 'test_noti']);

//Route::get('notice_monday','noticeController@notice_monday');
//Route::get('notice_day','noticeController@notice_day');
//Route::get('api','ApiController@api');
//Route::get('/index','testController@index');
//Route::get('noti', 'noticeController@test_noti');

Route::get('notice_breakfast', [noticeController::class, 'notice_breakfast']);
Route::get('notice_lunch', [noticeController::class, 'notice_lunch']);
Route::get('notice_dinner', [noticeController::class, 'notice_dinner']);
//Route::get('notice_breakfast','noticeController@notice_breakfast');
//Route::get('notice_lunch','noticeController@notice_lunch');
//Route::get('notice_dinner','noticeController@notice_dinner');

Route::get('food_diary/{id}', [diaryController::class, 'show_food']);
Route::get('vitamin_diary/{id}', [diaryController::class, 'show_vitamin']);
Route::get('exercise_diary/{id}', [diaryController::class, 'show_exercise']);
Route::get('weight_diary/{id}', [diaryController::class, 'show_weight']);
//Route::get('food_diary/{id}','diaryController@show_food');
//Route::get('vitamin_diary/{id}','diaryController@show_vitamin');
//Route::get('exercise_diary/{id}','diaryController@show_exercise');
//Route::get('weight_diary/{id}','diaryController@show_weight');

//การเชื่อมข้อมูลระหว่างคุณหมอกับหญิงตั้งครรภ์
//Route::get('personal_doctor/','diaryController@personal_doctor_confirm');
//Route::post('/pdoctor','diaryController@p_doctor')->name('send_code');
Route::get('personal_doctor', [diaryController::class, 'personal_doctor_confirm']);
Route::post('pdoctor', [diaryController::class, 'p_doctor'])->name('send_code');

//liff register
//Route::get('liff_register/{id}','testController@liff_register');
//Route::get('disclaimer/{id}','diaryController@disclaimer');
Route::get('liff_register/{id}', [testController::class, 'liff_register']);
Route::get('disclaimer/{id}', [diaryController::class, 'disclaimer']);

//weight warning
//Route::post('/weight_warning','diaryController@weight_warning');
Route::post('weight_warning', [diaryController::class, 'weight_warning']);

// Route::get('index','testController@index');

##Liff for normal pregnant
#บันทึกน้ำหนัก
// Route::get('record_weight/{id}','diaryController@record_weight');
// Route::post('/saveweight','diaryController@saveweight')->name('saveweight');
#บันทึกvอาหาร
//Route::get('record_diary/{id}','diaryController@record_diary');
//Route::post('/save_diary','diaryController@savediary')->name('savediary');
//Route::post('/savediary_vitexc','diaryController@savediary_vitexc')->name('savediary_vitexc');

//Route::delete('remove_diary/{id}','diaryController@remove_diary')->name('delete_diary'); 
Route::get('record_diary/{id}', [diaryController::class, 'record_diary']);
Route::post('save_diary', [diaryController::class, 'savediary'])->name('savediary');
Route::post('savediary_vitexc', [diaryController::class, 'savediary_vitexc'])->name('savediary_vitexc');

Route::delete('remove_diary/{id}', [diaryController::class, 'remove_diary'])
    ->name('delete_diary');

##Liff for GDM 
#การบันทึกน้ำตาล
//Route::get('record_sugar_blood','diaryController@record_sugar_blood');
//Route::post('/save_sugar_blood','SqlController@insert_blood_sugar')->name('sugar_blood');
Route::get('record_sugar_blood', [diaryController::class, 'record_sugar_blood']);
Route::post('save_sugar_blood', [SqlController::class, 'insert_blood_sugar'])
    ->name('sugar_blood');

Route::get('graph_sugar_blood/{id}', [diaryController::class, 'graph_sugar_blood']);

Route::delete('remove_bs/{id}', [diaryController::class, 'remove_bloodsugar'])
    ->name('delete_bs');

#หน้ากราฟน้ำตาล สรุปผลสุขภาพ
//Route::get('graph_sugar_blood/{id}','diaryController@graph_sugar_blood');
//Route::delete('remove_bs/{id}','diaryController@remove_bloodsugar')->name('delete_bs'); 
#นับลูกดิ้น
//Route::get('babykicks/{id}','diaryController@fetal_movement');
//Route::get('getbabykick','diaryController@getbabykick')->name('getkick');
//Route::post('/save_babykicks','diaryController@savebabykick')->name('babykicks');
Route::get('babykicks/{id}', [diaryController::class, 'fetal_movement']);
Route::get('getbabykick', [diaryController::class, 'getbabykick'])->name('getkick');
Route::post('save_babykicks', [diaryController::class, 'savebabykick'])
    ->name('babykicks');

#แจ้งเกิด
//Route::get('birth','diaryController@birth_noti');
//Route::post('/save_birthdate','diaryController@insert_birth_noti')->name('birthdate');
Route::get('birth', [diaryController::class, 'birth_noti']);
Route::post('save_birthdate', [diaryController::class, 'insert_birth_noti'])
    ->name('birthdate');


#แจ้งเตือนหมอว่าลูกดิ้นปกติ
// Route::get('noti-fetalmove/{id}','diaryController@noti_fetalmove')->name('noti-fetalmove'); 
//Route::post('/noti-fetalmove/{id}','diaryController@noti_fetalmove')->name('noti-fetalmove'); 
Route::post('noti-fetalmove/{id}', [diaryController::class, 'noti_fetalmove'])
    ->name('noti-fetalmove');


#บันทึกแคลอรี่
//Route::post('submitcal','diaryController@submitcal'); 
Route::post('submitcal', [diaryController::class, 'submitcal']);




// Route::get('testWeb',function()
// {
//   return 'Welcome REMI BOT';
// });

// // Route::get('/test', function () { return view('test_api'); });
// Route::get('/test/{user_id}','ApiController@test_graph');
Route::get('testWeb', function () {
    return 'Welcome REMI BOT';
});

Route::get('test/{user_id}', [ApiController::class, 'test_graph']);


// /////doctor register 
// Route::get('/doctor_register', 'ApiController@create');
// Route::post('doctor_register', 'ApiController@doctor_register');
// /////doctor login

// Route::post('/dashboard', 'ApiController@doctor_login')->name('login');

// ///doctor get data users
// Route::post('/doctor_get_datamom/', 'ApiController@doctor_get_userdata');
Route::get('doctor_register', [ApiController::class, 'create']);
Route::post('doctor_register', [ApiController::class, 'doctor_register']);

Route::post('dashboard', [ApiController::class, 'doctor_login'])->name('login');
Route::post('doctor_get_datamom', [ApiController::class, 'doctor_get_userdata']);


// // Management
//  Route::get('/', function () { return view('management.login'); });
// //  Route::get('/login', 'ApiController@doctor_login');
//  Route::get('/info/{user_id}', 'ApiController@viewInfo');
//  Route::get('/dashboard', 'ApiController@doctor_login')->name('dashboard');
//  Route::get('/logout', 'ApiController@doctor_logout');
//  Route::post('hnnumber_save', 'ApiController@hnnumber_save');
Route::get('/', function () {
    return view('management.login');
});

Route::get('info/{user_id}', [ApiController::class, 'viewInfo']);
Route::get('dashboard', [ApiController::class, 'doctor_login'])->name('dashboard');
Route::get('logout', [ApiController::class, 'doctor_logout']);
Route::post('hnnumber_save', [ApiController::class, 'hnnumber_save']);

 
 
 ///

// Route::get('admin_edit_user','ApiController@list_user');
// Route::get('edit_user/{id}','ApiController@show_edit');
// Route::post('edit/{id}','ApiController@edit'); 
// Route::delete('remove/{id}','ApiController@remove_user')->name('delete_user'); 
// //  Route::get('/admin_edit', function () { return view('admin_edit_user'); });
// // Route::get('message', function()
// // {
// //     return View::make('message');
// // });

// Route::get('/edit', function () { return view('management.login_edit'); });
Route::get('admin_edit_user', [ApiController::class, 'list_user']);
Route::get('edit_user/{id}', [ApiController::class, 'show_edit']);
Route::post('edit/{id}', [ApiController::class, 'edit']);

Route::delete('remove/{id}', [ApiController::class, 'remove_user'])
    ->name('delete_user');

Route::get('edit', function () {
    return view('management.login_edit');
});


Route::get('testa/{id}', [SqlController::class, 'test_sql']);