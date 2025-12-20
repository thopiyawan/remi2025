<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use DateTime;
use Carbon\Carbon;
use App\Models\pregnants as pregnants;
use App\Models\RecordOfPregnancy as RecordOfPregnancy;
use App\Models\sequents as sequents;
use App\Models\sequentsteps as sequentsteps;
use App\Models\users_register as users_register;
use App\Models\logmessage as logmessage;
use App\Models\tracker as tracker;
use App\Models\question as question;
use App\Models\quizstep as quizstep;
use App\Models\reward as reward;
use App\Models\presenting_gift as presenting_gift;
use App\Models\reward_gift as reward_gift;
use App\Models\foodmenu_img as foodmenu_img;
use App\Models\doctor as doctor;
use App\Models\personal_doctor_mom as personal_doctor_mom;
use App\Models\foodmenu as foodmenu;
use App\Models\meal_planing as meal_planing;
use App\Models\birth_date as birth_date;
use App\Models\blood_sugar as blood_sugar;
use App\Models\fetal_movement as fetal_movement;
use App\Models\tracker_activity as tracker_activity;

use Illuminate\Support\Facades\DB;

use App\Http\Controllers\checkmessageController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\SqlController;
use App\Http\Controllers\CalController;


class SqlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function users_register_select($user){
        $users_register = users_register::where('user_id',$user)
                                        ->whereNull('deleted_at')
                                        ->first();
        return $users_register;
    }
       public function check_w_status($user){
        $compli_diabete = users_register::where('user_id',$user)
                                        ->whereNull('deleted_at')
                                        ->where('compli_diabete','1')
                                        ->count();
        $compli_hypertension = users_register::where('user_id',$user)
                                        ->whereNull('deleted_at')
                                        ->where('compli_hypertension','1')
                                        ->count();
        $compli_preterm_birth = users_register::where('user_id',$user)
                                        ->whereNull('deleted_at')
                                        ->where('compli_preterm_birth','1')
                                        ->count();
        $count = $compli_diabete + $compli_hypertension + $compli_preterm_birth;
        return $count;

    }
      public function user_update($user,$answer,$update)
    {          

         switch($update) {
                 case 1 : 
                        $users_register = users_register::where('user_id', $user)
                                                          ->whereNull('deleted_at')
                                                          ->update(['user_name' => $answer ]);
                    break;
                 case 2 : 
                        $users_register = users_register::where('user_id', $user)
                                                          ->whereNull('deleted_at')
                                                          ->update(['user_age' => $answer ]);
                    break;
                 case 3 : 
                        $users_register = users_register::where('user_id', $user)
                                                          ->whereNull('deleted_at')
                                                          ->update(['user_height' => $answer ]);
                    break;
                 case 4 : 
                        $users_register = users_register::where('user_id', $user)
                                                          ->whereNull('deleted_at')
                                                          ->update(['user_Pre_weight' => $answer ]);
                    break;
                  case 5 : 
                        $users_register = users_register::where('user_id', $user)
                                                          ->whereNull('deleted_at')
                                                          ->update(['user_weight' => $answer ]);
                    break;
                 case 6 : 
                        $users_register = users_register::where('user_id', $user)
                                                          ->whereNull('deleted_at')
                                                          ->update(['preg_week' => $answer ]);
                    break;
                 case 7 : 
                        $users_register = users_register::where('user_id', $user)
                                                          ->whereNull('deleted_at')
                                                          ->update(['phone_number' => $answer ]);
                    break;
                 case 8 : 
                        $users_register = users_register::where('user_id', $user)
                                                          ->whereNull('deleted_at')
                                                          ->update(['email' => $answer ]);
                    break;
                 case 9 : 
                        $users_register = users_register::where('user_id', $user)
                                                          ->whereNull('deleted_at')
                                                          ->update(['hospital_name' => $answer ]);
                    break;
                 case 10 : 
                        $users_register = users_register::where('user_id', $user)
                                                          ->whereNull('deleted_at')
                                                          ->update(['hospital_number' => $answer ]);
                    break;
                 case 11 : 
                        $users_register = users_register::where('user_id', $user)
                                                          ->whereNull('deleted_at')
                                                          ->update(['history_medicine' => $answer ]);
                    break;
                 case 12 : 
                        $users_register = users_register::where('user_id', $user)
                                                          ->whereNull('deleted_at')
                                                          ->update(['history_food' => $answer ]);
                    break;
                 case 13 : 
                        $users_register = users_register::where('user_id', $user)
                                                          ->whereNull('deleted_at')
                                                          ->update(['active_lifestyle' => $answer ]);
                    break;
                  case 14 : 
                        $users_register = users_register::where('user_id', $user)
                                                          ->whereNull('deleted_at')
                                                          ->update(['status' => $answer ]);
                    break;
                  case 15 : 
                        $users_register = users_register::where('user_id', $user)
                                                          ->whereNull('deleted_at')
                                                          ->update(['ulife_connect' => $answer ]);
                    break;
////ภาวะแทรกซ้อน
                  case 16 : 
                        $users_register = users_register::where('user_id', $user)
                                                          ->whereNull('deleted_at')
                                                          ->update(['compli_diabete' => $answer ]);
                  break;
                  case 17 : 
                        $users_register = users_register::where('user_id', $user)
                                                          ->whereNull('deleted_at')
                                                          ->update(['compli_hypertension' => $answer ]);
                    break;
                  case 18 : 
                        $users_register = users_register::where('user_id', $user)
                                                          ->whereNull('deleted_at')
                                                          ->update(['compli_preterm_birth' => $answer ]);
                    break;
                  case 19 : 
                        $users_register = users_register::where('user_id', $user)
                                                          ->whereNull('deleted_at')
                                                          ->update(['weight_status' => $answer ]);
                    break;
                  case 20 : 
                        $users_register = users_register::where('user_id', $user)
                                                          ->whereNull('deleted_at')
                                                          ->update(['preg_week_str' => $answer ]);
                    break;
                  case 21 : 
                        $users_register = users_register::where('user_id', $user)
                                                          ->whereNull('deleted_at')
                                                          ->update(['type_preg_week' => $answer ]);
                    break;
                  case 22 : 
                        $users_register = users_register::where('user_id', $user)
                                                          ->whereNull('deleted_at')
                                                          ->update(['calorie' => $answer ]);
                    break;
             
             }

             return $users_register;
        
    }

     public function delete_data_all($user)
    {          
        
         //$users_register = users_register::where('user_id', $user)->delete();
        // $RecordOfPregnancy = RecordOfPregnancy::where('user_id', $user)->delete();
         (new ApiController)->api_delete($user);
         $users_register = users_register::where('user_id', $user)
                       ->update(['deleted_at'=>NOW()]);
         $RecordOfPregnancy = RecordOfPregnancy::where('user_id', $user)
                       ->update(['deleted_at'=>NOW()]);
         $tracker = tracker::where('user_id', $user)
                       ->update(['deleted_at'=>NOW()]);
         $birth_date = birth_date::where('user_id', $user)
                       ->update(['deleted_at'=>NOW()]);
         $blood_sugar = blood_sugar::where('user_id', $user)
                       ->update(['deleted_at'=>NOW()]);
         $fetal_movement = fetal_movement::where('user_id', $user)
                       ->update(['deleted_at'=>NOW()]);
         $personal_doctor_mom = personal_doctor_mom::where('user_id', $user)
                       ->update(['deleted_at'=>NOW()]);  
         $tracker_activity = tracker_activity::where('user_id', $user)
                       ->update(['deleted_at'=>NOW()]);            

         $sequentsteps = sequentsteps::where('sender_id', $user)->delete();
         //$tracker = tracker::where('user_id', $user)
         //               ->update(['deleted_status'=>'0']);

    }
     public function  pregnants($preg_week){
        $pregnants = pregnants::where('week', $preg_week)->first();
        return $pregnants;

    }
     public function sequents_question($seqcode)
    {          
                   $question = sequents::select('question')
                                ->where('seqcode',$seqcode)
                                ->first();
                   return $question->question;
    }
     public function sequentsteps_seqcode($user)
    {          
       $sequentsteps = sequentsteps::select('seqcode','nextseqcode','answer')
                                ->where('sender_id',$user)
                                ->first();
                   return $sequentsteps;
    }
     public function sequentsteps_insert($user,$seqcode,$nextseqcode)
    {          
      $sequentsteps = sequentsteps::insert(['sender_id'=>$user,'seqcode' => $seqcode,'answer' => 'NULL','nextseqcode' =>$nextseqcode,'status'=>'0','created_at'=>NOW() , 'updated_at'=>NOW()]);
    }
     public function sequentsteps_update($user,$seqcode,$nextseqcode)
    {          
      $sequentsteps = sequentsteps::where('sender_id', $user)
                                      ->update(['seqcode' =>$seqcode,'nextseqcode' => $nextseqcode]);
    }
     public function update_datepreg($strDate1,$user){

         $users_register = users_register::where('user_id', $user)
                       ->whereNull('deleted_at')
                       ->update(['date_preg'=>$strDate1]);  
         return $users_register;

    }
     public function update_dateofbirth($dateofbirth,$user){

         $users_register = users_register::where('user_id', $user)
                       ->update(['dateofbirth'=>$dateofbirth]);  

    }
     public function sequentsteps_update2($user,$answer)
    {          
         $sequentsteps = sequentsteps::where('sender_id', $user)
                       ->update(['answer'=>$answer]);
    }
     public function user_insert($user,$user_name)
    {          
         $users_register = users_register::insert(['user_id'=>$user,'user_name' => $user_name ,'status' => '4','user_age'=>'0','user_height'=>'0','user_Pre_weight'=>'0','user_weight'=>'0','preg_week'=>'0', 'phone_number'=>'NULL','email' =>'NULL','hospital_name'=>'NULL','hospital_num'=>'NULL','history_medicine'=>'NULL', 'history_food'=>'NULL','active_lifestyle'=>'0','created_at'=>NOW(),'updated_at' =>NOW(),'date_preg'=>'NULL','dateofbirth'=>'NULL','ulife_connect'=>'0','compli_diabete'=>'0','compli_hypertension'=>'0','compli_preterm_birth'=>'0']);
    }
     public function tracker_insert1($user,$tracker)
    {          
          $tracker = tracker::insert(['user_id'=>$user,'breakfast' => $tracker,'lunch' => 'NULL','dinner' => 'NULL','dessert_lu' => 'NULL' ,'dessert_din' => 'NULL' ,'exercise' => 'NULL','vitamin'=>'NULL','created_at'=>NOW(),'updated_at' =>NOW(),'data_to_ulife'=>'0']);
    }
     public function tracker_update($user,$column,$tracker)
    {          

         $tracker_update = tracker::where('user_id',$user)
                                        ->whereNull('deleted_at')
                                        ->orderBy('created_at', 'desc')
                                        ->first();
         $created_at   =   $tracker_update ->created_at;                         
         $tracker_update = tracker::where('user_id', $user)
                       ->where('created_at', $created_at)
                       ->update([ $column =>$tracker]);

        
         return $tracker_update;
    }
     public function RecordOfPregnancy_insert($preg_week, $user_weight,$user,$weight_status){
     $RecordOfPregnancy = RecordOfPregnancy::insert(['user_id'=>$user,'preg_week' => $preg_week,'preg_weight' => $user_weight, 'created_at'=>NOW(),'updated_at' =>NOW(),'data_to_ulife'=>'0' ,'weight_status'=>$weight_status]);
    }
     public function RecordOfPregnancy_update($user_weight,$user,$date){

     $RecordOfPregnancy = RecordOfPregnancy::where('user_id', $user)
                       ->where('preg_week', $date)
                       ->update(['preg_weight' =>$user_weight]);
    }
     public function RecordOfPregnancy_select($user){

     $RecordOfPregnancy = RecordOfPregnancy::where('user_id', $user)
                       ->whereNull('deleted_at')
                       ->orderBy('updated_at', 'desc')
                       ->first();
     return $RecordOfPregnancy;
    }

     public function log_message($user, $Message,$message_type)
    {          
         $sequentsteps = logmessage::insert(['sender_id'=>$user,'receiver_id'=>'remi','message_type'=>$message_type,'message' =>$Message ,'created_at'=>NOW()]);
    }
     public function log_message_doctor_to_mom($doctor_id,$user,$Message,$message_type)
    {          
         $sequentsteps = logmessage::insert(['sender_id'=>$doctor_id,'receiver_id'=>$user,'message_type'=>$message_type,'message' =>$Message ,'created_at'=>NOW()]);
    }
     public function log_message_bot_to_mom($user,$Message,$message_type)
    {          
         $sequentsteps = logmessage::insert(['sender_id'=>'remi','receiver_id'=>$user,'message_type'=>$message_type,'message' =>$Message ,'created_at'=>NOW()]);
    }
       public function tracker_cal($user)
    {          
         $record = tracker::where('user_id',$user)
                               ->whereNull('deleted_at')
                               ->orderBy('created_at', 'DESC')
                               ->first();
          return $record;
    }

    public function select_question($date){
        $select_question = question::where('date_question',$date)
                                        ->first();
        return $select_question;

    }
    public function insert_reward($user){
        $reward = reward::insert(['user_id'=>$user,'question_num' => $question_num,'question_ans' => $question_ans,'code_quiz'=>$code_quiz,'point' => $point,'feq_ans_week' => $feq_ans_week,'answer_status' => $answer_status,'created_at'=>NOW(),'updated_at' =>NOW()]);
        return $reward;

    }
    public function insert_quizstep($user,$code_quiz,$question_num,$answer_status){
        $quizstep = quizstep::insert(['user_id'=>$user,'code_quiz'=>$code_quiz,'question_num' => $question_num,'answer_status' => $answer_status,'created_at'=>NOW(),'updated_at' =>NOW()]);
        return $quizstep;

    }
    public function select_quizstep($user,$code_quiz,$question_num){
        $select_quizstep = quizstep::where('user_id',$user)
                                        ->where('code_quiz',$code_quiz)
                                        ->where('question_num',$question_num)
                                        ->first();
        return $select_quizstep;
    }
    public function select_quizstep_user($user){
        $select_quizstep1 = quizstep::where('user_id',$user)
                                        ->orderBy('question_num', 'DESC')
                                        ->first();
        return $select_quizstep1;
    }
    public function select_question_user($code_quiz1,$question_num1){
        $question2 = question::where('code_quiz',$code_quiz1)
                              ->where('question_num',$question_num1)
                              ->first();
        return $question2;
    }   
    public function quizstep_update($user,$question_ans,$answer_status,$correct_ans,$code_quiz1,$question_num1)
    {          
         $quizstep_up = quizstep::where('user_id', $user)
                       ->where('code_quiz', $code_quiz1)
                       ->where('question_num', $question_num1)
                       ->update(['question_ans'=>$question_ans,'answer_status'=>$answer_status,'correct_ans'=>$correct_ans]);
        return $quizstep_up ;
    }
    public function ins_reward($user,$code_quiz1,$point,$feq_ans_week){
        $insert_reward = reward::insert(['user_id'=>$user,'code_quiz'=>$code_quiz1,'point' => $point,'feq_ans_week' => $feq_ans_week,'created_at'=>NOW(),'updated_at' =>NOW()]);
        return $insert_reward;

    }
    public function update_reward($user,$code_quiz1,$point,$feq_ans_week){
            $reward_up = reward::where('user_id', $user)
                       ->where('code_quiz', $code_quiz1)
                       ->update(['point'=>$point,'feq_ans_week'=>$feq_ans_week]);
        return $reward_up ;

    }  
    public function reward_select($user,$code_quiz1){
        $reward1 = reward::where('user_id',$user)
                                ->where('code_quiz', $code_quiz1)
                                ->first();
        return $reward1;
    }  
    public function ins_reward1($user,$point,$feq_ans_week,$feq_ans_meals){
        $insert_reward1 = reward::insert(['user_id'=>$user,'point' => $point,'feq_ans_week' => $feq_ans_week,'feq_ans_meals'=>$feq_ans_meals,'created_at'=>NOW(),'updated_at' =>NOW()]);
        return $insert_reward1;

    }
    public function update_reward1($user,$point,$feq_ans_week,$feq_ans_meals){
            $reward_up1 = reward::where('user_id', $user)
                       ->update(['point'=>$point,'feq_ans_week'=>$feq_ans_week,'feq_ans_meals'=>$feq_ans_meals]);
        return $reward_up1 ;

    }  
    public function reward_select1($user){
        $reward1 = reward::where('user_id',$user)
                                ->first();
        return $reward1;
    }
    public function reward_count($user){
        $reward_count = reward::where('user_id',$user)
                                ->count();
        return $reward_count;
    }
    public function reward_gift(){
        // $reward_gift = reward_gift::get();
     
        $reward_gift = reward_gift::
                      whereRaw('? between date_start and date_exp', [date('Y-m-d')])
                      ->get()
                      ->toArray();
        return $reward_gift;
    }
    public function ins_presenting_gift($user,$code_gift,$presenting_status){
      /////เพิ่มวันหมดอายุ
        $insert_reward1 = presenting_gift::insert(['user_id'=>$user,'code_gift' => $code_gift,'presenting_status' => $presenting_status,'created_at'=>NOW(),'updated_at' =>NOW()]);
        return $insert_reward1;

    } 
    public function update_reward1_point($user,$point){
            $reward_up1 = reward::where('user_id', $user)
                       ->update(['point'=>$point]);
        return $reward_up1 ;

    } 
    public function reward_gift2($code_gift){
        $reward1 = reward_gift::where('code_gift',$code_gift)
                                ->first();
        return $reward1;
    }  
    public function presenting_gift_select($user){
        $presenting_gift =  presenting_gift::where('presenting_gift.user_id',$user)
                                           -> where('presenting_gift.presenting_status','1')
                                           ->join('reward_gift', 'reward_gift.code_gift', '=', 'presenting_gift.code_gift')
                                           ->select('reward_gift.code_gift', 'reward_gift.name_gift') 
                                           ->get();


       
        return  $presenting_gift;
    }
    public function update_presenting_gift($user,$presenting_status,$code_gift){
            $reward_up1 = presenting_gift::where('user_id', $user)
                       ->where('code_gift', $code_gift)
                       ->update(['presenting_status'=>$presenting_status]);
        return $reward_up1 ;

    } 
    public function presenting_gift_count($user){
      
        $presenting_gift1 =  presenting_gift::where('user_id',$user)
                                           ->where('presenting_status',1)
                                           ->distinct('code_gift')->count('code_gift');
        return  $presenting_gift1;

    } 
    public function reward_gift_count($code_gift){
        $reward1 = reward_gift::where('code_gift',$code_gift)
                                ->count();
        return $reward1;
    } 
    public function presenting_gift_group($user){
           $data = presenting_gift::where('presenting_gift.presenting_status',1)
                 ->where('presenting_gift.user_id',$user)
                 ->join('reward_gift', 'reward_gift.code_gift', '=', 'presenting_gift.code_gift')
                 ->select('reward_gift.name_gift','presenting_gift.code_gift', DB::raw('count(*) as total'))
                 ->groupBy('presenting_gift.code_gift')
                 ->get()
                 ->toArray();

      return $data;

    } 
    public function presenting_gift_check($user,$code_gift){
      
        $presenting_gift1 =  presenting_gift::where('user_id',$user)
                                           ->where('presenting_status',1)
                                           ->where('code_gift',$code_gift)
                                           ->distinct('code_gift')->count('code_gift');
        return  $presenting_gift1;

    } 
    public function  foodmenu_img(){
      
        $foodmenu_img = foodmenu_img::inRandomOrder()->get();
        return  $foodmenu_img;

    } 
    public function  doctor_sel(){
      
        $doctor =  doctor::get();
        return  $doctor;

    } 
    public function  personal_doctor_mom($user_id,$doctor_id){
      
        $personal_doctor_mom =  personal_doctor_mom::insert(['user_id'=>$user_id,'doctor_id' => $doctor_id,'created_at'=>NOW()]);
        return  $personal_doctor_mom;

    } 
    public function  personal_doctor_select($doctor_id){
      
         $doctor =  doctor::where('doctor_id',$doctor_id)
                           ->first();
        return  $doctor;


    }
     public function  personal_doctor_mom_count($user_id){
      
        $personal_doctor_mom =  personal_doctor_mom::where('user_id',$user_id)
                                                    ->count('user_id');
        return  $personal_doctor_mom;

    } 
     public function  personal_doctor_mom_update($user_id){
      
        $personal_doctor_mom =  personal_doctor_mom::where('user_id', $user_id)
                                                          ->whereNull('deleted_at')
                                                          ->update(['deleted_at' => NOW() ]);
        return  $personal_doctor_mom;

    } 
    // public function  reward_gift2(){
    //     $pregnants = pregnants::where('week', $preg_week)->get();
    //     return $pregnants;

    //}
     public function  foodmenu($type){
        $foodmenu = foodmenu::where('type', $type)
                             ->inRandomOrder()
                             ->take(6)
                             ->get();
        return $foodmenu;

    }
     public function  foodmenu_select($food_id){
        $foodmenu = foodmenu::where('id', $food_id)
                             ->first();
        return $foodmenu;

    }
     public function  meal_planing($cal){
                 if ($cal <= 1600) {
                        $Nutrition = 1600;
                    } elseif ($cal >= 1601 && $cal <= 1700) {
                            $Nutrition =  1700;
                    }elseif ($cal >=1701 && $cal <=1800) {
                            $Nutrition =  1800;
                    }elseif ($cal >=1801 && $cal<=1900) {
                            $Nutrition =  1900;
                    }elseif ($cal >=1901 && $cal<=2000) {
                            $Nutrition =  2000;
                    }elseif ($cal >=2001 && $cal<=2100 ) {
                            $Nutrition =  2100;
                    }elseif ($cal >= 2101 && $cal<=2200) {
                            $Nutrition =  2200;
                    }elseif ($cal >= 2201 && $cal <= 2300) {
                            $Nutrition =  2300;
                    }elseif ($cal >= 2301 && $cal <=2400) {
                            $Nutrition =  2400;
                    }elseif ($cal >= 2401 && $cal <= 2500) {
                            $Nutrition =  2500;
                    }else {
                            $Nutrition =  2600;
                    }
                   
       $cal = meal_planing::where('caloric_level', $Nutrition)
                             ->first();
        return $cal;

    }
      public function  doctor_select_login($doctor_id,$password){
      
        $doctor =  doctor::where('doctor_id',$doctor_id)
                           ->where('password',$password)
                           ->first();
        return  $doctor;


    }
     public function  doctor_select_mom($doctor_id) {
      
       $data_mom = personal_doctor_mom::join('users_register', 'users_register.user_id', '=', 'personal_doctor_mom.user_id')
                  ->select('users_register.*')
                  ->where('personal_doctor_mom.doctor_id', '=', $doctor_id)
                  ->whereNull('users_register.deleted_at')
                  ->whereNull('personal_doctor_mom.deleted_at')
                  ->get();
       return $data_mom;


    }
     public function  pregnants_list($preg_week){
        $pregnants_list = pregnants::where('week', '>=', $preg_week)
                             ->take(8)
                             ->get();
        return $pregnants_list;
    }
   public function  get_log_message_mom($user){
      
        $logmessage =  logmessage::where('receiver_id',$user)
                                 ->orWhere('sender_id',$user)
                                 ->get();
        return  $logmessage;
    }
    public function  get_log($user,$message_type){
      
        $logmessage =  logmessage::where('receiver_id',$user)
                                 ->where('message_type',$message_type)
                                 ->get();
        return  $logmessage;
    }
    public function  get_tracker_mom($user){
      
    $record = tracker::where('user_id',$user)
                               ->whereNull('deleted_at')
                               ->get();
    return $record;
    }
    public function  get_weight_mom($user){
      
    $record =  RecordOfPregnancy::select('preg_week','preg_weight')
                     ->where('user_id', $user)
                     ->whereNull('deleted_at')
                     ->distinct()
                     ->orderBy('preg_week', 'asc')
                     ->get();
                     
                    //  tracker::where('user_id',$user)
                    //            ->whereNull('deleted_at')
                    //            ->get();
    return $record;
    }
    public function  doctor_sel_typuser($doctor_id){
      
        $doctor =  doctor::select('type_user')
                         ->where('doctor_id',$doctor_id)
                         ->first();
        return  $doctor;

    } 
    public function  update_due_date( $user,$due_date){
      
        $users_register = users_register::where('user_id', $user)
                                          ->whereNull('deleted_at')
                                          ->update(['due_date' => $due_date ]);
        return  $users_register;

    } 
    public function  update_hn( $user,$hospital_num){
      
        $hn = users_register::where('user_id', $user)
                              ->whereNull('deleted_at')
                              ->update(['hospital_num' => $hospital_num ]);
        return  $hn;
    }   
    public function  last_chat($user){
      
        // $usersTimezone = 'Asia/Bangkok';
        // $date = new ]DateTime( NOW(), new DateTimeZone($usersTimezone) );
      $current = Carbon::now('Asia/Bangkok');

        $last_chat = users_register::where('user_id', $user)
                              ->whereNull('deleted_at')
                              ->update(['created_at' =>$current]);
        return  $last_chat;
    }   
     public function  weight_status_all(){
      
        // $usersTimezone = 'Asia/Bangkok';
        // $date = new ]DateTime( NOW(), new DateTimeZone($usersTimezone) );
        $weight_status = users_register::whereNull('deleted_at')
                                    ->count();
        $weight_status1 = users_register::whereNull('deleted_at')
                                    ->where('weight_status','1')
                                    ->count();
        $weight_status2 = users_register::whereNull('deleted_at')
                                    ->where('weight_status','2')
                                    ->count();
        $weight_status3 = users_register::whereNull('deleted_at')
                                    ->where('weight_status','3')
                                    ->count();
        $weight_status4 = users_register::whereNull('deleted_at')
                                    ->where('weight_status','4')
                                    ->count();
        $weight_status0 = users_register::whereNull('deleted_at')
                                    ->where('weight_status','0')
                                    ->count();
        $weight_status_arr = [$weight_status1,  $weight_status2,  $weight_status3, $weight_status4, $weight_status0];
        return  $weight_status_arr;
    }   
    public function  update_weight_status($user,$weight_status){
      
        $update_weight_status = users_register::where('user_id', $user)
                              ->whereNull('deleted_at')
                              ->update(['weight_status' => $weight_status ]);
        return  $update_weight_status;
    }   
    public function select_weight_str($user_id,$preg_week_str){

     $RecordOfPregnancy = RecordOfPregnancy::select('preg_weight')
                       ->where('user_id', $user_id)
                       ->where('preg_week', $preg_week_str)
                       ->whereNull('deleted_at')
                       ->first();

     return $RecordOfPregnancy;
    }  
    public function tracker_count($user){
      
    $record = tracker::where('user_id',$user)
                               ->whereNull('deleted_at')
                               ->count();
    return $record;
    }
     public function  weight_status($doctor_id){
      
        // $usersTimezone = 'Asia/Bangkok';
        // $date = new ]DateTime( NOW(), new DateTimeZone($usersTimezone) );
                 $weight_status1 = personal_doctor_mom::where('personal_doctor_mom.doctor_id',$doctor_id)
                                           ->join('users_register','personal_doctor_mom.user_id','=','users_register.user_id')
                                           ->whereNull('personal_doctor_mom.deleted_at')
                                           ->whereNull('users_register.deleted_at')
                                           ->where('users_register.weight_status','1')
                                           ->count();
                 $weight_status2 = personal_doctor_mom::where('personal_doctor_mom.doctor_id',$doctor_id)
                                           ->join('users_register','personal_doctor_mom.user_id','=','users_register.user_id')
                                           ->whereNull('personal_doctor_mom.deleted_at')
                                           ->whereNull('users_register.deleted_at')
                                           ->where('users_register.weight_status','2')
                                           ->count();
                 $weight_status3 = personal_doctor_mom::where('personal_doctor_mom.doctor_id',$doctor_id)
                                           ->join('users_register','personal_doctor_mom.user_id','=','users_register.user_id')
                                           ->whereNull('personal_doctor_mom.deleted_at')
                                           ->whereNull('users_register.deleted_at')
                                           ->where('users_register.weight_status','3')
                                           ->count();
                  $weight_status4 = personal_doctor_mom::where('personal_doctor_mom.doctor_id',$doctor_id)
                                           ->join('users_register','personal_doctor_mom.user_id','=','users_register.user_id')
                                           ->whereNull('personal_doctor_mom.deleted_at')
                                           ->whereNull('users_register.deleted_at')
                                           ->where('users_register.weight_status','4')
                                           ->count();
                $weight_status0 = personal_doctor_mom::where('personal_doctor_mom.doctor_id',$doctor_id)
                                           ->join('users_register','personal_doctor_mom.user_id','=','users_register.user_id')
                                           ->whereNull('personal_doctor_mom.deleted_at')
                                           ->whereNull('users_register.deleted_at')
                                           ->where('users_register.weight_status','0')
                                           ->count();
      
        $weight_status_arr = [$weight_status1,  $weight_status2,  $weight_status3, $weight_status4, $weight_status0];
        return  $weight_status_arr;
    } 
      public function  weight_status_line($user){
      
        $weight_status = users_register::select('weight_status')
                                    ->whereNull('deleted_at')
                                    ->where('user_id',$user)
                                    ->first();
        return  $weight_status;
    }   
      public function RecordOfPregnancy_select_str($user){

     $RecordOfPregnancy = RecordOfPregnancy::where('user_id', $user)
                       ->whereNull('deleted_at')
                       ->orderBy('updated_at', 'asc')
                       ->count();


     return $RecordOfPregnancy;
    } 

    ///liff for gdm 
    public function insert_blood_sugar(Request $request){
        // dd( $request->all());
        // dd( $request->all());
    
        $user_id = $request->input('user_id'); 
        $meal = $request->input('meal'); 
        $time_of_day = $request->input('time_of_day');
        $datetime = date('Y-m-d H:i:s', strtotime($request->input('datetime')));
        $blood_sugar = $request->input('blood_sugar'); 

        $preg_week = users_register::select('preg_week')
                    ->whereNull('deleted_at')
                    ->where('user_id',$user_id)
                    ->first();

        $data = array(
            "user_id" => $user_id,
            "meal" => $meal,
            "time_of_day" => $time_of_day,
            "datetime" => $datetime,
            "blood_sugar" => $blood_sugar,
            "preg_week" => $preg_week->preg_week,
        );
        // dd($data);
        $this->validate(request(), [
            'meal' => 'required',
            'time_of_day' => 'required',
            'blood_sugar'=> 'required|numeric|regex:/^\d*(\.\d{1,4})?$/'
        ]);
    

        // DB::table('blood_sugar')->create($data);
        
        $blood_sugar = blood_sugar::create($data);

        return redirect()->back()->with('message', 'บันทึกเรียบร้อยค่ะ');
    }
    //get blood sugar
    public function blood_sugar_select($user){
        $blood_sugar = blood_sugar::where('user_id',$user)
                                        ->whereNull('deleted_at')
                                        ->orderBy('datetime', 'ASC')
                                        ->get();   
        return $blood_sugar;
    }

    public function tracker_activity($user){
        $strunit = Array(" ","ทัพพี","ช้อน","ช้อนโต๊ะ","ลูก","ฟอง","ตัว","มล.","ชิ้น");
        $strmeal = Array(" ","เช้า","กลางวัน","เย็น","ว่างเช้า","ว่างบ่าย");
        $strmeal = Array(" ","breakfast","lunch","dinner","dessert_lu","dessert_din");
        // $unit = $strunit[$tkact->unit];
        // $meal = $strmeal[$tkact->meal];
        $breakfast = tracker::join('tracker_activity','tracker.id','=','tracker_activity.food_id')
                            ->where('tracker.user_id',  $user)
                            ->where('meal',1)
                            ->whereNull('tracker_activity.deleted_at')
                            ->select('tracker.date','tracker.time_breakfast','tracker.breakfast','tracker_activity.meal','tracker_activity.food_name','tracker_activity.portion','tracker_activity.unit','tracker_activity.id')
                            ->groupBy(['breakfast','food_name'])
                            ->get()
                            ->groupBy(['breakfast'])
                            ->toArray();    
                            // ->pluck('tracker.time_breakfast','tracker_activity.food_name','tracker.breakfast');
                    
            

        dd($breakfast);
        return $blood_sugar;
    }

    public function test_sql($user){
        $user_name = "ทดสอบ";
        $case = 1;
        $seqcode = '0007';
        $nextseqcode = '0009';
        $userMessage  = (new SqlController)->sequents_question($seqcode);
        $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
        $user_insert = (new SqlController)->user_insert($user,$user_name);
        return response()->json([
         'message1' => $userMessage,
         'message2' => $sequentsteps_insert,
         'message3' => $user_insert 
       ]);
    }







}
