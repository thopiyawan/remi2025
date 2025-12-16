<?php

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
Route::get('graph/{id}', 'noticeController@graph');
Route::get('notice_monday','noticeController@notice_monday');
Route::get('notice_day','noticeController@notice_day');
Route::get('api','ApiController@api');
Route::get('/index','testController@index');
Route::get('noti', 'noticeController@test_noti');


Route::get('notice_breakfast','noticeController@notice_breakfast');
Route::get('notice_lunch','noticeController@notice_lunch');
Route::get('notice_dinner','noticeController@notice_dinner');

Route::get('food_diary/{id}','diaryController@show_food');
Route::get('vitamin_diary/{id}','diaryController@show_vitamin');
Route::get('exercise_diary/{id}','diaryController@show_exercise');
Route::get('weight_diary/{id}','diaryController@show_weight');

//การเชื่อมข้อมูลระหว่างคุณหมอกับหญิงตั้งครรภ์
Route::get('personal_doctor/','diaryController@personal_doctor_confirm');
Route::post('/pdoctor','diaryController@p_doctor')->name('send_code');
//liff register
Route::get('liff_register/{id}','testController@liff_register');
Route::get('disclaimer/{id}','diaryController@disclaimer');
//weight warning
Route::post('/weight_warning','diaryController@weight_warning');

// Route::get('index','testController@index');

##Liff for normal pregnant
#บันทึกน้ำหนัก
// Route::get('record_weight/{id}','diaryController@record_weight');
// Route::post('/saveweight','diaryController@saveweight')->name('saveweight');
#บันทึกvอาหาร
Route::get('record_diary/{id}','diaryController@record_diary');
Route::post('/save_diary','diaryController@savediary')->name('savediary');
Route::post('/savediary_vitexc','diaryController@savediary_vitexc')->name('savediary_vitexc');

Route::delete('remove_diary/{id}','diaryController@remove_diary')->name('delete_diary'); 
##Liff for GDM 
#การบันทึกน้ำตาล
Route::get('record_sugar_blood','diaryController@record_sugar_blood');
Route::post('/save_sugar_blood','SqlController@insert_blood_sugar')->name('sugar_blood');
#หน้ากราฟน้ำตาล สรุปผลสุขภาพ
Route::get('graph_sugar_blood/{id}','diaryController@graph_sugar_blood');
Route::delete('remove_bs/{id}','diaryController@remove_bloodsugar')->name('delete_bs'); 
#นับลูกดิ้น
Route::get('babykicks/{id}','diaryController@fetal_movement');
Route::get('getbabykick','diaryController@getbabykick')->name('getkick');
Route::post('/save_babykicks','diaryController@savebabykick')->name('babykicks');

#แจ้งเกิด
Route::get('birth','diaryController@birth_noti');
Route::post('/save_birthdate','diaryController@insert_birth_noti')->name('birthdate');


#แจ้งเตือนหมอว่าลูกดิ้นปกติ
// Route::get('noti-fetalmove/{id}','diaryController@noti_fetalmove')->name('noti-fetalmove'); 
Route::post('/noti-fetalmove/{id}','diaryController@noti_fetalmove')->name('noti-fetalmove'); 


#บันทึกแคลอรี่
Route::post('submitcal','diaryController@submitcal'); 




Route::get('testWeb',function()
{
  return 'Welcome REMI BOT';
});

// Route::get('/test', function () { return view('test_api'); });
Route::get('/test/{user_id}','ApiController@test_graph');


/////doctor register 
Route::get('/doctor_register', 'ApiController@create');
Route::post('doctor_register', 'ApiController@doctor_register');
/////doctor login

Route::post('/dashboard', 'ApiController@doctor_login')->name('login');

///doctor get data users
Route::post('/doctor_get_datamom/', 'ApiController@doctor_get_userdata');


// Management
 Route::get('/', function () { return view('management.login'); });
//  Route::get('/login', 'ApiController@doctor_login');
 Route::get('/info/{user_id}', 'ApiController@viewInfo');
 Route::get('/dashboard', 'ApiController@doctor_login')->name('dashboard');
 Route::get('/logout', 'ApiController@doctor_logout');
 Route::post('hnnumber_save', 'ApiController@hnnumber_save');
 
 
 
 ///

Route::get('admin_edit_user','ApiController@list_user');
Route::get('edit_user/{id}','ApiController@show_edit');
Route::post('edit/{id}','ApiController@edit'); 
Route::delete('remove/{id}','ApiController@remove_user')->name('delete_user'); 
//  Route::get('/admin_edit', function () { return view('admin_edit_user'); });
// Route::get('message', function()
// {
//     return View::make('message');
// });

Route::get('/edit', function () { return view('management.login_edit'); });
