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
use App\Models\blood_sugar as blood_sugar;
use App\Models\personal_doctor_mom as personal_doctor_mom;

use View;
use DB;
use Carbon\Carbon;
use DateTime;

use Google\Cloud\Dialogflow\V2\SessionsClient;
use Google\Cloud\Dialogflow\V2\TextInput;
use Google\Cloud\Dialogflow\V2\QueryInput;

use Google\Cloud\Dialogflow\V2\IntentsClient;
use Google\Cloud\Dialogflow\V2\Intent;
use Google\Cloud\Dialogflow\V2\Intent\TrainingPhrase\Part;
use Google\Cloud\Dialogflow\V2\Intent\TrainingPhrase;

use Google\Cloud\Dialogflow\V2\Intent\Message\Text;
use google\Cloud\Dialogflow\v2\Intent\Message;


use App\Http\Controllers\Controller;
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
//use LINE\LINEBot\Event;
//use LINE\LINEBot\Event\BaseEvent;
//use LINE\LINEBot\Event\MessageEvent;
use LINE\LINEBot\MessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use LINE\LINEBot\MessageBuilder\LocationMessageBuilder;
use LINE\LINEBot\MessageBuilder\AudioMessageBuilder;
use LINE\LINEBot\MessageBuilder\VideoMessageBuilder;
use LINE\LINEBot\ImagemapActionBuilder;
use LINE\LINEBot\ImagemapActionBuilder\AreaBuilder;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapMessageActionBuilder ;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapUriActionBuilder;
use LINE\LINEBot\MessageBuilder\Imagemap\BaseSizeBuilder;
use LINE\LINEBot\MessageBuilder\ImagemapMessageBuilder;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\DatetimePickerTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselColumnTemplateBuilder;



// define('LINE_MESSAGE_CHANNEL_SECRET','f571a88a60d19bb28d06383cdd7af631');
// define('LINE_MESSAGE_ACCESS_TOKEN','omL/jl2l8TFJaYFsOI2FaZipCYhBl6fnCf3da/PEvFG1e5ADvMJaILasgLY7jhcwrR2qOr2ClpTLmveDOrTBuHNPAIz2fzbNMGr7Wwrvkz08+ZQKyQ3lUfI5RK/NVozfMhLLAgcUPY7m4UtwVwqQKwdB04t89/1O/w1cDnyilFU=');
// define('LINE_MESSAGE_CHANNEL_SECRET','949b099c23a7c9ca8aebe11ad9b43a52');
// define('LINE_MESSAGE_ACCESS_TOKEN','qFLN6cTuyvSWdbB1FHgUBEsD9hM66QaW3+cKz/LsNkwzMrBNZrBkH9b1zuCGp9ks0IpGRLuT6W1wLOJSWQFAlnHT/KbDBpdpyDU4VTUdY6qs5o1RTuCDsL3jTxLZnW1qbgmLytIpgi1X1vqKKsYywAdB04t89/1O/w1cDnyilFU=');
define('LINE_MESSAGE_CHANNEL_SECRET','a06f8f521aabe202f1ce7427b4e52d1b');
define('LINE_MESSAGE_ACCESS_TOKEN','UWrfpYzUUCCy44R4SFvqITsdWn/PeqFuvzLwey51hlRA1+AX/jSyCVUY7V2bPTkuoaDzmp1AY5CfsgFTIinxzxIYViz+chHSXWsxZdQb5AyZu7U67A9f18NQKE/HfGNrZZrwNxWNUwVJf2AszEsCvgdB04t89/1O/w1cDnyilFU=');
class testController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {       
   //  $user_id ='U2dc636d2cd052e82c29f5284e00f69b9';

   // $blood_sugar = blood_sugar::where('user_id',$user_id)
   //                            ->whereNull('deleted_at')
   //                             ->get();   

   // $blood_sugar =  blood_sugar::where('user_id',$user_id)->whereNull('deleted_at')->select("datetime", \DB::raw('(CASE 
   //     WHEN (( blood_sugar.meal = 4 AND blood_sugar.blood_sugar >120) OR (( blood_sugar.time_of_day  = 1 AND blood_sugar.blood_sugar>95) OR (blood_sugar.time_of_day = 3 and blood_sugar.blood_sugar>140 ))) THEN "HIGHเกินเกณฑ์" 
   //     WHEN blood_sugar.blood_sugar < 60 THEN "LOWต่ำกว่าเกณฑ์" 
   //     ELSE "NORMALปกติ" 
   //     END) AS status_lable'))->groupBy('datetime')
   //     ->get();
   // $user_age = 31;
   // $active_lifestyle =  1; 
   // $user_Pre_weight = 62;
   // $preg_week = 34 ;
   // $cal  = (new CalController)->cal_calculator($user_age,$active_lifestyle,$user_Pre_weight,$preg_week); 
   // dd($cal);
              $doctor_id = 'test';
               $users = DB::table('personal_doctor_mom')
               ->where('personal_doctor_mom.doctor_id',$doctor_id)
               ->join('users_register', function ($join) {
               $join->on('personal_doctor_mom.user_id', '=', 'users_register.user_id');
               })
               ->whereNull('personal_doctor_mom.deleted_at')
               ->whereNull('users_register.deleted_at')
               ->select('users_register.hospital_num','users_register.user_name','users_register.preg_week','users_register.due_date','users_register.user_weight', 'users_register.created_at', 'users_register.weight_status','users_register.user_id')
               ->orderBy('users_register.id', 'asc')
               // ->distinct('users_register')
               ->paginate(1);

       
                         
               return view('admin_edit_user', compact(['users'])); 

    }
    // public function bmi_calculator($user_weight,$user_height){
    //             $height = $user_height*0.01;
    //             $bmi = $user_weight/($height*$height);
    //             $bmi = number_format($bmi, 2, '.', '');
    //         return $bmi;
    // }
    public function notice_monday()
    {
       $httpClient = new CurlHTTPClient('omL/jl2l8TFJaYFsOI2FaZipCYhBl6fnCf3da/PEvFG1e5ADvMJaILasgLY7jhcwrR2qOr2ClpTLmveDOrTBuHNPAIz2fzbNMGr7Wwrvkz08+ZQKyQ3lUfI5RK/NVozfMhLLAgcUPY7m4UtwVwqQKwdB04t89/1O/w1cDnyilFU=');
       $bot = new \LINE\LINEBot($httpClient, array('channelSecret' => 'f571a88a60d19bb28d06383cdd7af631'));

       $status = 2;
       $user_select = $this->user_select($status);

       $arrlength = count($user_select);
       for($x = 0; $x < $arrlength ; ++$x) {
          $user_id = $user_select[$x];
          $user_id = 'U2dc636d2cd052e82c29f5284e00f69b9';

          $RecordOfPregnancy = $this->RecordOfPregnancy($user_id);
          $preg_week = $RecordOfPregnancy->preg_week;
          $preg_week = $preg_week+1;

             if($preg_week>41){
                    $users_register = users_register::where('user_id', $user_id)
                                                   ->update(['status'=>'0']);


             }else{
          $pregnants = $this->pregnants($preg_week);
          $descript = $pregnants->descript;
          
          // $picFullSize = 'https://peat.none.codes/week/'.$preg_week.'.jpg';
          // $picThumbnail = 'https://peat.none.codes/week/'.$preg_week.'.jpg';
          
          $Message1 =  'สัปดาห์นี้คุณมีอายุครรภ์'.$preg_week.'สัปดาห์แล้วนะคะ';
          // $Message3 =  $descript;
          $Message4 = 'สัปดาห์นี้คุณแม่มีน้ำหนักเท่าไรแล้วคะ?';

          $textMessage1 = new TextMessageBuilder($Message1);
          // $textMessage2 = new ImageMessageBuilder($picFullSize,$picThumbnail);
          // $textMessage3 = new TextMessageBuilder($Message3);
          $textMessage4 = new TextMessageBuilder($Message4);
          
          $multiMessage = new MultiMessageBuilder;
          $multiMessage->add($textMessage1);
          // $multiMessage->add($textMessage2);
          // $multiMessage->add($textMessage3);
          $multiMessage->add($textMessage4);
          $textMessageBuilder = $multiMessage; 
       
          $seqcode     = 1003;
          $nextseqcode = 0000;
          $sequentsteps_insert =  $this->sequentsteps_update($user_id,$seqcode,$nextseqcode);

          $user_weight  = 'NULL';
          $RecordOfPregnancy = $this->RecordOfPregnancy_insert($preg_week, $user_weight,$user_id);

          $response = $bot->pushMessage( $user_id ,$textMessageBuilder);
          $response->getHTTPStatus() . ' ' . $response->getRawBody();
             }

        
             $up= $this->user_update($preg_week,$user_id);

       }
      
    }
        public function tracker_insert1($user_id,$tracker)
    {          
          $tracker = tracker::insert(['user_id'=>$user_id,'breakfast' => $tracker,'lunch' => 'NULL','dinner' => 'NULL','dessert_lu' => 'NULL' ,'dessert_din' => 'NULL' ,'exercise' => 'NULL','vitamin'=>'NULL','created_at'=>NOW(),'updated_at' =>NOW(),'deleted_at' => 'NULL','data_to_ulife'=>'0']);
    }
     public function user_select($status)
    {
       $user_select = users_register::select('user_id')
                      ->whereIn('preg_week', ['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','32','33','34','35','36','37','38','39','40','41'])
                       ->where('deleted_at', 'NULL')
                       ->whereIn('status', [1, $status])
                       ->where('user_id', 'U2dc636d2cd052e82c29f5284e00f69b9')
                       //->where('user_id', 'Udb5efc89a4729c093051ce8813454223')
                       ->distinct()
                       ->pluck('user_id')
                       ->all();
          
       //print_r($user_select);
       return  $user_select;
    }
    public function RecordOfPregnancy($user_id){

        $RecordOfPregnancy = RecordOfPregnancy::where('user_id',$user_id)
                                         ->whereBetween('preg_week', ['1','41'])
                                         ->orderBy('updated_at', 'desc')
                                         ->first();
        return $RecordOfPregnancy;

    }
      public function user_update($preg_week,$user_id)
    {
       $user_select = users_register::where('user_id', $user_id)
                      ->update(['preg_week' =>$preg_week]);
          
       //print_r($user_select);
       return  $user_select;
    }

    public function RecordOfPregnancy_asc($user_id){

        $RecordOfPregnancy = RecordOfPregnancy::where('user_id',$user_id)
                                         ->orderBy('updated_at', 'asc')
                                         ->first();
        return $RecordOfPregnancy;

    }
    


    public function  pregnants($preg_week){
         $pregnants = pregnants::where('week', $preg_week)->first();
        return $pregnants;

    }
    public function sequentsteps_update($user_id,$seqcode,$nextseqcode)
    {          
         $sequentsteps = sequentsteps::where('sender_id', $user_id)
                       ->update(['seqcode' =>$seqcode,'nextseqcode' => $nextseqcode]);
    }
    public function RecordOfPregnancy_insert($preg_week, $user_weight,$user_id){
     $RecordOfPregnancy = RecordOfPregnancy::insert(['user_id'=>$user_id,'preg_week' => $preg_week,'preg_weight' => $user_weight,  'created_at'=>NOW(),'updated_at' =>NOW(),'deleted_at' => 'NULL','data_to_ulife'=>'0']);
    }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


 public function notice_day()
    {
       $httpClient = new CurlHTTPClient('omL/jl2l8TFJaYFsOI2FaZipCYhBl6fnCf3da/PEvFG1e5ADvMJaILasgLY7jhcwrR2qOr2ClpTLmveDOrTBuHNPAIz2fzbNMGr7Wwrvkz08+ZQKyQ3lUfI5RK/NVozfMhLLAgcUPY7m4UtwVwqQKwdB04t89/1O/w1cDnyilFU=');
       $bot = new \LINE\LINEBot($httpClient, array('channelSecret' => 'f571a88a60d19bb28d06383cdd7af631'));

       $status = 3;
       $user_select = $this->user_select($status);

       $arrlength = count($user_select);
       for($x = 0; $x < $arrlength ; ++$x) {
          $user_id = $user_select[$x];
          $Message1 =  'วันนี้คุณทานอะไรไปบ้างคะ';
          $textMessageBuilder = new TextMessageBuilder($Message1);
          
          $seqcode     = 2001;
          $nextseqcode = 2002;
          $sequentsteps_insert =  $this->sequentsteps_update($user_id,$seqcode,$nextseqcode);

          $response = $bot->pushMessage( $user_id ,$textMessageBuilder);
          $response->getHTTPStatus() . ' ' . $response->getRawBody();


       }

    }
 public function notice_breakfast()
    {
       $httpClient = new CurlHTTPClient('omL/jl2l8TFJaYFsOI2FaZipCYhBl6fnCf3da/PEvFG1e5ADvMJaILasgLY7jhcwrR2qOr2ClpTLmveDOrTBuHNPAIz2fzbNMGr7Wwrvkz08+ZQKyQ3lUfI5RK/NVozfMhLLAgcUPY7m4UtwVwqQKwdB04t89/1O/w1cDnyilFU=');
       $bot = new \LINE\LINEBot($httpClient, array('channelSecret' => 'f571a88a60d19bb28d06383cdd7af631'));

       $status = 3;
       $user_select = $this->user_select($status);

       $arrlength = count($user_select);
       for($x = 0; $x < $arrlength ; ++$x) {
          $user_id = $user_select[$x];

          $a = array("สวัสดีตอนเเช้าค่ะคุณแม่😊 ตอนเช้าคุณแม่ทานอะไรไปบ้างคะ?","มอนิ่งนะคะ😁 คุณแม่ทานอะไรหรือยังคะ ทานอะไรไปบ้างเอ่ย?","สวัสดีค่ะ☀ เช้านี้คุณแม่ทานอะไรบ้างคะ?","สวัสดีตอนเช้าค่ะคุณแม่😊 ทานข้าวเช้าหรือยังค่ะ ทานอะไรไปบ้างคะ?");
          $random_keys= array_rand($a,2) ;
          $Message1 =  $a[$random_keys[0]];
          $textMessageBuilder = new TextMessageBuilder($Message1);
          
          $seqcode     = 2005;
          $nextseqcode = 2006;
          $sequentsteps_insert =  $this->sequentsteps_update($user_id,$seqcode,$nextseqcode);

          $response = $bot->pushMessage( $user_id ,$textMessageBuilder);
          $response->getHTTPStatus() . ' ' . $response->getRawBody();
          $tracker= 'NULL';
          $tracker_insert =  $this->tracker_insert1($user_id,$tracker);

       }
      
    }
public function notice_lunch()
    {
       $httpClient = new CurlHTTPClient('omL/jl2l8TFJaYFsOI2FaZipCYhBl6fnCf3da/PEvFG1e5ADvMJaILasgLY7jhcwrR2qOr2ClpTLmveDOrTBuHNPAIz2fzbNMGr7Wwrvkz08+ZQKyQ3lUfI5RK/NVozfMhLLAgcUPY7m4UtwVwqQKwdB04t89/1O/w1cDnyilFU=');
       $bot = new \LINE\LINEBot($httpClient, array('channelSecret' => 'f571a88a60d19bb28d06383cdd7af631'));

       $status = 3;
       $user_select = $this->user_select($status);
      


       $arrlength = count($user_select);
       for($x = 0; $x < $arrlength ; ++$x) {
          $user_id = $user_select[$x];

          $a = array("😊มื้อเที่ยงนี้ทานข้าวหรือยังคะ ทานอะไรบ้างคะ? ","มื้อเที่ยงแล้ว😁 คุณแม่ทานอะไรหรือยังคะ ทานอะไรไปบ้างเอ่ย?","☀มื้อเที่ยงคุณแม่ทานอะไรบ้างคะ?","สวัสดีตอนเที่ยงค่ะคุณแม่😊 ทานข้าวหรือยังค่ะ ทานอะไรไปบ้างคะ?");
          
          
          $random_keys= array_rand($a,2) ;
          $Message1 =  $a[$random_keys[0]];
          $textMessageBuilder = new TextMessageBuilder($Message1);
          
          $seqcode     = 2006;
          $nextseqcode = 2007;
          $sequentsteps_insert =  $this->sequentsteps_update($user_id,$seqcode,$nextseqcode);

          $response = $bot->pushMessage( $user_id ,$textMessageBuilder);
          $response->getHTTPStatus() . ' ' . $response->getRawBody();


       }
      
    }
public function notice_dinner()
    {
       $httpClient = new CurlHTTPClient('omL/jl2l8TFJaYFsOI2FaZipCYhBl6fnCf3da/PEvFG1e5ADvMJaILasgLY7jhcwrR2qOr2ClpTLmveDOrTBuHNPAIz2fzbNMGr7Wwrvkz08+ZQKyQ3lUfI5RK/NVozfMhLLAgcUPY7m4UtwVwqQKwdB04t89/1O/w1cDnyilFU=');
       $bot = new \LINE\LINEBot($httpClient, array('channelSecret' => 'f571a88a60d19bb28d06383cdd7af631'));

       $status = 3;
       $user_select = $this->user_select($status);

       $arrlength = count($user_select);
       for($x = 0; $x < $arrlength ; ++$x) {
          $user_id = $user_select[$x];
            $a = array("มื้อเย็นแล้ว ทานข้าวหรือยังคะ ทานอะไรบ้างคะ?😊","มื้อเย็นแล้ว😁 คุณแม่ทานอะไรหรือยังคะ ทานอะไรไปบ้างเอ่ย?","มื้อเย็นคุณแม่ทานอะไรบ้างคะ?","สวัสดีตอนเย็นค่ะคุณแม่😊 ทานข้าวหรือยังค่ะ ทานอะไรไปบ้างคะ?");
          
          $random_keys= array_rand($a,2) ;
          $Message1 =  $a[$random_keys[0]];

          $textMessageBuilder = new TextMessageBuilder($Message1);
          
          $seqcode     = 2001;
          $nextseqcode = 2002;
          $sequentsteps_insert =  $this->sequentsteps_update($user_id,$seqcode,$nextseqcode);

          $response = $bot->pushMessage( $user_id ,$textMessageBuilder);
          $response->getHTTPStatus() . ' ' . $response->getRawBody();


       }
      
    }

     public function liff_register($user)
    {

  
        return View::make('liff');
    } 
    public function detect_intent_texts($projectId, $text, $sessionId , $languageCode)
    {
        // new session
        $test = array('credentials' => 'client-secret.json');


        $sessionsClient = new SessionsClient($test);
        $session = $sessionsClient->sessionName($projectId, $sessionId ?: uniqid());
        printf('Session path: %s' . PHP_EOL, $session);
     
        // create text input
        $textInput = new TextInput();
        $textInput->setText($text);
        $textInput->setLanguageCode($languageCode);
     
        // create query input
        $queryInput = new QueryInput();
        $queryInput->setText($textInput);
     
        // get response and relevant info
        $response = $sessionsClient->detectIntent($session, $queryInput);
        $queryResult = $response->getQueryResult();
        $queryText = $queryResult->getQueryText();
        $intent = $queryResult->getIntent();
        $displayName = $intent->getDisplayName();
        $confidence = $queryResult->getIntentDetectionConfidence();
        $fulfilmentText = $queryResult->getFulfillmentText();

       
        print(str_repeat("=", 20) . PHP_EOL);
        printf('Query text: %s' . PHP_EOL, $queryText);
        printf('Detected intent: %s (confidence: %f)' . PHP_EOL, $displayName,
            $confidence);
        print(PHP_EOL);
        printf('Fulfilment text: %s' . PHP_EOL, $fulfilmentText);


        $sessionsClient->close();
         return $session;
       
    }
   
}
