<?php

namespace App\Http\Controllers;

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

use App\Http\Controllers\checkmessageController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\SqlController;
use App\Http\Controllers\CalController;

class CalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    #การคำนวณความต้องการพลังงานและสารอาหาร (Energy and Nutrient Requirements)
    public function cal_calculator($user_age,$active_lifestyle,$user_Pre_weight,$preg_week) {

        #energy ตามอายุหญิงตั้งครรภ์
        if ( $user_age>=10 && $user_age<=18 ) {
          $cal=( 13.384*$user_Pre_weight )+692.6;
        }elseif ( $user_age>18 && $user_age<=30 ) {
          $cal=( 14.818*$user_Pre_weight )+486.6;
        }else{
          $cal=( 8.126*$user_Pre_weight )+845.6;
        }
        
        #การคำนวณระดับการออกกำลังกาย (Activity factor) 
        if ( $active_lifestyle == '3' ) {
          $total = $cal*2.0;
        }elseif ( $active_lifestyle == '2' ) {
          $total = $cal*1.7;
        }else{
          $total = $cal*1.4;
        } 

        #การเพิ่มพลังงานที่ต้องการในไตรมาสของการตั้งครรภ์
        if ( $preg_week > 13 && $preg_week <= 40 ) {
          #/100 เพื่อทำเป็นทศนิยม แล้ว ปัดให้เป็นจำนวนเต็ม เช่น 2123.34 เป็น 2100 ง่ายสำหรับ query แคลอรี่ ใน database 
          $cal1 = ($total+300)/100;
          $cal = number_format($cal1)*100;  
        }else{   
          $total = $total/100;
          $cal = number_format($total)*100;
        } 
        return  $cal;
    }

    #เกณฑ์น้ำหนัก
    public function weight_criteria($bmi) {

        if ($bmi<18.5) {
          $result="น้ำหนักน้อย";
        } elseif ($bmi>=18.5 && $bmi<=22.9) {
          $result="น้ำหนักปกติ";
        } elseif ($bmi>=23.0 && $bmi<=29.9) {
          $result="น้ำหนักเกิน";
        } elseif ($bmi>=30.0) {
          $result="อ้วน";
        }                 
       return $result;
    }
    #การขึ้นของน้ำหนักที่เหมาะสม
     public function weight_criteria_status($bmi,$user,$weight_cur) {
                  
        $users_register = (new SqlController)->users_register_select($user);
        $user_id = $users_register->user_id;
        $preg_week = $users_register->preg_week;
        $preg_week_str = $users_register->preg_week_str;
        $user_weight = $users_register->user_weight;
        $user_Pre_weight = $users_register->user_Pre_weight;
        $user_height =  $users_register->user_height;
        $bmi  = (new CalController)->bmi_calculator($user_Pre_weight,$user_height);
        $weight_str = (new SqlController)->select_weight_str($user_id,$preg_week_str);
        $preg_weight = $weight_str->preg_weight;        
         
        if ($bmi < 18.5) {
          $bmi=1;
        } elseif ($bmi >= 18.5 && $bmi <= 22.9) {
          $bmi=2;
        } elseif ($bmi >= 23.0 && $bmi <= 29.9) {
          $bmi=3;
        } elseif ($bmi >= 30.0) {
          $bmi=4;
        }                    
        #ผลต่างของน้ำหนัก         
        $diff_weight = $weight_cur - $preg_weight;
        #ผลต่างของอายุครรภ์
        $diff_week = $preg_week - $preg_week_str;
        // dd( $diff_weight .','.$diff_week);

        #เทียบเกณฑ์ BMI 1 ต่ำกว่าเกณฑ์ 2 น้ำหนักปกติ 3 เกินเกรฑ์ 4 อ้วน
         switch ($bmi) {
              case 1:

                    #0.45 มาจากการอัตราการเพิ่มน้ำหนักต่อเดือน (1.8 - 2.3) แล้ว 1.8/4 เพื่อทำเป็นสัปดาห์
                    $a = 0.45*$diff_week;
                    $b = 0.575*$diff_week;

                    // dd($diff_weight.','.$a.','.$b);        
                     if(($diff_weight >= $a && $diff_weight <= $b)){
                          #เกณฑ์ปกติ
                          $result=1;
                     }elseif($diff_weight < $a){
                          #ต่ำกว่าเกณฑ์
                          $result=2;
                     }else{
                          #เกินเกณฑ์
                          $result=3;       
                     }
             
                  break;
              case 2:
                    $a = 0.35*$diff_week;
                    $b = 0.5*$diff_week;
                    // dd($diff_weight.','.$a.','.$b);        
                     if(($diff_weight >= $a && $diff_weight <= $b)){
                          $result=1;
                     }elseif($diff_weight < $a){
                          $result=2;
                     }else{
                          $result=3;       
                     }

                  break;
              case 3:

                    $a = 0.225*$diff_week;
                    $b = 0.325*$diff_week;
                    // dd($diff_weight.','.$a.','.$b);        
                     if(($diff_weight >= $a && $diff_weight <= $b)){
                          $result=1;
                     }elseif($diff_weight < $a){
                          $result=2;
                     }else{
                          $result=3;       
                     }
             
                  break;
              case 4:
                    $a = 0.15*$diff_week;
                    $b = 0.275*$diff_week;
                    // dd($diff_weight.','.$a.','.$b);        
                     if(($diff_weight >= $a && $diff_weight <= $b)){
                          $result=1;
                     }elseif($diff_weight < $a){
                          $result=2;
                     }else{
                          $result=3;       
                     }
                  break;
              default:
                    $result=0;
          }
             //dd($diff_weight.','.$a.','.$b.','.$result);
             $weight_status = $result;
             $up = (new SqlController)->update_weight_status($user,$weight_status);
             // $RecordOfPregnancy = RecordOfPregnancy::where('user_id', $user)
             //              ->where('preg_week',$preg_week)
             //              ->update(['preg_weight' =>$weight_cur,'preg_week' =>$preg_week,'weight_status'=>$weight_status]);

            return $result;
    }

    public function bmi_calculator($user_Pre_weight,$user_height){
            $height = $user_height*0.01;
            $bmi = $user_Pre_weight/($height*$height);
            $bmi = number_format($bmi, 2, '.', '');
            return $bmi;
    }

    public function expdate($startdate,$datenum){
            $startdatec=strtotime($startdate); // ทำให้ข้อความเป็นวินาที
            $tod=$datenum*86400; // รับจำนวนวันมาคูณกับวินาทีต่อวัน
            $ndate=$startdatec-$tod; // นับบวกไปอีกตามจำนวนวันที่รับมา
            return $ndate; // ส่งค่ากลับ
    }

    public function pregnancy_calculator($user,$userMessage,$seqcode)
    {          

      if(strpos($userMessage, '/') !== false ){
        $pieces = explode("/", $userMessage);
        $date   = str_replace("","",$pieces[0]);
        $month  = str_replace("","",$pieces[1]);
        $today_years= date("Y") ;
        $today_month= date("m") ;
        $today_day  = date("d") ;

      if(is_numeric($month) == false){
          $month = (new checkmessageController)->check_month($month);
        }
      }elseif(strpos($userMessage, ':') !== false ){
        $pieces = explode(":", $userMessage);
        $date   = str_replace("","",$pieces[0]);
        $month  = str_replace("","",$pieces[1]);
        $today_years= date("Y") ;
        $today_month= date("m") ;
        $today_day  = date("d") ;
      if(is_numeric($month) == false){
          $month = (new checkmessageController)->check_month($month);
        }
      }elseif(strpos($userMessage, '-') !== false ){
        $pieces = explode("-", $userMessage);
        $date   = str_replace("","",$pieces[0]);
        $month  = str_replace("","",$pieces[1]);
        $today_years= date("Y") ;
        $today_month= date("m") ;
        $today_day  = date("d") ;

        if(is_numeric($month) == false){
          $month = (new checkmessageController)->check_month($month);
        }
      }elseif(strpos($userMessage, ' ') !== false ){
        $pieces = explode(" ", $userMessage);
        $date   = str_replace("","",$pieces[0]);
        $month  = str_replace("","",$pieces[1]);
        $today_years= date("Y") ;
        $today_month= date("m") ;
        $today_day  = date("d") ;

        if(is_numeric($month) == false){
          $month = (new checkmessageController)->check_month($month);
           if($month=='00'){
            $textReplyMessage = 'ฉันคิดว่าคุณพิมพ์เดือนผิดนะ';
            return   $textReplyMessage;
           }
        }
        
      }else{
          $textReplyMessage = 'ฉันคิดว่าคุณพิมพ์ผิดตามรูปแบบที่กำหนดนะคะ ตัวอย่างการพิมพ์เช่น 12 มกราคม หรือ 13:03 ค่ะ ลองพิมพ์ใหม่นะคะ';
          return   $textReplyMessage;
      }
    
       
      if($month> '12'|| $month<'1' ){
            $textReplyMessage = 'ฉันคิดว่าคุณพิมพ์เดือนผิดนะ';
            return   $textReplyMessage;
      }


      $day = (new checkmessageController)->check_day($date ,$month);

                  
        switch($seqcode) {
     
                 case ($seqcode=='1015' || $seqcode =='10640'): 

                            if($month>$today_month && $month<=12 && $date<=31 && $day=='' ){
                                        $years = $today_years-1;
                                        $strDate1 = $years."-".$month."-".$date;
                                        $strDate2=date("Y-m-d");
                                        
                                        $date_pre =  (strtotime($strDate2) - strtotime($strDate1))/( 60 * 60 * 24 );
                                        $week = $date_pre/7;
                                        $w_preg = number_format($week);
                                        $day = $date_pre%7;
                                        $day_preg = number_format($day);
                                        $due_date = 40-$w_preg;
                                        $due_date = date("Y-m-d",strtotime($due_date." weeks")); 

                                        if($w_preg>41 || $w_preg < 1){
                                              $textReplyMessage = 'ฉันคิดว่าอายุครรภ์ของคุณผิดนะ';
                                              return   $textReplyMessage;
                                        }
                                        $age_pre = 'คุณมีอายุครรภ์'.$w_preg .'สัปดาห์'.  $day_preg .'วัน' ;
                                        (new SqlController)->sequentsteps_update2($user,$w_preg); 
                                        (new SqlController)->update_datepreg($strDate1,$user);
                                         ///กำหนดการคลอด                      
                                        $due_date = (new SqlController)->update_due_date( $user,$due_date);
                                        ////////////////
                                        return  $age_pre;  
                                           

                                    }elseif($month<=$today_month && $month<=12 && $date<=31 && $day==''){
                                        $strDate1 = $today_years."-".$month."-".$date;
                                        $strDate2=date("Y-m-d");
                                        $date_pre =  (strtotime($strDate2) - strtotime($strDate1))/( 60 * 60 * 24 );;
                                        $week = $date_pre/7;
                                        $w_preg = number_format($week);
                                        $day = $date_pre%7;
                                        $day_preg = number_format($day);
                                        $due_date = 40-$w_preg;
                                        $due_date = date("Y-m-d",strtotime($due_date." weeks")); 

                                        if($w_preg>41 || $w_preg < 1){
                                              $textReplyMessage = 'ฉันคิดว่าอายุครรภ์ของคุณผิดนะ';
                                              return   $textReplyMessage;
                                        }
                                        $age_pre = 'คุณมีอายุครรภ์'. $w_preg .'สัปดาห์'.  $day_preg .'วัน' ;
                                        (new SqlController)->sequentsteps_update2($user,$w_preg);
                                        (new SqlController)->update_datepreg($strDate1,$user); 
                                       ///กำหนดการคลอด                      
                                        $due_date = (new SqlController)->update_due_date( $user,$due_date);
                                        ////////////////
                                        return  $age_pre;    
                                    }else{

                                        // $textReplyMessage = 'ดูเหมือนคุณจะพิมพ์วันที่ผิดนะ';
                                        return   $day ;

                                    }
  
                    break;
                 case ($seqcode=='2015'|| $seqcode=='20640')  : 
                        
                         if( $month < $today_month && $month<=12 && $date<=31 && $day==''){
                                 $years = $today_years+1;
                                 $strDate1 = $years."-".$month."-".$date;
                                 $strDate2=date("Y-m-d");
                                
                                 $date_pre =  (strtotime($strDate1) - strtotime($strDate2))/( 60 * 60 * 24 );
                                 $week = $date_pre/7;
                                 $week_preg =floor($week);
                                 $day = $date_pre%7;
                                 $day_preg = number_format($day);
                                 $w_preg = 39-$week_preg  ;
                                 $d = 7-$day_preg;

                                 $due_date = date("Y-m-d",strtotime($strDate1)); 


                         switch ($d){
                         case '7':
                              $w_preg = $w_preg + 1;
                                if($w_preg>41 || $w_preg < 1){
                                              $textReplyMessage = 'ฉันคิดว่าอายุครรภ์ของคุณผิดนะ';
                                              return   $textReplyMessage;
                                        }
                              $replyData = 'คุณมีอายุครรภ์'.  $w_preg  .'สัปดาห์';
                              (new SqlController)->sequentsteps_update2($user,$w_preg);

                                    $dr=$this->expdate($strDate1,266); 
                                    $df=date("Y-m-d",$dr); 
                                    (new SqlController)->update_datepreg($df,$user); 
                            ///กำหนดการคลอด                      
                            $due_date = (new SqlController)->update_due_date($user,$due_date);
                            ////////////////

                              return  $replyData;
                          break;
                         default:
                                if($w_preg>41 || $w_preg < 1){
                                              $textReplyMessage = 'ฉันคิดว่าอายุครรภ์ของคุณผิดนะ';
                                              return   $textReplyMessage;
                                        }
                              $replyData = 'คุณมีอายุครรภ์'. $w_preg .'สัปดาห์'.  $d .'วัน' ;
                              (new SqlController)->sequentsteps_update2($user,$w_preg);
                              $dr=$this->expdate($strDate1,266); 
                              $df=date("Y-m-d",$dr);
                              (new SqlController)->update_datepreg($df,$user); 
                                   ///กำหนดการคลอด                      
                                  $due_date = (new SqlController)->update_due_date($user,$due_date);
                                  ////////////////
                              return  $replyData;
                          break;
                          }

                         }elseif($month >= $today_month && $month<=12 && $date<=31){
                                 $years = $today_years;
                                 $strDate1 = $years."-".$month."-".$date;
                                 $strDate2=date("Y-m-d");
                                
                                 $date_pre =  (strtotime($strDate1) - strtotime($strDate2))/( 60 * 60 * 24 );
                                 $week = $date_pre/7;
                                 $week_preg =floor($week);
                                 $day = $date_pre%7;
                                 $day_preg = number_format($day);
                                 $w_preg = 39-$week_preg  ;
                                 $d = 7-$day_preg;
                                 $due_date = date("Y-m-d",strtotime($strDate1)); 
                      
                          switch ($d){
                               case '7':
                                 if($w_preg>41 || $w_preg < 1){
                                              $textReplyMessage = 'ฉันคิดว่าอายุครรภ์ของคุณผิดนะ';
                                              return   $textReplyMessage;
                                        }
                                  $w_preg = $w_preg + 1;
                                  $replyData ='คุณมีอายุครรภ์'.  $w_preg  .'สัปดาห์';
                                  (new SqlController)->sequentsteps_update2($user,$w_preg);
                                  $dr=$this->expdate($strDate1,266); 
                                  $df=date("Y-m-d",$dr);
                                  (new SqlController)->update_datepreg($df,$user); 
                                  ///กำหนดการคลอด                      
                                  $due_date = (new SqlController)->update_due_date($user,$due_date);
                                  ////////////////

                                  return  $replyData;
                                 break;
                               default:
                                 if($w_preg>41 || $w_preg < 1){
                                              $textReplyMessage = 'ฉันคิดว่าอายุครรภ์ของคุณผิดนะ';
                                              return   $textReplyMessage;
                                        }
                                  $replyData = 'คุณมีอายุครรภ์'. $w_preg .'สัปดาห์'.  $d .'วัน' ;
                                  (new SqlController)->sequentsteps_update2($user,$w_preg);
                                  $dr=$this->expdate($strDate1,266); 
                                  $df=date("Y-m-d",$dr);
                                  (new SqlController)->update_datepreg($df,$user); 
                                  ///กำหนดการคลอด                      
                                  $due_date = (new SqlController)->update_due_date($user,$due_date);
                                  ////////////////
                                 return  $replyData;
                                 break;
                           }
                         }else{

                                        // $textReplyMessage = 'ดูเหมือนคุณจะพิมพ์ไม่ถูกต้อง';
                                        // return   $textReplyMessage;
                                  return   $day ;

                                    }
               
                     break;
                   
      }

    }
    public function cal_food($tracker){
                                 $d = explode(" ",$tracker);
                                    $u = [];
                                    $da= [];

                                      $json1 = file_get_contents('calfood.json');
                                      $json= json_decode($json1);
                                                foreach($json->data as $item)
                                                  {
                                                    
                                                    foreach($d as $item1)
                                                      if(strpos( $item1, $item->id ) !== false )
                                                      {

                                                        $da[]= $item->content;
                                                        $u[] = $item->cal;
                                                        // $sum =  array_sum($u);
                                                                                
                                                      }    
                                    }
                   return $da ;
                    // if(empty( $da )) {
                     
                    //        $userMessage  = '😋';
                     
                    // } else {
                     
                    //        $comma_separated = implode("\n", $da);
                    //        $userMessage = "วันนี้คุณแม่กิน \n".$comma_separated."\n \n"."พลังงาน: ".$sum."kcal";
                     
                    // }

                    // return $userMessage;

  }
  public function cal_sum_food($tracker){
                                 $d = explode(" ",$tracker);
                                    $u = [];
                                    $da= [];

                                      $json1 = file_get_contents('calfood.json');
                                      $json= json_decode($json1);
                                                foreach($json->data as $item)
                                                  {
                                                    
                                                    foreach($d as $item1)
                                                      if(strpos( $item1, $item->id ) !== false )
                                                      {

                                                        // $da[]= $item->content;
                                                        $u[] = $item->cal;
                                                        $sum =  array_sum($u);
                                                                                
                                                      }    
                                    }
                        if(empty($sum)){
                          $sum=null;
                        }
                    return $sum;

  }
  public function pregnancy_calculator_block($user){          
                  $users_register = (new SqlController)->users_register_select($user);
                  // $date_preg = $users_register->date_preg;
                  //$due_date = $users_register->due_date;
                  $type_preg_week = $users_register->type_preg_week;
                  
        switch($type_preg_week) {
     
                 case ($type_preg_week == 1 ): 

                      $strDate1 = $users_register->date_preg;
                      $strDate2=date("Y-m-d");
                      
                      $date_pre =  (strtotime($strDate2) - strtotime($strDate1))/( 60 * 60 * 24 );
                      $week = $date_pre/7;
                      $w_preg = number_format($week);
                      $day = $date_pre%7;
                      $day_preg = number_format($day);
                      $due_date = 40-$w_preg;
                      $due_date = date("Y-m-d",strtotime($due_date." weeks")); 

                      $age_pre = $w_preg ;
                      // (new SqlController)->sequentsteps_update2($user,$w_preg); 
                      // (new SqlController)->update_datepreg($strDate1,$user);
                      ///กำหนดการคลอด                      
                      // $due_date = (new SqlController)->update_due_date( $user,$due_date);
                      ////////////////
                      return  $age_pre;                                                       
                    break;
                 case ($type_preg_week == 2)  : 
                        
                                 $strDate1 = $users_register->due_date;
                                 $strDate2=date("Y-m-d");
                                
                                 $date_pre =  (strtotime($strDate1) - strtotime($strDate2))/( 60 * 60 * 24 );
                                 $week = $date_pre/7;
                                 $week_preg =floor($week);
                                 $day = $date_pre%7;
                                 $day_preg = number_format($day);
                                 $w_preg = 39-$week_preg  ;
                                 $d = 7-$day_preg;

                                 $due_date = date("Y-m-d",strtotime($strDate1)); 


                         switch ($d){
                         case '7':
                              $w_preg = $w_preg + 1;
                              $replyData = $w_preg;
                              return  $replyData;
                          break;
                         default:
                              $replyData = $w_preg;
                              return  $replyData;
                          break;
                          }
               
                     break;          
      }
    }
     
}
