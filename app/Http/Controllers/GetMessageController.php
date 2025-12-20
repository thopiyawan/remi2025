<?php

namespace App\Http\Controllers;

use App\Models\pregnants as pregnants;
use App\Models\RecordOfPregnancy as RecordOfPregnancy;
use App\Models\sequents as sequents;
use App\Models\sequentsteps as sequentsteps;
use App\Models\users_register as users_register;
use App\Models\tracker as tracker;
use App\Models\question as question;
use App\Models\quizstep as quizstep;
use App\Models\reward as reward;

use Google\Cloud\Dialogflow\V2\SessionsClient;
use Google\Cloud\Dialogflow\V2\TextInput;
use Google\Cloud\Dialogflow\V2\QueryInput;
use Google\Cloud\Dialogflow\V2\IntentsClient;
use Google\Cloud\Dialogflow\V2\Intent;


use App\Http\Controllers\checkmessageController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\SqlController;
use App\Http\Controllers\CalController;
use App\Http\Controllers\ReplyMessageController;

use Storage;

use Image; 
use Carbon\Carbon;
use DateTime;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
use LINE\LINEBot\Event\Parser\EventRequestParser;
use LINE\LINEBot\Exception\InvalidSignatureException;


use Session;

// define('LINE_MESSAGE_CHANNEL_ID','กรอก ค่า Channel ID');
// define('LINE_MESSAGE_CHANNEL_SECRET','f571a88a60d19bb28d06383cdd7af631');
// define('LINE_MESSAGE_ACCESS_TOKEN','omL/jl2l8TFJaYFsOI2FaZipCYhBl6fnCf3da/PEvFG1e5ADvMJaILasgLY7jhcwrR2qOr2ClpTLmveDOrTBuHNPAIz2fzbNMGr7Wwrvkz08+ZQKyQ3lUfI5RK/NVozfMhLLAgcUPY7m4UtwVwqQKwdB04t89/1O/w1cDnyilFU=');
// define('LINE_MESSAGE_CHANNEL_SECRET','949b099c23a7c9ca8aebe11ad9b43a52');
// define('LINE_MESSAGE_ACCESS_TOKEN','qFLN6cTuyvSWdbB1FHgUBEsD9hM66QaW3+cKz/LsNkwzMrBNZrBkH9b1zuCGp9ks0IpGRLuT6W1wLOJSWQFAlnHT/KbDBpdpyDU4VTUdY6qs5o1RTuCDsL3jTxLZnW1qbgmLytIpgi1X1vqKKsYywAdB04t89/1O/w1cDnyilFU=');
//define('LINE_MESSAGE_CHANNEL_SECRET','a06f8f521aabe202f1ce7427b4e52d1b');
//define('LINE_MESSAGE_ACCESS_TOKEN','UWrfpYzUUCCy44R4SFvqITsdWn/PeqFuvzLwey51hlRA1+AX/jSyCVUY7V2bPTkuoaDzmp1AY5CfsgFTIinxzxIYViz+chHSXWsxZdQb5AyZu7U67A9f18NQKE/HfGNrZZrwNxWNUwVJf2AszEsCvgdB04t89/1O/w1cDnyilFU=');

class GetMessageController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    /**
     * @var GetMessageService
     */
//get message from line chatbot

  public function getmessage(Request $request) { 
        // ✅ ต้องบรรทัดแรก
        $content   = $request->getContent();
        $signature = $request->header('x-line-signature');

        \Log::info('LINE WEBHOOK', [
            'len' => strlen($content),
            'has_signature' => !empty($signature),
            'method' => $request->method(),
            'ua' => $request->header('user-agent'),
        ]);

        if (!$content || !$signature) {
            return response()->json(['status' => 'missing_data'], 400);
        }

        $httpClient = new CurlHTTPClient(config('line.access_token'));
        $bot = new LINEBot($httpClient, [
            'channelSecret' => config('line.channel_secret')
        ]);

        try {
            $events = $bot->parseEventRequest($content, $signature);
        } catch (InvalidSignatureException $e) {
            \Log::error('INVALID SIGNATURE');
            return response()->json(['status' => 'invalid_signature'], 400);
        }

        // ✅ ป้องกัน events ว่าง
        if (empty($events)) {
            \Log::info('LINE WEBHOOK: no events');
            return response()->json(['status' => 'ok'], 200);
        }

        // ✅ รองรับหลาย event
        foreach ($events as $eventObj) {

          // 📩 กรณีพิมพ์ข้อความ
              if ($eventObj instanceof \LINE\LINEBot\Event\MessageEvent\TextMessage) {

                  $replyToken = $eventObj->getReplyToken();
                  $userId     = $eventObj->getUserId();
                  $text       = $eventObj->getText();
                  $httpClient = new CurlHTTPClient(config('line.access_token'));
                  $bot = new LINEBot($httpClient, [
                      'channelSecret' => config('line.channel_secret')
                  ]);
                  $this->checkmessage($replyToken, $text, $userId, $bot);
                  continue;
           
              }
              // ➕ กรณีปลดบล็อค / add friend
              if ($eventObj instanceof \LINE\LINEBot\Event\FollowEvent) {

                  $replyToken = $eventObj->getReplyToken();
                  $user     = $eventObj->getUserId();
                  $users_register = (new SqlController)->users_register_select($user);
                  if(is_null($users_register)){
                    //ยังไม่ลงทะเบียน
                      $userMessage  = 'ยังไม่ลงทะเบียน';
                      $case = 6; 
                  }else{
                      $update = 6;
                      $update_preg =  (new CalController)->pregnancy_calculator_block($user);
                      $answer = $update_preg;
                      $user_update = (new SqlController)->user_update($user,$answer,$update); 
                      $userMessage = 'สวัสดีค่ะ เรมี่คิดถึงคุณแม่มากๆเลยค่ะ';
                      $case = 1;

                    // $userMessage  = 'ลงทะเบียนแล้ว';
                  }
                  // $replyText = $userMessage;
                  // $bot->replyMessage(
                  //     $replyToken,
                  //     new TextMessageBuilder($replyText)
                  // );
                 return (new ReplyMessageController)->replymessage($replyToken,$userMessage,$case,$user);   

              }

              // 🚫 กรณีบล็อค (unfollow) — ห้าม reply
              if ($eventObj instanceof \LINE\LINEBot\Event\UnfollowEvent) {

                  $userId = $eventObj->getUserId();
                  \Log::info('USER UNFOLLOW', ['user' => $userId]);
                  // ❌ ห้าม reply
              }
        }


        return response()->json(['status' => 'success'], 200);
  }
  public function getmessage1(Request $request) {         
  
     // ✅ ต้องบรรทัดแรก
    $content   = $request->getContent();
    $signature = $request->header('x-line-signature');

    \Log::info('LINE WEBHOOK', [
        'len' => strlen($content),
        'has_signature' => !empty($signature),
        'method' => $request->method(),
        'ua' => $request->header('user-agent'),
    ]);

    if (!$content || !$signature) {
        return response()->json(['status' => 'missing_data'], 400);
    }

    $httpClient = new CurlHTTPClient(config('line.access_token'));
    $bot = new LINEBot($httpClient, [
        'channelSecret' => config('line.channel_secret')
    ]);

    try {
        // ✅ ตรวจ signature ที่ LINE ส่งมา
        $events = $bot->parseEventRequest($content, $signature);

    } catch (InvalidSignatureException $e) {
        \Log::error('INVALID SIGNATURE');
        return response()->json(['status' => 'invalid_signature'], 400);
    }

    // ===== จากนี้ business logic เดิมของคุณใช้ได้ทั้งหมด =====
    $eventObj = $events[0];
    $eventType = $eventObj->getType();
    $replyToken = $eventObj->getReplyToken();
    $user = $eventObj->getUserId();

    if(!is_null($eventFollow)) {
      $replyToken = $eventObj->getReplyToken(); 
      // $userMessage = $eventObj->getText();
      $user = $eventObj->getUserId();
      $users_register = (new SqlController)->users_register_select($user);
        if(is_null($users_register)){
          //ยังไม่ลงทะเบียน
            $userMessage  = 'ยังไม่ลงทะเบียน';
            $case = 6; 
        }else{
            $update = 6;
            $update_preg =  (new CalController)->pregnancy_calculator_block($user);
            $answer = $update_preg;
            $user_update = (new SqlController)->user_update($user,$answer,$update); 
            $userMessage = 'สวัสดีค่ะ เรมี่คิดถึงคุณแม่มากๆเลยค่ะ';
            $case = 1;

          // $userMessage  = 'ลงทะเบียนแล้ว';
        }
      
   
      return (new ReplyMessageController)->replymessage($replyToken,$userMessage,$case,$user);   
    }    
     
    // ตรวจสอบถ้าเป้น Message Event และกำหนดค่าตัวแปรต่างๆ
    if(!is_null($eventMessage)) {
      // สร้างตัวแปรเก็ยค่าประเภทของ Message จากทั้งหมด 8 ประเภท
      $typeMessage = $eventObj->getMessageType();  
      //  text | image | sticker | location | audio | video | imagemap | template 
      // ถ้าเป็นข้อความ
      // if($typeMessage=='text') {
      //   $userMessage = $eventObj->getText(); // เก็บค่าข้อความที่ผู้ใช้พิมพ์
      // }
      // ถ้าเป็น sticker
      // if($typeMessage=='sticker'){
      //   $packageId = $eventObj->getPackageId();
      //   $stickerId = $eventObj->getStickerId();
      // }
      // ถ้าเป็น location
      // if($typeMessage=='location'){
      //   $locationTitle = $eventObj->getTitle();
      //   $locationAddress = $eventObj->getAddress();
      //   $locationLatitude = $eventObj->getLatitude();
      //   $locationLongitude = $eventObj->getLongitude();
      // }       
    // เก็บค่า id ของข้อความ
    $idMessage = $eventObj->getMessageId();  
}
 
// ส่วนของการทำงาน
if(!is_null($events)){
  // ถ้าเป็น Postback Event
  if(!is_null($eventPostback)){
        $dataPostback = NULL;
        $paramPostback = NULL;
        // แปลงข้อมูลจาก Postback Data เป็น array
        parse_str($eventObj->getPostbackData(),$dataPostback);
        // ดึงค่า params กรณีมีค่า params
        $paramPostback = $eventObj->getPostbackParams();
        // ทดสอบแสดงข้อความที่เกิดจาก Postaback Event
        $textReplyMessage = "ข้อความจาก Postback Event Data = ";        
        $textReplyMessage.= json_encode($dataPostback);
        $textReplyMessage.= json_encode($paramPostback);
        // $replyData = new TextMessageBuilder($textReplyMessage);    
        $reward_code = $dataPostback['item']; 
        $action = $dataPostback['action']; 

        $reward_code = json_encode($reward_code);
        $action = json_encode($action);
        $reward_code = str_replace('"', "", $reward_code );
        $action = str_replace('"', "", $action );

        // if($action == 'reward'){
              // $case = 1 ;

                      $userMessage =  $reward_code ;
                      // return (new ReplyMessageController)->replymessage($replyToken,$userMessage,$case); 
   
       
            //           $replyData = new TextMessageBuilder($textReplyMessage);  
            //           $userMessage = $reward_code;
            //           // $replyToken = $eventObj->getReplyToken(); 
            //           // $user = $eventObj->getUserId();  

            return $this->checkmessage($replyToken,$userMessage,$user,$bot );
          // } 
  }
    // ถ้าเป้น Message Event 
  if(!is_null($eventMessage)) {
                            // สร้างตัวแปรเก็ยค่าประเภทของ Message จากทั้งหมด 8 ประเภท
                  $typeMessage = $eventObj->getMessageType();  
                  //  text | image | sticker | location | audio | video | imagemap | template 
                  // ถ้าเป็นข้อความ
                  if($typeMessage=='text') {
                        if(!is_null($events)) {
                            $replyToken = $eventObj->getReplyToken(); 
                            $userMessage = $eventObj->getText();
                            $user = $eventObj->getUserId();
                        }
                       return $this->checkmessage($replyToken,$userMessage,$user,$bot );
                  }
                  // ถ้าเป็น sticker
                  elseif($typeMessage=='sticker'){
                           $replyToken = $eventObj->getReplyToken(); 
                           $case = 29 ;
                           $userMessage= '0';
                           return (new ReplyMessageController)->replymessage($replyToken,$userMessage,$case);
                  }
                  // ถ้าเป็น location
                  elseif($typeMessage=='image'){
                $replyToken = $eventObj->getReplyToken(); 

                 $response = $bot->getMessageContent($idMessage);
                    if ($response->isSucceeded()) {
          // คำสั่ง getRawBody() ในกรณีนี้ จะได้ข้อมูลส่งกลับมาเป็น binary 
          // เราสามารถเอาข้อมูลไปบันทึกเป็นไฟล์ได้
          $dataBinary = $response->getRawBody(); // return binary
          // ทดสอบดูค่าของ header ด้วยคำสั่ง getHeaders()
          $dataHeader = $response->getHeaders();   
          $replyData = new TextMessageBuilder(json_encode($dataHeader));
          $case = 1;
          $userMessage= $replyData;
        return (new ReplyMessageController)->replymessage($replyToken,$userMessage,$case);
      
      }
          $case = 1;

       $userMessage= $response;
      return (new ReplyMessageController)->replymessage($replyToken,$userMessage,$case);
                // dd($response);
                // if ($response->isSucceeded()) {
                //     // คำสั่ง getRawBody() ในกรณีนี้ จะได้ข้อมูลส่งกลับมาเป็น binary 
                //     // เราสามารถเอาข้อมูลไปบันทึกเป็นไฟล์ได้
                //     $dataBinary = $response->getRawBody(); // return binary
                //     // ดึงข้อมูลประเภทของไฟล์ จาก header
                //     $fileType = $response->getHeader('Content-Type');    
                //     switch ($fileType){
                //         case (preg_match('/^image/',$fileType) ? true : false):
                //             list($typeFile,$ext) = explode("/",$fileType);
                //             $ext = ($ext=='jpeg' || $ext=='jpg')?"jpg":$ext;
                //             $fileNameSave = time().".".$ext;
                //             break;
                //         case (preg_match('/^audio/',$fileType) ? true : false):
                //             list($typeFile,$ext) = explode("/",$fileType);
                //             $fileNameSave = time().".".$ext;                        
                //             break;
                //         case (preg_match('/^video/',$fileType) ? true : false):
                //             list($typeFile,$ext) = explode("/",$fileType);
                //             $fileNameSave = time().".".$ext;                                
                //             break;                                                      
                //     }
                //     $botDataFolder = 'botdata/'; // โฟลเดอร์หลักที่จะบันทึกไฟล์
                //     $botDataUserFolder = $botDataFolder.$userID; // มีโฟลเดอร์ด้านในเป็น userId 
                //     // กำหนด path ของไฟล์ที่จะบันทึก
                //     $fileFullSavePath = $botDataUserFolder.'/'.$fileNameSave;
                //     file_put_contents($fileFullSavePath,$dataBinary); // ทำการบันทึกไฟล์
                //     $textReplyMessage = "บันทึกไฟล์เรียบร้อยแล้ว $fileNameSave";
                //     $replyData = new TextMessageBuilder($textReplyMessage);
                //        Storage::disk('local')->put($fileFullSavePath, $dataBinary);
                //        echo $textReplyMessage;
                  
                // }
                // $textReplyMessage = json_encode($response);
            
             

                //           $replyToken = $eventObj->getReplyToken(); 
                //            $case = 1 ;

                  }  
             
    }
  }


  }
//check condition of message
  public function checkmessage($replyToken,$userMessage,$user,$bot) {          
         //Keyword Questions about mother
          // $array = array('อาบน้ำ','ขี้','อึ', 'ปัสสาวะ','ฉี่', 'อุจจาระ', 'ทาครีม','ท้องลาย','แต่งตัว','เสื้อผ้า','รองเท้า','แหวน','เพศสัมพันธ์','มีอะไรกัน','เดินห้าง','ใส่ตุ้มสะดือ','ทาเล็บ','สีผม','ย้อมผม','แต่งหน้า','ทาลิปสติก','ไฮไลต์','การทำงานนั่งโต๊ะ','ทำงาน','เดินทาง','ทำฟัน','ออกกำลังกาย','กินยา','แพ้ท้อง','อ้วก','อาเจียน','ฉี่บ่อย','ปัสสาวะบ่อย','เหนื่อย','ท้องผูก','อุจจาระลำบาก','ขี้ลำบาก','ริดสีดวง','คัดตึงเต้านม','เจ็บเต้านม','คันบริเวณหน้าท้อง','ปวดเมื่อยบริเวณหลัง','เมื่อยหลัง','ตะคริวที่ขา','เท้าบวม','เส้นเลือดขอด','ท้องอืด','เลือดออกจากช่องคลอด','เลือดออก','แพ้ท้องรุนแรง','เจ็บครรภ์คลอดก่อนกำหนด','เจ็บท้องก่อนกำหนด','น้ำเดิน','เวียนหัว','ปวดศีรษะ','ปวดหัว','ตามัว','จุกแน่นใต้ลิ้นปี่','ลูกดิ้นลดลง','ลูกไม่ดิ้น','ไข้','กลัวอ้วน','อาหารเสริม','ของแสลง','ของที่ห้ามกิน','คลอดเจ็บ','ใกล้คลอดจะมีอาการ','อาการใกล้คลอด','เมื่อไรจะคลอด','คลอดเมื่อไร','ริดสีดวงทวารหนัก','เจ็บนม','เจ็บเต้านม','เจ็บท้องคลอดก่อนกำหนด','ท้องอืดหลังกินข้าว','คันหน้าท้อง','คันท้อง','ทาปาก','เจ็บท้องคลอด','ปวดท้อง','มีอะไรกับแฟน',"ทาลิป","คันตรงท้อง","ตะคริว","แพ้ท้องหนัก","เจ็บท้อง","พ่อ",'ลูกไม่ค่อยดิ้น','ใกล้คลอด','เจ็บเอว','ปวดเอว','เจ็บหลัง','ปวดหลัง','เตรียมตัวคลอด','คันตรงหน้าท้อง','ดื่มกาแฟ','กินกาแฟ','วัคซีน','ฉีดยา','ยารักษาสิว','ยาอันตราย','วิตามินเสริม','ยาบำรุง','ดื่มนมวัว','กินนมวัว','ภาวะครรภ์เสี่ยง','เนื้องอก','ปวดนิ้วมือ','นิ้วเท้า','ดื่มนม','กินนม','อัลตร้าซาวด์','นอนคว่ำ','ห้ามวิ่ง','ป่วยกินยา','ป่วยทานยา','ไม่สบายทานยา','ไม่สบายกินยา','บุหรี่','เหล้า','ลูกโต','น้ำมะพร้าว','ทุเรียน','เพลงโมสาท','เสียงดนตรี','ความเครียด','รู้สึกเครียด','เก้าอี้โยก','คุยกับลูก','คุยกับเด็ก','เครื่องบิน','ลูกสะอึก','อาหารที่ควรหลีกเลี่ยง','อาหารที่ไม่ควรกิน','อาหารที่ควรงด','อาหารที่ห้ามกิน','เจาะถุงน้ำคร่ำ','แกงบอน','ลาบดิบ','ซูชิ','เบียร์','น้ำชา','ชาดอกคำฝอย','ชาสมุนไพร','ชาขิง','ชาตะไคร้','ชาใบเตย','ชามะตูม','ชาโป๊ยกั๊ก','ชาเปปเปอร์มินต์','ชากุหลาบ','ชาเขียว','ชานมไข่มุก','กุ้งเต้น','ส้มตำ','กิมจิ','รสจัด','ปลาแซลมอน','มะม่วงหาวมะนาวโห่','ยาระบาย','กินคลีน','ทานคลีน','กินอาหารคลีน','ทานอาหารคลีน','ถั่วงอก','ว่านหางจรเข้','ว่านหางจระเข้','ปลาร้า','โกโก้','กินเผ็ดมาก','ทานเผ็ดมาก','กินเผ็ดบ่อย','กรดไหลย้อน','เบื่ออาหาร','ไม่อยากกินข้าว','ไม่อยากอาหาร');

          //Keyword about General mood
          $array2 = array('เหงา','เบื่อ','เครียด','ทำอะไรดี','ทำไรดี','ง่วง','เซง','เซ็ง','เหนื่อยใจ','ทำอะไรได้บ้าง','ทำไรได้บ้าง','รู้ไรมั่ง','รู้อะไรบ้าง','ทำไรได้','เบลอ','ขี้เกียจ');

          //Keyword about baby
          $array3 = array('ข้อมูลลูกน้อย','ลูกน้อยเป็นไง','ทารกเป็นไง','ตัวอ่อนเป็นไง','ตัวอ่อนหน้าตา','ทารกหน้าตา','ลูกน้อยหน้าตา','ลูกหน้าตา','หน้าตาลูก','หน้าตาทารก','หน้าตาเด็ก','หน้าตาตัวอ่อน','ลูกเป็นไง','พัฒนาการเด็ก','พัฒนาการตัวอ่อน','ลูกมีพัฒนาการ','เด็กมีพัฒนาการ','ลูกน้อยมีพัฒนาการ','ลูกมีหน้าตา','หน้าลูก','หน้าเด็ก','หน้าทารก','หน้าตัวอ่อน');

          //Keyword about weight
          $array4 = array('น้ำหนักในช่วงตั้งครรภ์ปกติควรขึ้น','น้ำหนักแม่ต้องขึ้น','น้ำหนักที่ควรจะมีระหว่างตั้งครรภ์','น้ำหนักที่เหมาะสมจนถึงคลอดควรมีน้ำหนัก','น้ำหนักต้องขึ้น','น้ำหนักตัวแม่ต้องขึ้น','น้ำหนักที่ควรขึ้น','น้ำหนักควรขึ้น','น้ำหนักควรเพิ่ม','น้ำหนักเพิ่ม','น้ำหนักตัวเพิ่ม','น้ำหนักต้องเพิ่ม');
          //Keyword about bot
          $array5 = array('น่ารัก','เก่ง','โง่','ฉลาด','ไม่น่ารัก','เกลียด');

          //Keyword about eat
          $array6 = array('ไม่กิน','ยังไม่ได้กิน','ไม่ทาน','ไม่หิว','ยัง','ขี้เกียจกิน','เสือก','ไม่แดก','ยุ่ง','ไม่บอก');

          $array7 = array('แก้ไข:ชื่อ','แก้ไข:อายุ','แก้ไข:ส่วนสูง','แก้ไข:น้ำหนักก่อนตั้งครรภ์','แก้ไข:น้ำหนักปัจจุบัน','แก้ไข:อายุครรภ์','แก้ไข:เบอร์โทรศัพท์','แก้ไข:อีเมล์','แก้ไข:โรงพยาบาลที่ฝากครรภ์','แก้ไข:แพ้อาหาร','แก้ไข:ภาวะแทรกซ้อนเบาหวาน','แก้ไข:ภาวะแทรกซ้อนความดัน','แก้ไข:ภาวะแทรกซ้อนเจ็บครรภ์คลอดก่อนกำหนด');
          //select seqcode for check message
          $sequentsteps =  (new SqlController)->sequentsteps_seqcode($user);
//start chat
          if ($userMessage =='ยอมรับ') {
                  $case = 1;
                  $seqcode = '0005';
                  $nextseqcode = '0007';
                  $userMessage  = (new SqlController)->sequents_question($seqcode);
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_insert($user,$seqcode,$nextseqcode);  
          }elseif ($userMessage == 'แนะนำอาหารเช้า'){
                   
                 $case = 37;
                 $userMessage = 1;
             

          }elseif ($userMessage == 'แนะนำอาหารจานเดียว'){
                   
                 $case = 37;
                 $userMessage = 2;
            
          }elseif ($userMessage== 'แนะนำกับข้าว'){
                   
                 $case = 37;
                 $userMessage = 3;
           
             
          }elseif ($userMessage== 'แนะนำเครื่องดื่ม'){
                   
                 $case = 37;
                 $userMessage = 4;
     
          }elseif ($userMessage == 'แนะนำผลไม้'){
                   
                 $case = 37;
                 $userMessage = 5;
          
          }elseif ($userMessage == 'แนะนำอาหารว่าง'){
                   
                 $case = 37;
                 $userMessage = 6;
        

          }elseif (strpos($userMessage, 'MENUfood') !== false ){
              
                  $pieces = explode("MENUfood", $userMessage);
                  $userMessage  = str_replace("","",$pieces[1]);
                  $case = 1;
                  $foodmenu = (new SqlController)->foodmenu_select($userMessage);
                  $userMessage = $foodmenu->advice;
               


//about random food
          }elseif ($userMessage== 'อาหารเช้า'||$userMessage=='อาหารกลางวัน' ||$userMessage== 'อาหารเย็น'||$userMessage== 'อาหารว่าง'){
                  $users_register = (new SqlController)->users_register_select($user);
                  $preg_week = $users_register->preg_week;
                  $user_weight =  $users_register->user_weight;
                  $user_age =  $users_register->user_age;
                  $active_lifestyle =  $users_register->active_lifestyle;
                  $cal  = (new CalController)->cal_calculator($user_age,$active_lifestyle,$user_weight,$preg_week);
              
              if( strpos($userMessage, 'อาหารเช้า') !== false ){
                    if ($cal <= '1,600') {
                              $a = array("1","12","23","34","45");
                      } elseif ($cal >= '1,601' && $cal <= '1,700') {
                              $a = array("2","13","24","35","46");
                      }elseif ($cal >='1,701' && $cal <='1,800') {
                              $a = array("3","14","25","36","47");
                      }elseif ($cal >='1,801' && $cal<='1,900') {
                              $a = array("4","15","26","37","48");
                      }elseif ($cal >='1,901' && $cal<='2,000') {
                              $a = array("5","16","27","38","49");
                      }elseif ($cal >='2,001' && $cal<='2,100' ) {
                              $a = array("6","17","28","39","50");
                      }elseif ($cal >= '2,101' && $cal<='2,200') {
                              $a = array("7","18","29","40","51");  
                      }elseif ($cal >= '2,201' && $cal <= '2,300') {
                              $a = array("8","19","30","41","52");    
                      }elseif ($cal >= '2,301' && $cal <='2,400') {
                              $a = array("9","20","31","42","53");
                      }elseif ($cal >= '2,401' && $cal <= '2,500') {
                              $a = array("10","21","32","43","54");
                      }else {
                              $a = array("11","22","33","44","55");
                      }
                  $random_keys= array_rand($a,2) ;
                  $input =  $a[$random_keys[0]];
                  $json1 = file_get_contents('breakfast.json');
                  $json= json_decode($json1);
                foreach($json->data as $item)
                  {
                      if($item->id == $input)
                      {
                         $userMessage1 = $item->food;
                         $userMessage2 = $item->content;                      
                      }
                  }
                  (new ReplyMessageController)->replymessage2($replyToken,$userMessage1,$userMessage2);

              }elseif (strpos($userMessage, 'อาหารกลางวัน') !== false  ) {
                      if ($cal <= '1,600') {
                              $a = array("1","12","23","34","45","56","67","78","89","100","111","122");
                      } elseif ($cal >= '1,601' && $cal <= '1,700') {
                              $a = array("2","13","24","35","46","57","68","79","90","101","112","123");
                      }elseif ($cal >='1,701' && $cal <='1,800') {
                              $a = array("3","14","25","36","47","58","69","80","91","102","113","124");
                      }elseif ($cal >='1,801' && $cal<='1,900') {
                              $a = array("4","15","26","37","48","59","70","81","92","103","114","125");
                      }elseif ($cal >='1,901' && $cal<='2,000') {
                              $a = array("5","16","27","38","49","60","71","82","93","104","115","126");
                      }elseif ($cal >='2,001' && $cal<='2,100' ) {
                              $a = array("6","17","28","39","50","61","72","83","94","105","116","127");
                      }elseif ($cal >= '2,101' && $cal<='2,200') {
                              $a = array("7","18","29","40","51","62","73","84","95","106","117","128");  
                      }elseif ($cal >= '2,201' && $cal <= '2,300') {
                              $a = array("8","19","30","41","52","63","74","85","96","107","118","129");    
                      }elseif ($cal >= '2,301' && $cal <='2,400') {
                              $a = array("9","20","31","42","53","64","75","86","97","108","119","130");
                      }elseif ($cal >= '2,401' && $cal <= '2,500') {
                              $a = array("10","21","32","43","54","65","76","87","98","109","120","131");
                      }else {
                              $a = array("11","22","33","44","55","66","77","88","99","110","121","132");
                      }
             
                  $random_keys= array_rand($a,2) ;
                  $input =  $a[$random_keys[0]];
                  $json1 = file_get_contents('lunch.json');
                  $json= json_decode($json1);
                foreach($json->data as $item)
                  {
                      if($item->id == $input)
                      {
                         $userMessage1 = $item->food;
                         $userMessage2 = $item->content;
                                                 
                      }
                  }
                  (new ReplyMessageController)->replymessage2($replyToken,$userMessage1,$userMessage2);
                       
              }elseif (strpos($userMessage, 'อาหารเย็น') !== false ) {

                      if ($cal <= '1,600') {
                              $a = array("1","12","23","34","45","56","67","78","89","100","111","122","133","144");
                      } elseif ($cal >= '1,601' && $cal <= '1,700') {
                              $a = array("2","13","24","35","46","57","68","79","90","101","112","123","134","145");

                      }elseif ($cal >='1,701' && $cal <='1,800') {
                              $a = array("3","14","25","36","47","58","69","80","91","102","113","124","135","146");

                      }elseif ($cal >='1,801' && $cal<='1,900') {
                              $a = array("4","15","26","37","48","59","70","81","92","103","114","125","136","147");

                      }elseif ($cal >='1,901' && $cal<='2,000') {
                              $a = array("5","16","27","38","49","60","71","82","93","104","115","126","137","148");

                      }elseif ($cal >='2,001' && $cal<='2,100' ) {
                              $a = array("6","17","28","39","50","61","72","83","94","105","116","127","138","149");

                      }elseif ($cal >= '2,101' && $cal<='2,200') {
                              $a = array("7","18","29","40","51","62","73","84","95","106","117","128","139","150");  

                      }elseif ($cal >= '2,201' && $cal <= '2,300') {
                              $a = array("8","19","30","41","52","63","74","85","96","107","118","129","140","151");

                      }elseif ($cal >= '2,301' && $cal <='2,400') {
                              $a = array("9","20","31","42","53","64","75","86","97","108","119","130","141","152");

                      }elseif ($cal >= '2,401' && $cal <= '2,500') {
                              $a = array("10","21","32","43","54","65","76","87","98","109","120","131","142","153");

                      }else {
                              $a = array("11","22","33","44","55","66","77","88","99","110","121","132","143","154");

                      }
           
                  $random_keys= array_rand($a,2) ;
                  $input =  $a[$random_keys[0]];
                  $json1 = file_get_contents('dinner.json');
                  $json= json_decode($json1);
                foreach($json->data as $item)
                  {
                      if($item->id == $input)
                      {
                         $userMessage1 = $item->food;
                         $userMessage2 = $item->content;                       
                      }
                  }
                (new ReplyMessageController)->replymessage2($replyToken,$userMessage1,$userMessage2);
          
             }elseif (strpos($userMessage, 'อาหารว่าง') !== false ){
                       $json1 = file_get_contents('snack.json');
                       $json= json_decode($json1);
                      if ($cal <= '1,600') {
                              $a = array("12","13","14","15");
                              $b = '1';
                      } elseif ($cal >= '1,601' && $cal <= '1,700') {
                              $a = array("16","17","18","19");
                              $b = '2';
                      }elseif ($cal >='1,701' && $cal <='1,800') {
                              $a = array("20","21","22","23");
                              $b = '3';
                      }elseif ($cal >='1,801' && $cal<='1,900') {
                              $a = array("24","25","26","27");
                              $b = '4';
                      }elseif ($cal >='1,901' && $cal<='2,000') {
                              $a = array("28","29","30","31");
                              $b = '5';
                      }elseif ($cal >='2,001' && $cal<='2,100' ) {
                              $a = array("32","33","34","35");
                              $b = '6';
                      }elseif ($cal >= '2,101' && $cal<='2,200') {
                              $a = array("36","37","38","39");
                              $b = '7';  
                      }elseif ($cal >= '2,201' && $cal <= '2,300') {
                              $a = array("40","41","42","43"); 
                              $b = '8';   
                      }elseif ($cal >= '2,301' && $cal <='2,400') {
                              $a = array("44","45","46","47");
                              $b = '9';
                      }elseif ($cal >= '2,401' && $cal <= '2,500') {
                              $a = array("48","49","50","51");
                              $b = '10';
                      }else {
                              $a = array("52","53","54","55");
                              $b = '11';
                      }
                  foreach($json->data as $item)
                  {
                      if($item->id == $b)
                      {
                         $b = $item->content;
                                                 
                      }
                  }
              $random_keys= array_rand($a,2) ;
              $input =  $a[$random_keys[0]];
           
                  foreach($json->data as $item)
                  {
                      if($item->id == $input)
                      {
                         $userMessage1 = $item->food;
                         $userMessage3 = $b.$item->content;
                                                 
                      }
                  }
                  (new ReplyMessageController)->replymessage2($replyToken,$userMessage1,$userMessage3);
             }
//What mother want to eat
            }elseif (strpos($userMessage, 'กินไรดี') !== false ||strpos($userMessage, 'กินอะไรดี') !== false ||strpos($userMessage, 'หิว') !== false ||strpos($userMessage, 'กินไรได้') !== false ) {

              $message_type = '01';
              $Message = $userMessage;
              $log_message = (new SqlController)->log_message($user,$Message,$message_type);

                  $users_register = (new SqlController)->users_register_select($user);
                  $preg_week = $users_register->preg_week;
                  $user_weight =  $users_register->user_weight;
                  $user_age =  $users_register->user_age;
                  $active_lifestyle =  $users_register->active_lifestyle;
                  $cal  = (new CalController)->cal_calculator($user_age,$active_lifestyle,$user_weight,$preg_week);
              
                  $userMessage = $cal;
              if((Carbon::now('Asia/Bangkok')->format('H:i a') >=  Carbon::parse('04:00')->format('H:i a'))&& (Carbon::now('Asia/Bangkok')->format('H:i a') <= Carbon::parse('08:00')->format('H:i a'))  ){
                      if ($cal <= '1,600') {
                                $a = array("1","12","23","34","45");
                        } elseif ($cal >= '1,601' && $cal <= '1,700') {
                                $a = array("2","13","24","35","46");
                        }elseif ($cal >='1,701' && $cal <='1,800') {
                                $a = array("3","14","25","36","47");
                        }elseif ($cal >='1,801' && $cal<='1,900') {
                                $a = array("4","15","26","37","48");
                        }elseif ($cal >='1,901' && $cal<='2,000') {
                                $a = array("5","16","27","38","49");
                        }elseif ($cal >='2,001' && $cal<='2,100' ) {
                                $a = array("6","17","28","39","50");
                        }elseif ($cal >= '2,101' && $cal<='2,200') {
                                $a = array("7","18","29","40","51");  
                        }elseif ($cal >= '2,201' && $cal <= '2,300') {
                                $a = array("8","19","30","41","52");    
                        }elseif ($cal >= '2,301' && $cal <='2,400') {
                                $a = array("9","20","31","42","53");
                        }elseif ($cal >= '2,401' && $cal <= '2,500') {
                                $a = array("10","21","32","43","54");
                        }else {
                                $a = array("11","22","33","44","55");
                        }
                    $random_keys= array_rand($a,2) ;
                    $input =  $a[$random_keys[0]];
                    $json1 = file_get_contents('breakfast.json');
                    $json= json_decode($json1);
                foreach($json->data as $item)
                  {
                      if($item->id == $input)
                      {
                         $userMessage1 = $item->food;
                         $userMessage2 = $item->content;
                                                 
                      }
                  }
                  (new ReplyMessageController)->replymessage2($replyToken,$userMessage1,$userMessage2);

              }elseif ((Carbon::now('Asia/Bangkok')->format('H:i a') >  Carbon::parse('11:00')->format('H:i a'))&& (Carbon::now('Asia/Bangkok')->format('H:i a') <= Carbon::parse('13:00')->format('H:i a'))  ) {
                        if ($cal <= '1,600') {
                                $a = array("1","12","23","34","45","56","67","78","89","100","111","122");
                        } elseif ($cal >= '1,601' && $cal <= '1,700') {
                                $a = array("2","13","24","35","46","57","68","79","90","101","112","123");
                        }elseif ($cal >='1,701' && $cal <='1,800') {
                                $a = array("3","14","25","36","47","58","69","80","91","102","113","124");
                        }elseif ($cal >='1,801' && $cal<='1,900') {
                                $a = array("4","15","26","37","48","59","70","81","92","103","114","125");
                        }elseif ($cal >='1,901' && $cal<='2,000') {
                                $a = array("5","16","27","38","49","60","71","82","93","104","115","126");
                        }elseif ($cal >='2,001' && $cal<='2,100' ) {
                                $a = array("6","17","28","39","50","61","72","83","94","105","116","127");
                        }elseif ($cal >= '2,101' && $cal<='2,200') {
                                $a = array("7","18","29","40","51","62","73","84","95","106","117","128");  
                        }elseif ($cal >= '2,201' && $cal <= '2,300') {
                                $a = array("8","19","30","41","52","63","74","85","96","107","118","129");    
                        }elseif ($cal >= '2,301' && $cal <='2,400') {
                                $a = array("9","20","31","42","53","64","75","86","97","108","119","130");
                        }elseif ($cal >= '2,401' && $cal <= '2,500') {
                                $a = array("10","21","32","43","54","65","76","87","98","109","120","131");
                        }else {
                                $a = array("11","22","33","44","55","66","77","88","99","110","121","132");
                        }
                   
                    $random_keys= array_rand($a,2) ;
                    $input =  $a[$random_keys[0]];
                    $json1 = file_get_contents('lunch.json');
                    $json= json_decode($json1);
                  foreach($json->data as $item)
                  {
                      if($item->id == $input)
                      {
                         $userMessage1 = $item->food;
                         $userMessage2 = $item->content;
                                                 
                      }
                  }
                  (new ReplyMessageController)->replymessage2($replyToken,$userMessage1,$userMessage2);

                       
              }elseif ((Carbon::now('Asia/Bangkok')->format('H:i a') >  Carbon::parse('17:00')->format('H:i a'))&& (Carbon::now('Asia/Bangkok')->format('H:i a') <= Carbon::parse('20:00')->format('H:i a'))  ) {

                         if ($cal <= '1,600') {
                                $a = array("1","12","23","34","45","56","67","78","89","100","111","122","133","144");
                        } elseif ($cal >= '1,601' && $cal <= '1,700') {
                                $a = array("2","13","24","35","46","57","68","79","90","101","112","123","134","145");
                        }elseif ($cal >='1,701' && $cal <='1,800') {
                                $a = array("3","14","25","36","47","58","69","80","91","102","113","124","135","146");
                        }elseif ($cal >='1,801' && $cal<='1,900') {
                                $a = array("4","15","26","37","48","59","70","81","92","103","114","125","136","147");
                        }elseif ($cal >='1,901' && $cal<='2,000') {
                                $a = array("5","16","27","38","49","60","71","82","93","104","115","126","137","148");
                        }elseif ($cal >='2,001' && $cal<='2,100' ) {
                                $a = array("6","17","28","39","50","61","72","83","94","105","116","127","138","149");
                        }elseif ($cal >= '2,101' && $cal<='2,200') {
                                $a = array("7","18","29","40","51","62","73","84","95","106","117","128","139","150");  
                        }elseif ($cal >= '2,201' && $cal <= '2,300') {
                                $a = array("8","19","30","41","52","63","74","85","96","107","118","129","140","151");
                        }elseif ($cal >= '2,301' && $cal <='2,400') {
                                $a = array("9","20","31","42","53","64","75","86","97","108","119","130","141","152");
                        }elseif ($cal >= '2,401' && $cal <= '2,500') {
                                $a = array("10","21","32","43","54","65","76","87","98","109","120","131","142","153");
                        }else {
                                $a = array("11","22","33","44","55","66","77","88","99","110","121","132","143","154");
                        }
       
                    $random_keys= array_rand($a,2) ;
                    $input =  $a[$random_keys[0]];
                    $json1 = file_get_contents('dinner.json');
                    $json= json_decode($json1);
                       foreach($json->data as $item)
                  {
                      if($item->id == $input)
                      {
                         $userMessage1 = $item->food;
                         $userMessage2 = $item->content;
                                                 
                      }
                  }
                  (new ReplyMessageController)->replymessage2($replyToken,$userMessage1,$userMessage2);
        
              }else{

                        if ($cal <= '1,600') {
                                $a = array("1","1");
                        } elseif ($cal >= '1,601' && $cal <= '1,700') {
                                $a = array("2","2");
                        }elseif ($cal >='1,701' && $cal <='1,800') {
                                $a = array("3","3");
                        }elseif ($cal >='1,801' && $cal<='1,900') {
                                $a = array("4","4");
                        }elseif ($cal >='1,901' && $cal<='2,000') {
                                $a = array("5","5");
                        }elseif ($cal >='2,001' && $cal<='2,100' ) {
                                $a = array("6","6");
                        }elseif ($cal >= '2,101' && $cal<='2,200') {
                                $a = array("7","7");  
                        }elseif ($cal >= '2,201' && $cal <= '2,300') {
                                $a = array("8","8");    
                        }elseif ($cal >= '2,301' && $cal <='2,400') {
                                $a = array("9","9");
                        }elseif ($cal >= '2,401' && $cal <= '2,500') {
                                $a = array("10","10");
                        }else {
                                $a = array("11","11");
                        }

                    $random_keys= array_rand($a,2) ;
                    $input =  $a[$random_keys[0]];
                    $json1 = file_get_contents('snack.json');
                    $json= json_decode($json1);
                       foreach($json->data as $item)
                  {
                      if($item->id == $input)
                      {
                         $userMessage1 = $item->food;
                         $userMessage2 = $item->content;
                                                 
                      }
                  }
                  (new ReplyMessageController)->replymessage2($replyToken,$userMessage1,$userMessage2);
             }
          
            }elseif ((new checkmessageController)->match($array3, $userMessage)) {

              $case = 39; 
              $userMessage  = $user;

//General mood
            }elseif ((new checkmessageController)->match($array2, $userMessage )){

              $message_type = '01';
              $Message = $userMessage;
              $log_message = (new SqlController)->log_message($user,$Message,$message_type);
              $case = 1;
  
                    if(strpos($userMessage, 'เหงา') !== false ||strpos($userMessage, 'เบื่อ') !== false || strpos($userMessage, 'ทำอะไรดี') !== false ||strpos($userMessage, 'เซง') !== false ||strpos($userMessage, 'เซ็ง') !== false ||strpos($userMessage, 'ทำไรดี') !== false  ){

                       $text = array('คุยกับเรมี่ได้นะ','ลองหาเพลงเพราะๆฟัง จะได้ผ่อนคลายนะคะ','หาหนังสืออ่านสักเล่มไหมคะ','ลองออกไปเดินเล่นค่ะ จะได้รู้สึกดีขึ้น');
                     
                      $random_keys= array_rand($text,2);
                      $userMessage  =  $text[$random_keys[0]];
                    }elseif (strpos($userMessage, 'ง่วง') !== false ||strpos($userMessage, 'เบลอ') !== false ) {

                      $userMessage = 'ลองงีบสักพัก จะได้รู้สึกดีขึ้นนะคะ';
                    }elseif (strpos($userMessage, 'ขี้เกียจ') !== false ) {

                      $userMessage = 'คุณแม่ลุกเดินหาอะไรทำบ้างนะคะ';
                  
                    }elseif (strpos($userMessage, 'เครียด') !== false ||strpos($userMessage, 'เหนื่อยใจ') !== false  ) {
                      $text = array('เรมี่เป็นกำลังใจให้นะคะ','อย่ากังวลนะคะ คุณแม่ลองหากิจกรรมทำ เช่นฟังเพลงสบายๆ ดูหนัง หรืออ่านหนังสือที่ชอบนะคะ','คุณแม่ลองเปลี่ยนบรรยากาศไปพบปะเพื่อนๆ พูดคุย ชอปปิง เดินเล่น สิคะจะได้ดีขึ้น','เครียดมากไม่ดีนะคะ อาจจะมีผลต่อเด็กและสุขภาพของคุณแม่ได้นะ เรมี่เป็นห่วงนะคะ','ลองอาบน้ำให้รู้สึกผ่อนคลาย สบายเนื้อสบายตัวดีไหมคะ','คุณแม่รู้สึกอารมณ์เปลี่ยนแปลง สาเหตุหนึ่งก็เกิดจากฮอร์โมนที่สูงขึ้นค่ะ ดังนั้นควรจะหาวิธีคลายความเครียดนี้นะคะ');
                     
                      $random_keys= array_rand($text,2);
                      $userMessage  =  $text[$random_keys[0]];

                    }elseif (strpos($userMessage, 'ทำอะไรได้บ้าง') !== false ||strpos($userMessage, 'ทำไรได้บ้าง') !== false ||strpos($userMessage, 'รู้ไรมั่ง') !== false ||strpos($userMessage, 'รู้อะไรบ้าง') !== false ||strpos($userMessage, 'ทำไรได้') !== false ) {

                      $userMessage = '🙆 สวัสดีค่ะ ดิฉันชื่อ REMI ความสามารถที่ดิฉันทำได้มีดังนี้นะคะ'."\n"."\n".
                                     '🤔 สามารถแนะนำอาหารที่เหมาะกับคุณแม่ที่ตั้งครรภ์ตามหลักโภชนาการ'."\n".
                                     '🤔 แนะนำท่าออกกำลังกายสำหรับคุณแม่ตั้งครรภ์ในแต่ละไตรมาส'."\n".
                                     '🤔 สามารถดูและบันทึกรายการอาหาร,วิตามิน และการออกกำลังกายย้อนหลังได้'."\n".
                                     '🤔 สามารถตอบคำถามทั่วไปตามที่คุณแม่อยากทราบได้';

                    }
               $message_type = '02';
               $Message = $userMessage;
               $log_message = (new SqlController)->log_message_bot_to_mom($user,$Message,$message_type);
//change seqcode to '0000' defult
            }elseif ($userMessage == 'q'|| $userMessage == 'Q') {
                
                  $case = 1;
                  $seqcode = '0000';
                  $nextseqcode = '0000';
                  $userMessage  = 'ข้ามคำถามเรียบร้อยแล้วค่ะ';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
//Restart chat bot                        
            }elseif ($userMessage == 'เริ่มต้นการใช้งาน') {
                   $delete = (new SqlController)->delete_data_all($user);
                   $userMessage  = 'คุณสนใจผู้ช่วยอัตโนมัติไหม? ';
                   $case = 6; 
//Conditions of award
            }elseif ($userMessage == 'เงื่อนไขการรับสิทธิ์') {
                   $userMessage  = 'เงื่อนไขการร่วมสนุก คุณแม่ตอบคำถามว่าวันนี้คุณแม่ทานอะไรครบ 3 มื้อ รับไปเลย 1 แต้ม แล้วคุณแม่สามารถนำแต้มสะสมมาแลกของรางวัลได้เลยนะคะ';
                   $case=1;
//List of awards    
            }elseif ($userMessage == 'แลกของรางวัล') {
                  $case=1;
                  $seqcode = '5000';
                  $nextseqcode = '5001';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                  (new ReplyMessageController)->replymessage5($replyToken,$user);
//Confirm the redemption.           
             }elseif ($userMessage == 'ยืนยัน' && $sequentsteps->seqcode == '5000') {          
                  $seqcode = '0000';
                  $nextseqcode = '0000';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                  $sequentsteps = (new SqlController)->sequentsteps_seqcode($user);
                  $code_gift = $sequentsteps->answer;
                  $presenting_status = 1;
                  $ins_presenting_gift =  (new SqlController)->ins_presenting_gift($user,$code_gift,$presenting_status);
                  $reward = (new SqlController)->reward_gift2($code_gift); 
                   if($reward == NULL)
                    {
                      $point = 0;
                    }else{
                      $point = $reward->point;
                    }
                  $reward_se =  (new SqlController)->reward_select1($user);
                  $point1 = $reward_se->point;
                   if($point==null)
                    {
                      $point1 = 0;
                    }
            
                  $point2 =$point1-$point;
                  (new SqlController)->update_reward1_point($user,$point2);

                  $case = 31; 
                  $userMessage = 'ยืนยันการแลกของรางวัล คุณแม่เหลือแต้มสะสม '.$point2.' แต้มนะคะ';
                  //หักคะแนนใน reword กับ pesenting_reword
//not Confirm the redemption. 
             }elseif ($userMessage == 'ไม่ยืนยัน' && $sequentsteps->seqcode == '5000') {
                   $reward_se =  (new SqlController)->reward_select1($user);
                  $point = $reward_se->point;
                   if($point==null)
                    {
                      $point = 0;
                    }
                  $case = 31; 
                  $userMessage = 'คุณแม่เหลือแต้มสะสม '.$point.' แต้มนะคะ';
                  $seqcode = '0000';
                  $nextseqcode = '0000';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
//get code reward
            }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '5000') {

                 echo $userMessage;
                  $answer = $userMessage;
                   $code_gift = $userMessage;
                  $reward = (new SqlController)->reward_gift2($code_gift); 
       
                   if($reward == NULL){
                      $point = 0;
                       $case = 1; 
                                  $userMessage = 'ไม่มีของนี้รางวัลร่วมสนุกนะคะ';

                                  $seqcode = '0000';
                                  $nextseqcode = '0000';
                                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);

                    }else{

                      $point = $reward->point;
                      $name_gift = $reward->name_gift;
                      $reward_se =  (new SqlController)->reward_select1($user);
                      $point_user = $reward_se->point;
                      // }
                      $count_gr = (new SqlController)->reward_gift_count($answer);
                          if( $point_user >= $point ){
                                     // if($count_gr  >=1){
                                  $case = 30; 
                                  $userMessage =  $name_gift;
                                  $seqcode = '5000';
                                  $nextseqcode = '5001';
                                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);


                                   (new SqlController)->sequentsteps_update2($user,$answer);
                                  //     }else{
                                  //         $case = 31; 
                                  //         $userMessage = 'ถ้าคุณแม่ไม่ต้องการแลกของรางวัล หรือไม่ต้องการแลกของรางวัล ให้กด Exit เพื่อออกจากหน้าการแลกของรางวัลค่ะ';
                                  // $seqcode = '0000';
                                  // $nextseqcode = '0000';
                                  // $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                                  //     }
                          }else{
                                  $case = 31; 
                                  $userMessage = 'แต้มสะสมของคุณแม่ไม่พอใช้ในการแลกของรางวัลชิ้นนี้นะคะ ถ้าคุณแม่ไม่ต้องการแลกของรางวัล ให้กด Exit เพื่อออกจากหน้าการแลกของรางวัลค่ะ';

                                  $seqcode = '0000';
                                  $nextseqcode = '0000';
                                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                          }  
                    }
                 // echo $point;
                  // $reward = (new SqlController)->reward_gift(); 
                 // foreach($reward as $value){  
                 //  $a = $value->code_gift;
                 //  $point = $value->point;
            }elseif ($userMessage == 'ดูของรางวัล' && $sequentsteps->seqcode == '0000') {
                    $case = 36;
                  
                  // (new ReplyMessageController)->replymessage6($replyToken,$user);
                 
//get reward                 
            }elseif ($userMessage == 'รับของรางวัล' && $sequentsteps->seqcode == '0000') {
                    $case = 1;
      
                  (new ReplyMessageController)->replymessage6($replyToken,$user);
                 
            }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '5001') {
// $userMessage == 'รับของรางวัล'  
                  $code_gift = $userMessage;
                  $presenting_gift_check = (new SqlController)->presenting_gift_check($user,$code_gift); 
                  if($presenting_gift_check>=1){
                  $seqcode = '0000';
                  $nextseqcode = '0000';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                  $presenting_status = 0;
                  $update = (new SqlController)->update_presenting_gift($user,$presenting_status,$code_gift);
                  $case=31;
                  $userMessage = 'รับของรางวัลเรียบร้อย'; 
                  }else{
                  $case=31;
                  $userMessage = 'คุณแม่กดเลือกรับของด้านบน หรือถ้าออกจากการรับของรางวัลให้กด Exit นะคะ'; 

                 }  
//end reward//              
//quiz//             
            // }elseif ($userMessage == 'quiz' || $userMessage == 'Quiz'|| $userMessage == 'QUIZ') {
            
             
            // $date = date('Y-m-d');
            // $select_question = (new SqlController)->select_question($date);
            //      if($select_question==null){
            //         $case = 1;
            //         $userMessage = 'วันนี้ไม่มีคำถามร่วมสนุกนะคะ';
            //         $seqcode = '0000';
            //         $nextseqcode = '0000';
            //            $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                   
            //      }else{

            // $question_num = $select_question->question_num;
            // $code_quiz = $select_question->code_quiz;
            // $question = $select_question->question;
            // $choice1 = $select_question->choice1;
            // $choice2 = $select_question->choice2;
            // $choice3 = $select_question->choice3;

           
            // $answer_status = 0;
                 

            // $select_quizstep =  (new SqlController)->select_quizstep($user,$code_quiz,$question_num);

            // if($select_quizstep == null){
            //    $sequentsteps_insert =  (new SqlController)->insert_quizstep($user,$code_quiz,$question_num,$answer_status);
            //         $seqcode = '4000';
            //         $nextseqcode = '4001';
            //          $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
            //       (new ReplyMessageController)->replymessage3($replyToken,$question,$choice1,$choice2,$choice3);

            //  }else{
            //      if($select_quizstep->answer_status == 0 ){
            //       (new ReplyMessageController)->replymessage3($replyToken,$question,$choice1,$choice2,$choice3);

            //       $seqcode = '4000';
            //       $nextseqcode = '4001';
            //          $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
           
                
            //      }else{
            //        $case = 1; 
            //        $userMessage  = 'วันนี้คุณแม่ได้ร่วมสนุกตอบคำถามไปแล้วค่ะ';
            //        $seqcode = '0000';
            //        $nextseqcode = '0000';
            //           $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                   
            //      }
            //  }

            //      }
                    
           
        
         
                     
            // }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '4000') {
                 
            //       $sq =  (new SqlController)->select_quizstep_user($user);

            //       $code_quiz1 = $sq->code_quiz;
            //       $question_num1 = $sq->question_num;
            //       $sq_ans=  (new SqlController)->select_question_user($code_quiz1,$question_num1);
            //       $answer= $sq_ans->answer;
            //       $choice1= $sq_ans->choice1;
            //       $choice2= $sq_ans->choice2;
            //        $userMess2 = $sq_ans->content_sugg;
            //         $userMessage2 = '📌'.$userMess2;
            //       $question_ans = $userMessage;
            //       $answer_status = 1;

            //       $reward_se =  (new SqlController)->reward_select($user,$code_quiz1);

            //   // if($choice1==$userMessage || $choice2==$userMessage  ){
                 
            //      if($question_ans == $answer){
                  
            //           if($reward_se == null){
            //             $point = 1;
            //             $feq_ans = 1;
            //             $reward_ins =  (new SqlController)->ins_reward($user,$code_quiz1,$point,$feq_ans);

            //           }else{
            //             $p = $reward_se->point;
            //             $feq = $reward_se->feq_ans;

            //             $code_quiz = $code_quiz1;
            //             $question_num = $question_num1 - 1 ;
            //             $select_qs =  (new SqlController)->select_quizstep($user,$code_quiz,$question_num);
                          
            //            if($feq >= 7){
            //               $point = $p+1;
            //               $feq_ans=0;
            //               $reward_up2 = (new SqlController)->update_reward($user,$code_quiz1,$point,$feq_ans);
                            
            //           }
            //              if($select_qs == null){
            //                          $feq_ans = 0;
            //                          $reward_up1 = (new SqlController)->update_reward($user,$code_quiz1,$point,$feq_ans);
            //                   }else{
            //                          $reward_se2 =  (new SqlController)->reward_select($user,$code_quiz1);
            //                          $p1 = $reward_se2->point;
            //                          $feq1 = $reward_se2->feq_ans;
            //                          $point4 = $p1 + 1;
            //                          $feq_ans4 =$feq1+1 ;
            //                          $reward_up1 = (new SqlController)->update_reward($user,$code_quiz1,$point4,$feq_ans4);

            //                   }

            //         }


            //       $reward_se3 =  (new SqlController)->reward_select($user,$code_quiz1);
            //       $point3 = $reward_se3->point;
            //       $case = 1; 
            //       $userMessage1  = '😆 คุณแม่ได้รับpointเพิ่มค่ะ ตอนนี้มีแต้มสะสม '. $point3.' แต้มค่ะ';

            //        $correct_ans = 1;
            //        $qs_up = (new SqlController)->quizstep_update($user,$question_ans,$answer_status,$correct_ans,$code_quiz1,$question_num1);
            //      }else{

            //       /////checkว่ามี user มีrow reward ของ quizปัจจุบัน? ยัง insert มี update
            //        if($reward_se == null){
            //             $point = 0;
            //             $feq_ans = 1;
            //             $reward_ins =  (new SqlController)->ins_reward($user,$code_quiz1,$point,$feq_ans);

            //           }else{
            //             $p = $reward_se->point;
            //             $feq = $reward_se->feq_ans;
           
            //             $code_quiz = $code_quiz1;
            //             $question_num = $question_num1 - 1 ;
            //             $select_qs =  (new SqlController)->select_quizstep($user,$code_quiz,$question_num);
            //                  ////check feq ตอบทุกวัน? checkว่าข้อเมื่อวานตอบไหม

            //             if($feq == 7){
            //               $point = $p+1;
            //               $feq_ans=0;
            //               $reward_up1 = (new SqlController)->update_reward($user,$code_quiz1,$point,$feq_ans);
            //           }
            //            if($select_qs == null){
            //                          $feq_ans = 0;
            //                          $reward_up1 = (new SqlController)->update_reward($user,$code_quiz1,$point,$feq_ans);
            //                   }else{

            //                          $reward_se2 =  (new SqlController)->reward_select($user,$code_quiz1);
            //                          $p1 = $reward_se2->point;
            //                          $feq1 = $reward_se2->feq_ans;
            //                          $point1 = $p1 + 0;
            //                          $feq_ans1 =$feq1+1 ;
            //                          $reward_up1 = (new SqlController)->update_reward($user,$code_quiz1,$point1,$feq_ans1);

            //                   }
            //         }
            //       $reward_se3 =  (new SqlController)->reward_select($user,$code_quiz1);
            //       $point3 = $reward_se3->point;
            //        $case = 1; 
            //        $userMessage1  = '😢 คุณแม่ไม่ได้รับpointเพิ่มค่ะ ตอนนี้มีแต้มสะสม '. $point3.' แต้มค่ะ ไม่เป็นไรนะคะ คุณแม่สามารถร่วมสนุกในวันพรุ่งนี้ได้ค่ะ';
            //        $correct_ans = 0;
            //        $qs_up = (new SqlController)->quizstep_update($user,$question_ans,$answer_status,$correct_ans,$code_quiz1,$question_num1);
            //      }
            //      (new ReplyMessageController)->replymessage2($replyToken,$userMessage1,$userMessage2);
            //       $seqcode = '0000';
            //       $nextseqcode = '0000';
            //       $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);

            //   // }else{
            //   //    $case = 1; 
            //   //      $userMessage  = 'คุณแม่เลือกกดคำตอบข้อใดข้อหนึ่งจากด้านบนเท่านั้นนะคะ';
            //   // }
/////////////////////////////////////////////////
            }elseif ($userMessage == 'ไม่สนใจ'  ) {
                
                  $userMessage = 'ไว้โอกาสหน้าให้เราได้เป็นผู้ช่วยของคุณนะคะ:)';
                  $case = 1; 
//wrong typing                  
            }elseif ($userMessage == 'ไม่ถูกต้อง'  ) {
                  $userMessage = 'กรุณาพิมพ์ใหม่';
                  $case = 1;      
//input name        
            }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '0005' ) {
                 $u = $userMessage;
                if($u == 'แนะนำเมนูอาหาร'|| $u == 'ความรู้เพิ่มเติม'|| $u == 'แนะนำการออกกำลังกาย'|| $u == 'บันทึกข้อมูลคุณแม่'|| $u == 'แนะนำการใช้งาน'){
                      $case = 1;
                      $userMessage = 'กรุณาพิมพ์ชื่อของคุณแม่ก่อนนะคะ';
                  }else{
                      $user_name = $userMessage;
                      $case = 1;
                      $seqcode = '0007';
                      $nextseqcode = '0009';
                      $userMessage  = (new SqlController)->sequents_question($seqcode);
                      $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                      $user_insert = (new SqlController)->user_insert($user,$user_name);
                }
//input age  
            }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '0007' ) {
                  if(is_numeric($userMessage) !== false  && ($userMessage<=60 &&$userMessage>=10)){
                        $answer = $userMessage;
                        $today_years = date("Y");
                        $yearsofbirth = $today_years - $userMessage;
                        $dateofbirth = $yearsofbirth.'-01-01';
                        $case = 1;
                        $update = 2;
                        $seqcode = '0009';
                        $nextseqcode = '0011';
                        $userMessage  = (new SqlController)->sequents_question($seqcode);
                        $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                        $user_update = (new SqlController)->user_update($user,$answer,$update);
                        $update_dateofbirth = (new SqlController)->update_dateofbirth($dateofbirth,$user);

                  }else{
                        $case = 1;
                        $userMessage  = 'อายุตอบเป็นตัวเลขเท่านั้นนะคะ กรุณาพิมพ์ใหม่';
                  }     
//input height
            }elseif (is_string($userMessage) !== false  && $sequentsteps->seqcode == '0009' ) {
                if(is_numeric($userMessage) !== false && ($userMessage<=200 &&$userMessage>=50)){
                        $answer = $userMessage;
                        $case = 1;
                        $update = 3;
                        $seqcode = '0011';
                        $nextseqcode = '0013';
                        $userMessage  = (new SqlController)->sequents_question($seqcode);
                        $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                        $user_update = (new SqlController)->user_update($user,$answer,$update);
                }else{
                        $case = 1;
                        $userMessage  = 'ส่วนสูงตอบเป็นตัวเลขเท่านั้นและหน่วยเซนติเมตรนะคะ กรุณาพิมพ์ใหม่';
                }
//input weight before pregnant                    
            }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '0011' ) {
               if(is_numeric($userMessage) !== false && ($userMessage<=150 &&$userMessage>=20)){
                        $answer = $userMessage;
                        $case = 1;
                        $update = 4;
                        $seqcode = '0013';
                        $nextseqcode = '0015';
                        $userMessage  = (new SqlController)->sequents_question($seqcode);
                        $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                        $user_update = (new SqlController)->user_update($user,$answer,$update);
               }else{
                        $case = 1;
                        $userMessage  = 'น้ำหนักตอบเป็นตัวเลขเท่านั้นและหน่วยเป็นกิโลกรัมนะคะ กรุณาพิมพ์ใหม่';
                }
//input latest period                
            }elseif ($userMessage == 'ครั้งสุดท้ายที่มีประจำเดือน'  && $sequentsteps->seqcode == '0013' ) {
                        $answer = $userMessage;
                        $case = 1;
                        // $update = 5;
                        $seqcode = '1015';
                        $nextseqcode = '0017';

                        $userMessage  = 'ขอทราบครั้งสุดท้ายที่คุณมีประจำเดือนเพื่อคำนวณอายุครรภ์ค่ะ (กรุณาตอบวันที่และเดือนเป็นตัวเลขนะคะ เช่น 17 04 คือ วันที่ 17 เมษายน)';
                        $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                        $answer = 1;
                        $update = 21;
                        $user_update = (new SqlController)->user_update($user,$answer,$update);
                        // $user_update = $this->user_update($user,$answer,$update);
//input birth date
            }elseif ($userMessage == 'กำหนดการคลอด'  && $sequentsteps->seqcode == '0013' ) {
                        $answer = $userMessage;
                        $case = 1;
                        // $update = 5;
                        $seqcode = '2015';
                        $nextseqcode = '0017';
                        $userMessage  = 'ขอทราบกำหนดการคลอดของคุณหน่อยค่ะ (กรุณาตอบวันที่และเดือนเป็นตัวเลขนะคะ เช่น 17 04 คือ วันที่ 17 เมษายน)';
                        $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                        $answer = 2;
                        $update = 21;
                        $user_update = (new SqlController)->user_update($user,$answer,$update);
                        // $user_update = $this->user_update($user,$answer,$update);
//input current weight 
            }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '0013' ) {
                if(is_numeric($userMessage) !== false && ($userMessage<=150 &&$userMessage>=20)){
                        $answer = $userMessage;
                        $case = 2;
                        $update = 5;
                        $seqcode = '0015';
                        $nextseqcode = '0017';
                        $userMessage  = (new SqlController)->sequents_question($seqcode);
                        //$sequentsteps_insert =  $this->sequentsteps_update($user,$seqcode,$nextseqcode);
                        $user_update = (new SqlController)->user_update($user,$answer,$update);
                }else{
                        $case = 1;
                        $userMessage  = 'น้ำหนักตอบเป็นตัวเลขเท่านั้น หน่วยเป็นกิโลกรัม กรุณาพิมพ์ใหม่';
                }     
//confirm latest period or birth date
            }elseif ($userMessage == 'อายุครรภ์ถูกต้อง'  && ($sequentsteps->seqcode == '1015' ||  $sequentsteps->seqcode == '2015')  ) {
                        $answer = $sequentsteps->answer;
                        $case = 1;
                        $update = 6;
                        $seqcode = '0017';
                        $nextseqcode = '0019';
                        $userMessage  = (new SqlController)->sequents_question($seqcode);
                        $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                        $user_update = (new SqlController)->user_update($user,$answer,$update); 
                        $update = 20;
                        $user_update = (new SqlController)->user_update($user,$answer,$update); 

//check date
            }elseif ( is_string($userMessage) !== false && ($sequentsteps->seqcode == '1015' || $sequentsteps->seqcode == '2015') ) {
                        $seqcode = $sequentsteps->seqcode;
                        $userMessage = (new CalController)->pregnancy_calculator($user,$userMessage,$seqcode);

                        if($userMessage == 'ดูเหมือนคุณจะพิมพ์ไม่ถูกต้อง' || strpos($userMessage, 'วันเท่านั้น') !== false  || strpos($userMessage, 'ฉันคิดว่า') !== false ){
                           $case = 1;
                        }else{
                           $case = 3;
                        }
//input telephone number
            }elseif (is_string($userMessage)!== false && $sequentsteps->seqcode == '0017'  ) {
                if(is_numeric($userMessage) !== false && strlen($userMessage) == 10){
                        $answer = $userMessage;
                        $case = 1;
                        $update = 7;
                        $seqcode = '0019';
                        $nextseqcode = '0021';
                        $userMessage  = (new SqlController)->sequents_question($seqcode);
                        $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                        $user_update = (new SqlController)->user_update($user,$answer,$update);
                }else{
                        $case = 1;
                        $userMessage = 'ฉันคิดว่าคุณพิมพ์เบอร์โทรศัพท์ผิดนะคะ กรุณาพิมพ์ใหม่';
                }
//input email
            }elseif (is_string($userMessage)!== false && $sequentsteps->seqcode == '0019'  ) {
                if(strpos($userMessage, '@') !== false || strpos($userMessage, '-') !== false){
                        $answer = $userMessage;
                        $case = 17;
                        $update = 8;
                        $seqcode = '0021';
                        $nextseqcode = '0023';
                        $userMessage  = (new SqlController)->sequents_question($seqcode);
                        $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                        $user_update = (new SqlController)->user_update($user,$answer,$update); 
                }else{
                        $case = 1;
                        $userMessage = 'ฉันคิดว่าคุณพิมพ์อีเมลผิดนะ กรุณาพิมพ์ใหม่';
                }
//input hospital name                 
            }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '0021'  ) {
              if($userMessage == 'โรงพยาบาลธรรมศาสตร์' || $userMessage == 'โรงพยาบาลศิริราช' ){
                 $answer = $userMessage;
                        $case = 10;
                        $update = 9;
                        $seqcode = '0027';
                        $nextseqcode = '0025';
                        $userMessage  = (new SqlController)->sequents_question($seqcode);
                        $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                        $user_update = (new SqlController)->user_update($user,$answer,$update); 
              }else{
                $case = 1;
                $userMessage = 'กรุณากดเลือกโรงพยาบาลที่ปุ่มนะคะ';

              }   
            // }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '0023'  ) {
            //       $answer = $userMessage;
            //       $case = 10;
            //       $update = 10;
            //       //$seqcode = '0025';
            //       $seqcode = '0027';
            //       $nextseqcode = '0027';
            //       $userMessage  = $this->sequents_question($seqcode);
            //       $sequentsteps_insert =  $this->sequentsteps_update($user,$seqcode,$nextseqcode);
            //       $user_update = $this->user_update($user,$answer,$update);

            // }elseif ($userMessage == 'แพ้ยา' && $sequentsteps->seqcode == '0025'  ) {
            //       $answer = $userMessage;
            //       $case = 1;
            //       $userMessage  = 'คุณแพ้ยาอะไรคะ?';
            //       $seqcode = '0025_1';
            //       $nextseqcode = '0031';
            //       $sequentsteps_insert =  $this->sequentsteps_update($user,$seqcode,$nextseqcode);

            // }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '0025_1'  ) {
            //       $answer = $userMessage;
            //       $case = 10;
            //       $update = 11;
            //       $seqcode = '0027';
            //       $nextseqcode = '0029';
            //       $userMessage  = $this->sequents_question($seqcode);
            //       $sequentsteps_insert =  $this->sequentsteps_update($user,$seqcode,$nextseqcode);
            //       $user_update = $this->user_update($user,$answer,$update); 
//input food allergy
            }elseif ($userMessage == 'แพ้อาหาร' && $sequentsteps->seqcode == '0027'  ) {
                    $answer = $userMessage;
                    $case = 1;
                    $userMessage  = 'คุณแพ้อาหารอะไรคะ?';
                    $seqcode = '0027_1';
                    $nextseqcode = '0031';
                    $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
//input food allergy
            }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '0027_1'  ) {
                if($userMessage == 'แนะนำเมนูอาหาร'|| $userMessage == 'ความรู้เพิ่มเติม'|| $userMessage == 'แนะนำการออกกำลังกาย'|| $userMessage == 'บันทึกข้อมูลคุณแม่'|| $userMessage == 'แนะนำการใช้งาน'){
                    $case = 1;
                    $userMessage = 'กรุณาตอบคำถามด้านบนก่อนนะคะ หรือพิมพ์ Q เพื่อข้ามคำถามด้านบนค่ะ';
                  }else{
                    $answer = $userMessage;
                    $case = 4;
                    $update = 12;
                    $seqcode = '0029';
                    $nextseqcode = '0031';
                    $userMessage  = (new SqlController)->sequents_question($seqcode);
                    $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                    $user_update = (new SqlController)->user_update($user,$answer,$update); 
                }
            // }elseif ($userMessage == 'ไม่แพ้ยา' && $sequentsteps->seqcode == '0025'  ) {
            //       $answer = $userMessage;
            //       $case = 10;
            //       $update = 11;
            //       $seqcode = '0027';
            //       $nextseqcode = '0029';
            //       $userMessage  = $this->sequents_question($seqcode);
            //       $sequentsteps_insert =  $this->sequentsteps_update($user,$seqcode,$nextseqcode);
            //       $user_update = $this->user_update($user,$answer,$update); 

            // }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '0027'  ) {
            //       $answer = $userMessage;
            //       $case = 4;
            //       $update = 12;
            //       $seqcode = '0029';
            //       $nextseqcode = '0031';
            //       $userMessage  = (new SqlController)->sequents_question($seqcode);
            //       $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
            //       $user_update = (new SqlController)->user_update($user,$answer,$update); 
//input Not allergic to food
            }elseif ($userMessage == 'ไม่แพ้อาหาร' && $sequentsteps->seqcode == '0027'  ) {
                    $answer = $userMessage;
                    $case = 4;
                    $update = 12;
                    $seqcode = '0029';
                    $nextseqcode = '0031';
                    $userMessage  = (new SqlController)->sequents_question($seqcode);
                    $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                    $user_update = (new SqlController)->user_update($user,$answer,$update); 
//input excercise lavel
            }elseif ($userMessage == 'เบา' ||$userMessage == 'ปานกลาง' || $userMessage == 'หนัก'  ) {
                      //||$userMessage== 'ดูข้อมูล'
                    if ($userMessage=='หนัก'  ) {
                        $answer= 3;
                    }elseif($userMessage=='ปานกลาง') {
                        $answer = 2;
                    }else{
                        $answer = 1;
                    }
                    $case = 5;
                    $update = 13;
                    // if($userMessage== 'ดูข้อมูล'){
                    //    $seqcode = '0041';
                    //    $nextseqcode = '0000';
                    //    $sequentsteps_insert =  $this->sequentsteps_update($user,$seqcode,$nextseqcode);
                    // }
                    //$userMessage  = (new checkmessageController)->user_data($user);
                    (new ReplyMessageController)->resultinfo_regis($replyToken,$user);
                    $user_update = (new SqlController)->user_update($user,$answer,$update);
                    $seqcode = '0029';
                    $nextseqcode = '0031';
                    $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                //     $reward_count = (new SqlController)->reward_count($user);
                // if($reward_count == 0 ){
                //     $point = 0;
                //     $feq_ans_meals = 0;
                //     $feq_ans_week =0;
                //     $reward_ins =  (new SqlController)->ins_reward1($user,$point,$feq_ans_week,$feq_ans_meals);
                // }
//verify
            }elseif (($userMessage == 'ยืนยันข้อมูล' && $sequentsteps->seqcode == '0029') || ($userMessage == 'ยืนยันข้อมูล' && $sequentsteps->seqcode == '0040' ) || $userMessage == 'ยืนยันข้อมูล' ) {
                      //Model::count()
                    $num = RecordOfPregnancy::where('user_id', $user)
                                      ->whereNull('deleted_at')
                                      ->count();
        
                    $users_register = (new SqlController)->users_register_select($user);
                    $preg_week = $users_register->preg_week;
                    $user_Pre_weight = $users_register->user_Pre_weight;
                    $user_weight = $users_register->user_weight;

                    $user_height =  $users_register->user_height;
                    $status =  $users_register->status;

                    $bmi  = (new CalController)->bmi_calculator($user_Pre_weight,$user_height);
                    
                    $user_age =  $users_register->user_age;
                    $active_lifestyle =  $users_register->active_lifestyle;
                    $weight_criteria  = (new CalController)->weight_criteria($bmi);
                    $weight_cur = $users_register->preg_week_str;

                    // $weight_status  = (new CalController)->weight_criteria_status($bmi,$user,$weight_cur);
                   
                    $cal  = (new CalController)->cal_calculator($user_age,$active_lifestyle,$user_Pre_weight,$preg_week);

                    $update = 22;
                    $answer = $cal;
                    $update_user = (new SqlController)->user_update($user,$answer,$update);

                  if ($bmi>=24.9 ) {
                      $text = 'น้ำหนักของคุณเกินเกณฑ์ ลองปรับการรับประทานอาหารหรือออกกำลังกายดูไหมคะ'."\n".
                         'หากคุณแม่ไม่ทราบว่าจะทานอะไรดีหรือออกกำลังกายแบบไหนดีสามารถกดที่ MENU ด้านล่างได้เลยนะคะ';
                  }else{
                      $text = 'หากคุณแม่ไม่ทราบว่าจะทานอะไรดีหรือออกกำลังกายแบบไหนดีสามารถกดที่ MENU ด้านล่างได้เลยนะคะ';
                  }
            
                  // if( $sequentsteps->seqcode == '0029'){

                   

                  if($num <= 1){  
                        $weight_status = NULL;
                        $RecordOfPregnancy = (new SqlController)->RecordOfPregnancy_insert($preg_week, $user_weight,$user,$weight_status);
                   }else{


                    // $weight_status  = (new CalController)->weight_criteria_status($bmi,$user,$weight_cur);

                    $RecordOfPregnancy = RecordOfPregnancy::where('user_id', $user)
                         ->whereNull('deleted_at')
                         ->orderBy('updated_at', 'asc')
                         ->first();
                    $created_at = $RecordOfPregnancy->created_at;
                 
                    // $RecordOfPregnancy = RecordOfPregnancy::where('user_id', $user)
                    //         ->where('created_at', $created_at)
                    //         ->where('preg_week',$preg_week)
                    //         ->update(['preg_weight' =>$user_weight,'preg_week' =>$preg_week,'weight_status'=>$weight_status]);

                    $num1 =  RecordOfPregnancy::where('user_id', $user)
                                      ->where('preg_week',$preg_week)
                                      ->count(); 

                        if($num1 <= 1){
                                $weight_status = NULL;
                                $RecordOfPregnancy = (new SqlController)->RecordOfPregnancy_insert($preg_week, $user_weight,$user,$weight_status);
                        }else{

                               $weight_status = NULL;
                               $RecordOfPregnancy = RecordOfPregnancy::where('user_id', $user)
                                                                      // ->where('created_at', $created_at)
                                                                      ->where('preg_week',$preg_week)
                                                                      ->update(['preg_weight' =>$user_weight,'preg_week' =>$preg_week,'weight_status'=>$weight_status]);

                        }
                          
                   }
                 if($status == '4'){
                        $users_register = users_register::where('user_id', $user)
                                                          ->whereNull('deleted_at')
                                                          ->update(['status' => '1']);
                             
                 }
      
                  // }else{
                  // $delete = $this->RecordOfPregnancy_delete($user);
                  // $RecordOfPregnancy = $this->RecordOfPregnancy_insert($preg_week, $user_weight,$user);
                  // }
  /////////////////รูปกราฟ//////////////////////
                  $format = (new SqlController)->sequentsteps_update2($user,$cal);
                  $seqcode = '0000';
                  $nextseqcode = '0000';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                  $a = (new ReplyMessageController)->replymessage_result($replyToken,$preg_week,$bmi,$cal,$weight_criteria,$text,$user);

      
          
                    //    $url = "https://peat.none.codes/graph/".$user; 
                 
                    // //call Google PageSpeed Insights API
                    // $googlePagespeedData = file_get_contents("https://www.googleapis.com/pagespeedonline/v2/runPagespeed?url=$url&screenshot=true");

                    // // //decode json data
                    // $googlePagespeedData = json_decode($googlePagespeedData, true);

                    // //screenshot data
                    // $screenshot = $googlePagespeedData['screenshot']['data'];
                    // $screenshot = str_replace(array('_','-'),array('/','+'),$screenshot);
                    // $name_of_screenshot = uniqid().'.png';

                    // // // display screenshot image
                    // $data = "data:image/jpeg;base64,".$screenshot;


                    // $img = Image::make($data);
                    // $filename  = uniqid().'.jpg';
                    // $path = 'uploads/' . $filename;
                    // $img->save($path);

                    // $sequentsteps = sequentsteps::where('sender_id', $user)
                    //                             ->update(['answer'=>$filename]);
////////////////////////////////////////////////////////////////////////////////////////
            }elseif ($userMessage == 'ทารกในครรภ์') {
                  $users_register = (new SqlController)->users_register_select($user);     
                  $preg_week = $users_register->preg_week;
                  $pregnants = (new SqlController)->pregnants($preg_week);
                  $descript = $pregnants->descript;
                  $userMessage  =  $descript;
                  $case = 1; 

            }elseif ($userMessage == 'ข้อมูลโภชนาการ') {
                  $users_register = (new SqlController)->users_register_select($user);
                
                  $preg_week = $users_register->preg_week;

                  $user_Pre_weight = $users_register->user_Pre_weight;
                  $user_weight = $users_register->user_weight;
                  $user_height =  $users_register->user_height;

                  $bmi  = (new CalController)->bmi_calculator($user_Pre_weight,$user_height);
                  
                  $user_age =  $users_register->user_age;
                  $active_lifestyle =  $users_register->active_lifestyle;
                  $weight_criteria  = (new CalController)->weight_criteria($bmi);
                  $cal  = (new CalController)->cal_calculator($user_age,$active_lifestyle,$user_Pre_weight,$preg_week);
                  $meal_planing = (new SqlController)->meal_planing($cal);

                  // $meal_planing1 = (new checkmessageController)->meal_planing($cal);
                
                   // dd($meal_planing) ;

                  //  $case = 1;  
                   (new ReplyMessageController)->replymessage7($replyToken,$user);
//edit data
            }elseif ($userMessage == 'แก้ไขข้อมูล') {

                   $seqcode = '0040';
                   $nextseqcode = '0000';
                   $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                   $userMessage = 'พิมพ์เพียงแค่เลขตามด้านล่างนี้เพื่อแก้ไข'. "\n".
                                  'พิมพ์ "1" ชื่อ '. "\n".
                                  'พิมพ์ "2" อายุ '. "\n".
                                  'พิมพ์ "3" ส่วนสูง '."\n".
                                  'พิมพ์ "4" น้ำหนักก่อนตั้งครรภ์ '."\n".
                                  'พิมพ์ "5" น้ำหนักปัจจุบัน '."\n".
                                  'พิมพ์ "6" อายุครรภ์ '."\n".
                                  'พิมพ์ "7" เบอร์โทรศัพท์ '."\n".
                                  'พิมพ์ "8" อีเมล '."\n".
                                  'พิมพ์ "9" โรงพยาบาลที่ฝากครรภ์ '."\n".
                                  // 'พิมพ์ "10" เลขประจำตัวผู้ป่วย '."\n".
                                  // 'พิมพ์ "11" แพ้ยา '."\n".
                                  'พิมพ์ "10" แพ้อาหาร ';
                   $case = 1;  
            }elseif (is_numeric($userMessage) !== false && $sequentsteps->seqcode == '0040' && $userMessage <=12) {
                switch($userMessage) {
                 case '1' : 
                       $userMessage = 'ขอทราบชื่อและนามสกุลของคุณแม่อีกครั้งค่ะ';
                       $case = 1;
                       $seqcode = '0140' ;
                       $nextseqcode = '0000';
                    
                    break;
                 case '2' : 
                       $seqcode = '0007' ;
                       $case = 1;
                       $userMessage  = (new SqlController)->sequents_question($seqcode);
                       $seqcode = '0240' ;
                       $nextseqcode = '0000';
        
                    break;
                 case '3' : 
                       $seqcode = '0009' ;
                       $case = 1;
                       $userMessage  = (new SqlController)->sequents_question($seqcode);
                       $seqcode = '0340' ;
                       $nextseqcode = '0000';
                    break;
                 case '4' : 
                       $seqcode = '0011' ;
                       $case = 1;
                       $userMessage  = (new SqlController)->sequents_question($seqcode);
                       $seqcode = '0440' ;
                       $nextseqcode = '0000';
                    break;
                 case '5' : 
                       $seqcode = '0013' ;
                       $case = 1;
                       $userMessage  = (new SqlController)->sequents_question($seqcode);
                       $seqcode = '0540' ;
                       $nextseqcode = '0000';

                    break;
                 case '6' : 
                       $seqcode = '0015' ;
                       $case = 1;
                       $userMessage  = (new SqlController)->sequents_question($seqcode);
                       $seqcode = '0640' ;
                       $nextseqcode = '0000';
                       $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                       $case = 2;
                        return (new ReplyMessageController)->replymessage($replyToken,$userMessage,$case);
                    break;
                 case '7' : 
                       $seqcode = '0017' ;
                       $case = 1;
                       $userMessage  = (new SqlController)->sequents_question($seqcode);
                       $seqcode = '0740' ;
                       $nextseqcode = '0000';
                    break;
                 case '8' : 
                       $seqcode = '0019' ;
                       $case = 1;
                       $userMessage  = (new SqlController)->sequents_question($seqcode);
                       $seqcode = '0840' ;
                       $nextseqcode = '0000';
                    break;
                 case '9' : 
                       $seqcode = '0021' ;
                       $case = 17;
                       $userMessage  = (new SqlController)->sequents_question($seqcode);
                       $seqcode = '0940' ;
                       $nextseqcode = '0000';
                    break;
                 // case '10' : 
                 //       $seqcode = '0023' ;
                 //       $case = 1;
                 //       $userMessage  = $this->sequents_question($seqcode);
                 //       $seqcode = '1040' ;
                 //       $nextseqcode = '0000';
                 //    break;
                 // case '11' : 
                 //       $seqcode = '0025' ;
                 //       $case = 9;
                 //       $userMessage  = $this->sequents_question($seqcode);
                 //       $seqcode = '1140' ;
                 //       $nextseqcode = '0000';
                 //    break;
                 case '10' : 
                       $seqcode = '0027' ;
                       $case = 10;
                       $userMessage  = (new SqlController)->sequents_question($seqcode);
                       $seqcode = '1240' ;
                       $nextseqcode = '0000';
                    break;
                }
                   
                    $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
             }elseif ($userMessage == 'แก้ไข:ชื่อ' || $userMessage == 'แก้ไข:อายุ'|| $userMessage =='แก้ไข:ส่วนสูง'|| $userMessage =='แก้ไข:น้ำหนักก่อนตั้งครรภ์'|| $userMessage =='แก้ไข:น้ำหนักปัจจุบัน'|| $userMessage =='แก้ไข:อายุครรภ์'|| $userMessage =='แก้ไข:เบอร์โทรศัพท์'|| $userMessage =='แก้ไข:อีเมล์' || $userMessage =='แก้ไข:โรงพยาบาลที่ฝากครรภ์'|| $userMessage =='แก้ไข:แพ้อาหาร'|| $userMessage =='แก้ไข:ภาวะแทรกซ้อนเบาหวาน'|| $userMessage =='แก้ไข:ภาวะแทรกซ้อนความดัน'|| $userMessage =='แก้ไข:ภาวะแทรกซ้อนเจ็บครรภ์คลอดก่อนกำหนด') {
          
                  $pieces = explode(":", $userMessage);
                  $pieces1  = str_replace("","",$pieces[1]);
                  $case = 1;


                  if($pieces1=='ชื่อ'){
                    $userMessage = 1 ;
                  }elseif($pieces1=='อายุ'){
                    $userMessage = 2 ;
                  }elseif($pieces1=='ส่วนสูง'){
                    $userMessage = 3 ;
                  }elseif($pieces1=='น้ำหนักก่อนตั้งครรภ์'){
                    $userMessage = 4 ;
                  }elseif($pieces1=='น้ำหนักปัจจุบัน'){
                    $userMessage = 5 ;
                  }elseif($pieces1=='อายุครรภ์'){
                    $userMessage = 6 ;
                  }elseif($pieces1=='เบอร์โทรศัพท์'){
                    $userMessage = 7 ;
                  }elseif($pieces1=='อีเมล์'){
                    $userMessage = 8 ;
                  }elseif($pieces1=='โรงพยาบาลที่ฝากครรภ์'){
                    $userMessage = 9 ;
                  }elseif($pieces1=='แพ้อาหาร'){ 
                    $userMessage = 10 ;
                  }elseif($pieces1=='ภาวะแทรกซ้อนเบาหวาน'){ 
                    $userMessage = 11 ;
                  }elseif($pieces1=='ภาวะแทรกซ้อนความดัน'){
                    $userMessage = 12 ;
                  }elseif($pieces1=='ภาวะแทรกซ้อนเจ็บครรภ์คลอดก่อนกำหนด'){
                    $userMessage = 13 ; 
                  }

                  
                switch($userMessage) {
                 case '1' : 
                       $userMessage = 'ขอทราบชื่อและนามสกุลของคุณแม่อีกครั้งค่ะ';
                       $case = 1;
                       $seqcode = '0140' ;
                       $nextseqcode = '0000';
                    
                    break;
                 case '2' : 
                       $seqcode = '0007' ;
                       $case = 1;
                       $userMessage  = (new SqlController)->sequents_question($seqcode);
                       $seqcode = '0240' ;
                       $nextseqcode = '0000';
        
                    break;
                 case '3' : 
                       $seqcode = '0009' ;
                       $case = 1;
                       $userMessage  = (new SqlController)->sequents_question($seqcode);
                       $seqcode = '0340' ;
                       $nextseqcode = '0000';
                    break;
                 case '4' : 
                       $seqcode = '0011' ;
                       $case = 1;
                       $userMessage  = (new SqlController)->sequents_question($seqcode);
                       $seqcode = '0440' ;
                       $nextseqcode = '0000';
                    break;
                 case '5' : 
                       $seqcode = '0013' ;
                       $case = 1;
                       $userMessage  = (new SqlController)->sequents_question($seqcode);
                       $seqcode = '0540' ;
                       $nextseqcode = '0000';

                    break;
                 case '6' : 
                       // $seqcode = '0015' ;
                       $case = 1;
                       // $userMessage  = (new SqlController)->sequents_question($seqcode);
                       // $seqcode = '0640' ;
                       $seqcode = '0000' ;
                       $nextseqcode = '0000';
                       $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                       // $case = 2;
                       //  return (new ReplyMessageController)->replymessage($replyToken,$userMessage,$case);
                       $userMessage  = 'ไม่สามารถแก้ไขอายุครรภ์ได้ค่ะ';
                    break;
                 case '7' : 
                       $seqcode = '0017' ;
                       $case = 1;
                       $userMessage  = (new SqlController)->sequents_question($seqcode);
                       $seqcode = '0740' ;
                       $nextseqcode = '0000';
                    break;
                 case '8' : 
                       $seqcode = '0019' ;
                       $case = 1;
                       $userMessage  = (new SqlController)->sequents_question($seqcode);
                       $seqcode = '0840' ;
                       $nextseqcode = '0000';
                    break;
                 case '9' : 
                       $seqcode = '0021' ;
                       $case = 17;
                       $userMessage  = (new SqlController)->sequents_question($seqcode);
                       $seqcode = '0940' ;
                       $nextseqcode = '0000';
                    break;
                 // case '10' : 
                 //       $seqcode = '0023' ;
                 //       $case = 1;
                 //       $userMessage  = $this->sequents_question($seqcode);
                 //       $seqcode = '1040' ;
                 //       $nextseqcode = '0000';
                 //    break;
                 // case '11' : 
                 //       $seqcode = '0025' ;
                 //       $case = 9;
                 //       $userMessage  = $this->sequents_question($seqcode);
                 //       $seqcode = '1140' ;
                 //       $nextseqcode = '0000';
                 //    break;
                 case '10' : 
                       $seqcode = '0027' ;
                       $case = 10;
                       $userMessage  = (new SqlController)->sequents_question($seqcode);
                       $seqcode = '1240' ;
                       $nextseqcode = '0000';
                    break;
///////ภาวะแทรกซ้อน
                 case '11' : 
                       // $seqcode = '0032' ;
                       $case = 40;
                       $userMessage  ='คุณแม่มีภาวะแทรกซ้อนเบาหวานหรือไม่';
                       $seqcode = '1340' ;
                       $nextseqcode = '0000';
                    break;
                  case '12' : 
                       // $seqcode = '0033' ;
                       $case = 40;
                       $userMessage  = 'คุณแม่มีภาวะแทรกซ้อนความดันโลหิตสูงหรือไม่';
                       $seqcode = '1440' ;
                       $nextseqcode = '0000';
                    break;
                  case '13' : 
                       // $seqcode = '0034' ;
                       $case = 40;
                       $userMessage  = 'คุณแม่มีภาวะแทรกซ้อนเจ็บครรภ์คลอดก่อนกำหนดหรือไม่';
                       $seqcode = '1540' ;
                       $nextseqcode = '0000';
                    break;


                }
                   
                    $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);

////////update info
            }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '0140') {
       
                  $answer = $userMessage;
                  $case = 1;
                  //$seqcode = '0040';
                  $seqcode = '0000';
                  $nextseqcode = '0000';
                  $update = 1;
                  $user_update = (new SqlController)->user_update($user,$answer,$update); 
                  // $userMessage  = (new checkmessageController)->user_data($user);
                  $userMessage = 'แก้ไขชื่อคุณแม่เรียบร้อยแล้วค่ะ';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);

            }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '0240') {

                if(is_numeric($userMessage) !== false && ($userMessage<=60 &&$userMessage>=10)){
                  $answer = $userMessage;
                  $today_years = date("Y");
                  $yearsofbirth = $today_years - $userMessage;
                  $dateofbirth = $yearsofbirth.'-01-01';
                
                  // $case = 5;
                  // $seqcode = '0040';
                  $case = 1;
                  $seqcode = '0000';
                  $nextseqcode = '0000';
                  $update = 2;
                  $user_update = (new SqlController)->user_update($user,$answer,$update); 
                  // $userMessage  = (new checkmessageController)->user_data($user);
                  $userMessage = 'แก้ไขอายุคุณแม่เรียบร้อยแล้วค่ะ';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                  $update_dateofbirth = (new SqlController)->update_dateofbirth($dateofbirth,$user);
                       
  
                }else{
                  $case = 1;
                  $userMessage = 'อายุตอบเป็นตัวเลขเท่านั้นนะคะ กรุณาพิมพ์ใหม่';
                }

             }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '0340') {

               if(is_numeric($userMessage) !== false && ($userMessage<=200 &&$userMessage>=50)){
                  $answer = $userMessage;
                  $case = 1;
                  $seqcode = '0000';
                  // $case = 5;
                  // $seqcode = '0040';
                  $nextseqcode = '0000';
                  $update = 3;
                  $user_update = (new SqlController)->user_update($user,$answer,$update); 
                  // $userMessage  = (new checkmessageController)->user_data($user);
                  $userMessage = 'แก้ไขส่วนสูงคุณแม่เรียบร้อยแล้วค่ะ';

                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);

                }else{
                  $case = 1;
                  $userMessage = 'ส่วนสูงตอบเป็นตัวเลขเท่านั้นและหน่วยเซนติเมตรนะคะ กรุณาพิมพ์ใหม่';
                }
             }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '0440') {

                if(is_numeric($userMessage) !== false && ($userMessage<=150 &&$userMessage>=20)){
                  $answer = $userMessage;

                    $users_register = (new SqlController)->users_register_select($user);
                    $user_Pre_weight = $users_register->user_Pre_weight;
                    $user_height =  $users_register->user_height;
                    $weight_status =  $users_register->weight_status;
                    $bmi  = (new CalController)->bmi_calculator($user_Pre_weight,$user_height);
                    $weight_cur = $userMessage;
                    if($weight_status=='4'){
                      $weight_status  = 'ภาวะแทรกซ้อน';
                    }else{
                      $weight_status  = (new CalController)->weight_criteria_status($bmi,$user,$weight_cur);
                    }
                    
                    // $up = (new SqlController)->update_weight_status($user,$weight_status);


                  $case = 1;
                  $seqcode = '0000';
                  // $case = 5;
                  // $seqcode = '0040';
                  $nextseqcode = '0000';
                  $update = 4;
                  $user_update = (new SqlController)->user_update($user,$answer,$update); 
                  // $userMessage  = (new checkmessageController)->user_data($user);
                  $userMessage = 'แก้ไขน้ำหนักคุณแม่เรียบร้อยแล้วค่ะ';

                   
                   

                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                }else{
                  $case = 1;
                  $userMessage = 'น้ำหนักตอบเป็นตัวเลขเท่านั้นและหน่วยเป็นกิโลกรัมนะคะ กรุณาพิมพ์ใหม่';
                }
                 

             }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '0540') {
                if(is_numeric($userMessage) !== false && ($userMessage<=150 &&$userMessage>=20)){
                  $answer = $userMessage;

                   $users_register = (new SqlController)->users_register_select($user);
                   $preg_week = $users_register->preg_week;

                   $tracker_update = RecordOfPregnancy::where('user_id', $user)
                                         ->where('preg_week', $preg_week)
                                         ->whereNull('deleted_at')
                                         ->update(['preg_weight' =>$answer]);
                  $case = 1;
                  $seqcode = '0000';
                  // $case = 5;
                  // $seqcode = '0040';
                  $nextseqcode = '0000';
                  $update = 5;
                  $user_update = (new SqlController)->user_update($user,$answer,$update); 
                  $users_register = (new SqlController)->users_register_select($user);
                  $user_Pre_weight = $users_register->user_Pre_weight;
                  $user_height =  $users_register->user_height;
                  $bmi  = (new CalController)->bmi_calculator($user_Pre_weight,$user_height);
                  $weight_cur = $userMessage;
                  $weight_status =  $users_register->weight_status;
                    if($weight_status=='4'){
                      $weight_status  = 'ภาวะแทรกซ้อน';
                    }else{
                      $weight_status  = (new CalController)->weight_criteria_status($bmi,$user,$weight_cur);
                    }
                  // $userMessage  = (new checkmessageController)->user_data($user);
                  $userMessage = 'แก้ไขน้ำหนักคุณแม่เรียบร้อยแล้วค่ะ';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);

                }else{
                  $case = 1;
                  $userMessage = 'น้ำหนักตอบเป็นตัวเลขเท่านั้นและหน่วยเป็นกิโลกรัมนะคะ กรุณาพิมพ์ใหม่';
                }
                 

             }elseif ($userMessage == 'ครั้งสุดท้ายที่มีประจำเดือน' && $sequentsteps->seqcode == '0640') {
                  $answer = $userMessage;
                  $case = 1;
                  $seqcode = '10640';
                  $nextseqcode = '0000';
                  $userMessage  = 'ขอทราบครั้งสุดท้ายที่คุณมีประจำเดือนเพื่อคำนวณอายุครรภ์ค่ะ (กรุณาตอบวันที่และเดือนเป็นตัวเลขนะคะ เช่น 17 04 คือ วันที่ 17 เมษายน)';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);

             }elseif ($userMessage == 'กำหนดการคลอด' && $sequentsteps->seqcode == '0640') {
                 $answer = $userMessage;
                  $case = 1;
                  $seqcode = '20640';
                  $nextseqcode = '0000';
                  $userMessage  = 'ขอทราบกำหนดการคลอดของคุณหน่อยค่ะ (กรุณาตอบวันที่และเดือนเป็นตัวเลขนะคะ เช่น 17 04 คือ วันที่ 17 เมษายน';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
             }elseif ($userMessage == 'อายุครรภ์ถูกต้อง'  && ($sequentsteps->seqcode == '10640' ||  $sequentsteps->seqcode == '20640')  ) {
                  $answer = $sequentsteps->answer;
                  // $case = 5;
                  // $seqcode = '0040';
                  $case = 1;
                  $seqcode = '0000';
                  $nextseqcode = '0000';
                  $update = 6;
                  $user_update = (new SqlController)->user_update($user,$answer,$update);
                  $count_rows = (new SqlController)->RecordOfPregnancy_select_str($user);
                  if($count_rows <= 1){
                    $update = 20;
                    $user_update = (new SqlController)->user_update($user,$answer,$update);  
                  }
                  // $userMessage  = (new checkmessageController)->user_data($user);
                  $userMessage = 'แก้ไขอายุครรภ์คุณแม่เรียบร้อยแล้วค่ะ';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                 
            }elseif ( is_string($userMessage) !== false   && ($sequentsteps->seqcode == '10640' || $sequentsteps->seqcode == '20640') ) {
                  $seqcode = $sequentsteps->seqcode;
                  $userMessage = (new CalController)->pregnancy_calculator($user,$userMessage,$seqcode);

            if($userMessage == 'ดูเหมือนคุณจะพิมพ์ไม่ถูกต้อง' || strpos($userMessage, 'วันเท่านั้น') !== false ||strpos($userMessage, 'ฉันคิดว่า') !== false ){
                     $case = 1;
                  }else{
                     $case = 3;
                  }
      
            }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '0740') {

                if(is_numeric($userMessage) !== false && strlen($userMessage) == 10){
                  $answer = $userMessage;
                  $case = 1;
                  $seqcode = '0000';
                  // $case = 5;
                  // $seqcode = '0040';
                  $nextseqcode = '0000';
                  $update = 7;
                  $user_update = (new SqlController)->user_update($user,$answer,$update); 
                  // $userMessage  = (new checkmessageController)->user_data($user);
                  $userMessage = 'แก้ไขเบอร์โทรศัพท์คุณแม่เรียบร้อยแล้วค่ะ';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                }else{
                  $case = 1;
                  $userMessage = 'ฉันคิดว่าคุณพิมพ์เบอร์โทรศัพท์ผิดนะคะ กรุณาพิมพ์ใหม่';
                }
                      
            }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '0840') {
        

                 if(strpos($userMessage, '@') !== false || strpos($userMessage, '-') !== false){
                      $answer = $userMessage;
                      // $case = 5;
                      // $seqcode = '0040';
                      $case = 1;
                      $seqcode = '0000';
                      $nextseqcode = '0000';
                      $update = 8;
                      $user_update = (new SqlController)->user_update($user,$answer,$update); 
                      // $userMessage  = (new checkmessageController)->user_data($user);
                      $userMessage = 'แก้ไขอีเมลคุณแม่เรียบร้อยแล้วค่ะ';
                      $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                  }else{
                    $case = 1;
                    $userMessage = 'ฉันคิดว่าคุณพิมพ์อีเมลผิดนะ กรุณาพิมพ์ใหม่';
                  }
                

            }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '0940') {
                 if($userMessage == 'โรงพยาบาลธรรมศาสตร์' || $userMessage == 'โรงพยาบาลศิริราช' ){
        
                 
              
                  $answer = $userMessage;
                  // $case = 5;
                  // $seqcode = '0040';
                  $case = 1;
                  $seqcode = '0000';
                  $nextseqcode = '0000';
                  $update = 9;
                  $user_update = (new SqlController)->user_update($user,$answer,$update); 
                  // $userMessage  = (new checkmessageController)->user_data($user);
                  $userMessage = 'แก้ไขโรงพยาลที่ฝากครรภ์เรียบร้อยแล้วค่ะ';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                  }else{
                $case = 1;
                $userMessage = 'กรุณากดเลือกโรงพยาบาลที่ปุ่มใหม่อีกครั้งนะคะ';

              }   
                             
            }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '1040') {
       
                  $answer = $userMessage;
                  // $case = 5;
                  // $seqcode = '0040';
                  $case = 1;
                  $seqcode = '0000';
                  $nextseqcode = '0000';
                  $update = 10;
                  $user_update = (new SqlController)->user_update($user,$answer,$update); 
                  // $userMessage  = (new checkmessageController)->user_data($user);
                  $userMessage = 'แก้ไขเรียบร้อยแล้วค่ะ';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
            }elseif (  $userMessage == 'แพ้ยา'&& $sequentsteps->seqcode == '1140') {
    
                  $answer = $userMessage;
                  $case = 1;
                  $userMessage  = 'คุณแพ้ยาอะไรคะ?';
                  // $seqcode = '0040';
                  // $nextseqcode = '0000';
                  // $update = 11;
                  // $user_update = $this->user_update($user,$answer,$update); 
                  // $userMessage  = $this->user_data($user);
                  // $sequentsteps_insert =  $this->sequentsteps_update($user,$seqcode,$nextseqcode);

            }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '1140') {
      
                  $answer = $userMessage;
                  // $case = 5;
                  // $seqcode = '0040';
                  $case = 1;
                  $seqcode = '0000';
                  $nextseqcode = '0000';
                  $update = 11;
                  $user_update = (new SqlController)->user_update($user,$answer,$update); 
                  // $userMessage  = (new checkmessageController)->user_data($user);
                  $userMessage = 'แก้ไขประวัติการแพ้อาหารของคุณแม่เรียบร้อยแล้วค่ะ';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
            }elseif (  $userMessage == 'แพ้อาหาร'&& $sequentsteps->seqcode == '1240') {
    
                  $answer = $userMessage;
                  $case = 1;
                  $userMessage  = 'คุณแพ้อาหารอะไรคะ?';
            }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '1240') {
       
                  $answer = $userMessage;
                  // $case = 5;
                  // $seqcode = '0040';
                  $case = 1;
                  $seqcode = '0000';
                  $nextseqcode = '0000';
                  $update = 12;
                  $user_update = (new SqlController)->user_update($user,$answer,$update); 
                  // $userMessage  = (new checkmessageController)->user_data($user);
                  $userMessage = 'แก้ไขประวัติการแพ้อาหารของคุณแม่เรียบร้อยแล้วค่ะ';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);


            }elseif (is_string($userMessage) !== false && ($sequentsteps->seqcode == '1340' ||$sequentsteps->seqcode == '1440' || $sequentsteps->seqcode == '1540' ) ) {
                  $answer = $userMessage;

                if($sequentsteps->seqcode == '1340' && $userMessage=='มีภาวะแทรกซ้อน'){
                    $update = 16;
                    $answer = 1;
                }elseif($sequentsteps->seqcode == '1340' && $userMessage=='ไม่มีภาวะแทรกซ้อน'){
                    $update = 16;
                    $answer = 0;
                }elseif($sequentsteps->seqcode == '1440' && $userMessage=='มีภาวะแทรกซ้อน'){
                    $update = 17;
                    $answer = 1;
                }elseif($sequentsteps->seqcode == '1440' && $userMessage=='ไม่มีภาวะแทรกซ้อน'){
                    $update = 17;
                    $answer = 0;
                }elseif($sequentsteps->seqcode == '1540' && $userMessage=='มีภาวะแทรกซ้อน'){
                    $update = 18;
                    $answer = 1;
                }elseif($sequentsteps->seqcode == '1540' && $userMessage=='ไม่มีภาวะแทรกซ้อน'){
                    $update = 18;
                    $answer = 0;
                }
                $user_update = (new SqlController)->user_update($user,$answer,$update); 
             

                $compli_status = (new SqlController)->check_w_status($user);
                // echo $compli_status;
                  if($compli_status > 0 ){
                     $up = 19;
                     $a = 4;
                     $weight_status = (new SqlController)->user_update($user,$a,$up); 
                  
                  }else{
///cal status weight
                     //$up = 19;
                     
                  $users_register = (new SqlController)->users_register_select($user);
                
                  $preg_week = $users_register->preg_week;

                  $user_Pre_weight = $users_register->user_Pre_weight;
                  $user_weight = $users_register->user_weight;
                  $user_height =  $users_register->user_height;

                  $bmi  = (new CalController)->bmi_calculator($user_Pre_weight,$user_height);
                  
                  $user_age =  $users_register->user_age;
                  $active_lifestyle =  $users_register->active_lifestyle;
                  $weight_criteria  = (new CalController)->weight_criteria($bmi);
                  $cal  = (new CalController)->cal_calculator($user_age,$active_lifestyle,$user_Pre_weight,$preg_week);
                  $weight_cur = $answer;
                  $weight_status =  $users_register->weight_status;
                  $weight_cur = $user_weight;
                  $weight_status  = (new CalController)->weight_criteria_status($bmi,$user,$weight_cur);
                     //$weight_status = (new SqlController)->user_update($user,$a,$up); 
                  }


             
                  $case = 1;
                  $seqcode = '0000';
                  $nextseqcode = '0000';
                  $userMessage = 'บันทึกภาวะแทรกซ้อนระหว่างตั้งครรภ์ของคุณแม่เรียบร้อยแล้วค่ะ';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);



///ถามน้ำหนักทุกวันจันทร์            
            }elseif ($userMessage == 'น้ำหนักถูกต้อง' && $sequentsteps->seqcode == '1003' ) {
                  // $case = 7;
                  $seqcode = '0000';
                  $nextseqcode = '0000';
                  $sequentsteps = (new SqlController)->sequentsteps_seqcode($user);
                  $user_weight = $sequentsteps->answer;
                  

                  $RecordOfPregnancy = (new SqlController)->RecordOfPregnancy_select($user);
                  $updated_at = $RecordOfPregnancy->updated_at;
               
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                 
                  $update = 5;
                  $answer = $user_weight;
                  $update_user = (new SqlController)->user_update($user,$answer,$update);
                  // $userMessage = $user;
                  // $replymessage = $this->replymessage($replyToken,$userMessage,$case);

                  $users_register = (new SqlController)->users_register_select($user);
                
                  $preg_week = $users_register->preg_week;

                  $user_Pre_weight = $users_register->user_Pre_weight;
                  $user_weight = $users_register->user_weight;
                  $user_height =  $users_register->user_height;

                  $bmi  = (new CalController)->bmi_calculator($user_Pre_weight,$user_height);
                  
                  $user_age =  $users_register->user_age;
                  $active_lifestyle =  $users_register->active_lifestyle;
                  $weight_criteria  = (new CalController)->weight_criteria($bmi);
                  $cal  = (new CalController)->cal_calculator($user_age,$active_lifestyle,$user_Pre_weight,$preg_week);
                  $weight_cur = $answer;

                  $update1 = 22;
                  // $answer = $cal;
                  $update_user = (new SqlController)->user_update($user,$cal,$update1);
                  
                  $weight_status =  $users_register->weight_status;
                    if($weight_status=='4'){
                      $weight_status  = 'ภาวะแทรกซ้อน';
                    }else{
                      $weight_status  = (new CalController)->weight_criteria_status($bmi,$user,$weight_cur);
                    }

               
                       $num = RecordOfPregnancy::where('user_id', $user)
                                    ->whereNull('deleted_at')
                                    ->where('preg_week',$preg_week)
                                    ->count();

                if ($bmi>=24.9 ) {
                    $text = 'น้ำหนักของคุณเกินเกณฑ์ ลองปรับการรับประทานอาหารหรือออกกำลังกายดูไหมคะ'."\n".
                       'หากคุณแม่ไม่ทราบว่าจะทานอะไรดีหรือออกกำลังกายแบบไหนดีสามารถกดที่เมนูกิจกรรมด้านล่างได้เลยนะคะ';
                }else{
                    $text = 'หากคุณแม่ไม่ทราบว่าจะทานอะไรดีหรือออกกำลังกายแบบไหนดีสามารถกดที่เมนูกิจกรรมด้านล่างได้เลยนะคะ';
                }
               
                // if( $sequentsteps->seqcode == '0029'){

                if($num==0)         
                 {  
                        $RecordOfPregnancy = (new SqlController)->RecordOfPregnancy_insert($preg_week, $user_weight,$user,$weight_status);
                 }else{

                   // $RecordOfPregnancy = RecordOfPregnancy::where('user_id', $user)
                   //     ->where('deleted_status', '1')
                   //     ->orderBy('updated_at', 'asc')
                   //     ->first();
                   // $created_at = $RecordOfPregnancy->created_at;
               
                    // $RecordOfPregnancy = RecordOfPregnancy::where('user_id', $user)
                    //       ->where('created_at', $created_at)
                    //       ->where('preg_week',$preg_week)
                    //       ->update(['preg_weight' =>$user_weight,'preg_week' =>$preg_week]);

                        $num1 =  RecordOfPregnancy::where('user_id', $user)
                                    ->where('preg_week',$preg_week)
                                    ->count(); 

                       // if($num1 == 0){
                       //    $RecordOfPregnancy = $this->RecordOfPregnancy_insert($preg_week, $user_weight,$user);
                       // }else{
                         $RecordOfPregnancy = RecordOfPregnancy::where('user_id', $user)
                          // ->where('created_at', $created_at)
                          ->where('preg_week',$preg_week)
                          ->update(['preg_weight' =>$user_weight,'preg_week' =>$preg_week,'weight_status'=>$weight_status]);
                       // }
                        
                 }

    
                // }else{
                // $delete = $this->RecordOfPregnancy_delete($user);
                // $RecordOfPregnancy = $this->RecordOfPregnancy_insert($preg_week, $user_weight,$user);
                // }
                $date =  $preg_week ;
                $RecordOfPregnancy = (new SqlController)->RecordOfPregnancy_update($user_weight,$user,$date);
                $format = (new SqlController)->sequentsteps_update2($user,$cal);
                $seqcode = '0000';
                $nextseqcode = '0000';
                $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                $users_register =   (new SqlController)->users_register_select($user);
                $key = $users_register->ulife_connect;
                $api_weight = (new ApiController)->setgraph_api($key,$user);
         
                return (new ReplyMessageController)->replymessage_result($replyToken,$preg_week,$bmi,$cal,$weight_criteria,$text,$user);
//น้ำหนักทุกวันจันทร์
            }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '1003' ) {

                if(is_numeric($userMessage) !== false){
                  $answer = $userMessage;
                  $case = 8;    
                  $seqcode = '1003';
                  $nextseqcode = '0000'; 
                  $sequentsteps = sequentsteps::where('sender_id', $user)
                                              ->update(['seqcode' =>$seqcode,'answer'=>$answer,'nextseqcode' => $nextseqcode]);

                  $replymessage = (new ReplyMessageController)->replymessage($replyToken,$userMessage,$case,$user);
                }else{
                  $case = 1;
                  $userMessage = 'กรุณาตอบเป็นตัวเลขเท่านั้นค่ะ กรุณาพิมพ์ใหม่';
                }
                 
//ถามทุกวันเกี่ยวกับการกินอาหาร/ออกกำลังกาย/วิตามิน
            }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '2001'  ) {

               if($userMessage == 'แนะนำเมนูอาหาร'|| $userMessage == 'ความรู้เพิ่มเติม'|| $userMessage == 'แนะนำการออกกำลังกาย'|| $userMessage == 'บันทึกข้อมูลคุณแม่'|| $userMessage == 'แนะนำการใช้งาน'){
                      $case = 1;
                      $userMessage = 'กรุณาตอบคำถามด้านบนก่อนนะคะ หรือพิมพ์ Q เพื่อข้ามคำถามด้านบนค่ะ';
                  }else{

                   if((new checkmessageController)->match($array6, $userMessage) ){
                        $userMessage1 = 'อย่าลืมทานข้าวนะคะ และคุณแม่อย่าลืมบันทึกย้อนหลังที่ บันทึกข้อมูลอาหารเย็นด้วยนะคะ';
                        $userMessage2  = 'ทานขนมหรือของว่างระหว่างวันไหมคะ';
                      (new ReplyMessageController)->replymessage2($replyToken,$userMessage1,$userMessage2);
                    }else{
                        $tracker1 = $userMessage;
                        //$tracker_update =  $this->tracker_update($user,$column,$tracker);
                        $case = 1;
                        // $update = 8;
                        $userMessage  = 'ทานขนมหรือของว่างระหว่างวันไหมคะ?';

                   }
                  $seqcode = '2002_1';
                  $nextseqcode = '2003';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                  // $userMessage  = $this->sequents_question($seqcode);
                       $num = tracker::where('user_id', $user)
                                    ->whereNull('deleted_at')
                                    ->count();
                      if($num==0)         
                   {    
                         $tracker= 'NULL';
                         $tracker_insert =  (new SqlController)->tracker_insert1($user,$tracker);
                         $tracker= $tracker1 ;
                         $column = 'dinner';
                         $tracker_update = (new SqlController)->tracker_update($user,$column,$tracker);
                   }else{
                         $tracker= $tracker1 ;
                         $column = 'dinner';
                         $tracker_update = (new SqlController)->tracker_update($user,$column,$tracker); 
                   }
                }

            }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '2002_1'  ) {
                 if($userMessage == 'แนะนำเมนูอาหาร'|| $userMessage == 'ความรู้เพิ่มเติม'|| $userMessage == 'แนะนำการออกกำลังกาย'|| $userMessage == 'บันทึกข้อมูลคุณแม่'|| $userMessage == 'แนะนำการใช้งาน'){
                      $case = 1;
                      $userMessage = 'กรุณาตอบคำถามด้านบนก่อนนะคะ หรือพิมพ์ Q เพื่อข้ามคำถามด้านบนค่ะ';
                  }else{
                  $tracker = $userMessage;
                  $column = 'dessert_din';
                  $tracker_update =  (new SqlController)->tracker_update($user,$column,$tracker);
                  $case = 11;
                  // $update = 8;
                  $seqcode = '2002';
                  $nextseqcode = '2003';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                  $userMessage  = (new SqlController)->sequents_question($seqcode);
                  }          
            
            }elseif ($userMessage == 'ทานแล้ว'  && $sequentsteps->seqcode == '2002'  ) {

                  $tracker = '1';
                  $column = 'vitamin';
                  $tracker_update =  (new SqlController)->tracker_update($user,$column,$tracker);
                  $case = 12;
                  // $update = 8;
                  $seqcode = '2003';
                  $nextseqcode = '2004';
                  $userMessage  = (new SqlController)->sequents_question($seqcode);
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                 

            }elseif ($userMessage == 'ยังไม่ได้ทาน' && $sequentsteps->seqcode == '2002'  ) {
                  $tracker = '0';
                  $column = 'vitamin';
                  $tracker_update =  (new SqlController)->tracker_update($user,$column,$tracker);  
                  $case = 12;
                  // $update = 8;
                  $seqcode = '2003';
                  $nextseqcode = '2004';
                  $userMessage  = (new SqlController)->sequents_question($seqcode);
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                 

            }elseif ($userMessage == 'ออกแล้ว'  && $sequentsteps->seqcode == '2003'  ) {
                  $answer = $userMessage;
                  $case = 1;
                  // $update = 8;
                  $seqcode = '2004';
                  $nextseqcode = '0000';
                  $userMessage  = (new SqlController)->sequents_question($seqcode);
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);  

            }elseif ($userMessage == 'ยัง'  && $sequentsteps->seqcode == '2003'  ) {
                  $tracker = $userMessage;
                  $column = 'exercise';
                  $tracker_update = (new SqlController)->tracker_update($user,$column,$tracker); 
                  $case = 1;
                  // $update = 8;
                  $seqcode = '0000';
                  $nextseqcode = '0000';

                 
                  //    $reward_se =  (new SqlController)->reward_select1($user);
                  //    $feq_ans_week = $reward_se->feq_ans_week;
                  //    $feq_ans_meals = $reward_se->feq_ans_meals;

                  //     if($reward_se == null){
                  //       $point = 0;
                  //       $feq_ans_meals = 1;
                  //       $feq_ans_week =0;
                  //       $reward_ins =  (new SqlController)->ins_reward1($user,$point,$feq_ans_week,$feq_ans_meals);
                  //            $u1  = '😋คุณแม่สามารถสะสมแต้มจากการตอบคำถามทุกวันนะคะ สะสมแต้มเพื่อแลกของรางวัลค่ะ';

                  //     }else{


                  //         if($feq_ans_week>=7){
                  //             $p = $reward_se->point;
                  //             $point = $p+1;
                  //             $feq_ans_week = 0;
                  //             $feq_ans_meals = 0;
                  //             $select_qs =  (new SqlController)->update_reward1($user,$point,$feq_ans_week,$feq_ans_meals);
                  //             $u1  = '😆ยินดีด้ววยค่ะ คุณแม่ได้รับแต้มสะสมเพิ่ม 1 แต้ม จากการตอบคำถามทุกวันเป็นเวลา 1 สัปดาห์ค่ะ';
                  //         }elseif($feq_ans_meals>=2 ){
                  //             $p = $reward_se->point;
                  //             $point = $p+1;
                  //             $feqweek = $reward_se->point;
                  //             $feq_ans_week = $feqweek+1;
                  //             $feq_ans_meals = 0;
                  //             $select_qs =  (new SqlController)->update_reward1($user,$point,$feq_ans_week,$feq_ans_meals);

                  //             $u1  = '😆คุณแม่ได้รับแต้มสะสม 1 แต้ม จากการตอบคำถามวันนี้ค่ะ';

                  //         }elseif($feq_ans_meals>=2 && $feq_ans_week>=7){
                  //             $p = $reward_se->point;
                  //             $point = $p+2;
                  //             $feq_ans_week = 0;
                  //             $feq_ans_meals = 0;
                  //             $select_qs =  (new SqlController)->update_reward1($user,$point,$feq_ans_week,$feq_ans_meals);
                  //             $u1  = '😆คุณแม่ได้รับแต้มสะสม 2 แต้ม จากการตอบคำถามวันนี้ และตอบคำถามทุกวันเป็นเวลา 1 สัปดาห์ค่ะ';

                  //         }else{
                  //             $p = $reward_se->point;
                  //             $point = $p+0;
                  //             $feq_ans_week = 0;
                  //             $feq_ans_meals = 0;
                  //             $select_qs =  (new SqlController)->update_reward1($user,$point,$feq_ans_week,$feq_ans_meals);
                  //             $u1  = 'วันนี้คุณแม่ไม่ได้รับแต้มจากการตอบคำถามค่ะ พรุ่งนี้มาตอบคำถามกันนะคะจะได้รับแต้มสะสม';
                  //         }
                          
                  //     }  
                  // $reward_se2 =  (new SqlController)->reward_select1($user);
                  // $point = $reward_se2->point;  
              
                  // $userMessage1 = $u1.' ตอนนี้คุณแม่มีแต้มสะสม '.$point.' แต้มค่ะ';
                  // $userMessage2  = 'อย่าลืมออกกำลังกายนะคะ เรามีคำแนะนำการออกกำลังกายให้คุณกดที่menuด้านล่างได้เลยค่ะ';

                  // (new ReplyMessageController)->replymessage2($replyToken,$userMessage1,$userMessage2);
                  // $sequentsteps_insert =  $this->sequentsteps_update($user,$seqcode,$nextseqcode);
                  // $user_update = $this->user_update($user,$answer,$update);
                  $userMessage  = 'อย่าลืมออกกำลังกายนะคะ เรามีคำแนะนำการออกกำลังกายและการทานอาหารให้คุณแม่กดที่ MENU ด้านล่างได้เลยนะคะ';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
              
            }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '2004'  ) {
                 if($userMessage == 'แนะนำเมนูอาหาร'|| $userMessage == 'ความรู้เพิ่มเติม'|| $userMessage == 'แนะนำการออกกำลังกาย'|| $userMessage == 'บันทึกข้อมูลคุณแม่'|| $userMessage == 'แนะนำการใช้งาน'){
                      $case = 1;
                      $userMessage = 'กรุณาตอบคำถามด้านบนก่อนนะคะ หรือพิมพ์ Q เพื่อข้ามคำถามด้านบนค่ะ';
                  }else{
                  $tracker = $userMessage;
                  $column = 'exercise';
                  $tracker_update = (new SqlController)->tracker_update($user,$column,$tracker); 
                  $case = 1;
                  // $update = 8;
                  $seqcode = '0000';
                  $nextseqcode = '0000';
                  $userMessage  = 'เรามีคำแนะนำการออกกำลังกายและการทานอาหารให้คุณแม่กดที่ MENU ด้านล่างได้เลยนะคะ';
                      $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
               // (new ReplyMessageController)->replymessage($replyToken,$userMessage);
                  }
             
               //    $reward_se =  (new SqlController)->reward_select1($user);
               //    $feq_ans_week = $reward_se->feq_ans_week;
               //    $feq_ans_meals = $reward_se->feq_ans_meals;

               //        if($reward_se == null){
               //          $point = 0;
               //          $feq_ans_meals = 1;
               //          $feq_ans_week =0;
               //          $reward_ins =  (new SqlController)->ins_reward1($user,$point,$feq_ans_week,$feq_ans_meals);
               //               $u1  = 'คุณแม่สามารถสะสมแต้มจากการตอบคำถามทุกวันนะคะ สะสมแต้มเพื่อแลกของรางวัลค่ะ😋';

               //        }else{


               //            if($feq_ans_week>=7){
               //                $p = $reward_se->point;
               //                $point = $p+1;
               //                $feq_ans_week = 0;
               //                $feq_ans_meals = 0;
               //                $select_qs =  (new SqlController)->update_reward1($user,$point,$feq_ans_week,$feq_ans_meals);
               //                $u1  = 'ยินดีด้ววยค่ะ คุณแม่ได้รับแต้มสะสมเพิ่ม 1 แต้ม จากการตอบคำถามทุกวันเป็นเวลา 1 สัปดาห์ค่ะ😆';
               //            }elseif($feq_ans_meals>=2 ){
               //                $p = $reward_se->point;
               //                $point = $p+1;
               //                $feqweek = $reward_se->point;
               //                $feq_ans_week = $feqweek+1;
               //                $feq_ans_meals = 0;
               //                $select_qs =  (new SqlController)->update_reward1($user,$point,$feq_ans_week,$feq_ans_meals);

               //                $u1  = 'คุณแม่ได้รับแต้มสะสม 1 แต้ม จากการตอบคำถามวันนี้ค่ะ😆';

               //            }elseif($feq_ans_meals>=2 && $feq_ans_week>=7){
               //                $p = $reward_se->point;
               //                $point = $p+2;
               //                $feq_ans_week = 0;
               //                $feq_ans_meals = 0;
               //                $select_qs =  (new SqlController)->update_reward1($user,$point,$feq_ans_week,$feq_ans_meals);
               //                $u1  = 'คุณแม่ได้รับแต้มสะสม 2 แต้ม จากการตอบคำถามวันนี้ และตอบคำถามทุกวันเป็นเวลา 1 สัปดาห์ค่ะ😆';

               //            }else{
               //                $p = $reward_se->point;
               //                $point = $p+0;
               //                $feq_ans_week = 0;
               //                $feq_ans_meals = 0;
               //                $select_qs =  (new SqlController)->update_reward1($user,$point,$feq_ans_week,$feq_ans_meals);
               //                $u1  = '☺วันนี้คุณแม่ไม่ได้รับแต้มจากการตอบคำถามค่ะ มาตอบคำถามกันนะคะจะได้รับแต้มสะสม ไว้แลกของรางวัลค่ะ';
               //            }
                          
               //        }  
               //    $reward_se2 =  (new SqlController)->reward_select1($user);
               //    $point = $reward_se2->point;  
              
               //    $userMessage1 = $u1."\n".'ตอนนี้คุณแม่มีแต้มสะสม '.$point.' แต้มค่ะ';
               //    $userMessage2  = 'อย่าลืมออกกำลังกายนะคะ เรามีคำแนะนำการออกกำลังกายให้คุณกดที่menuด้านล่างได้เลยค่ะ';

                  
               //    $userMessage2  = 'เรามีคำแนะนำการออกกำลังกายให้คุณกดด้านล่างได้เลย';
              // $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
              //  (new ReplyMessageController)->replymessage2($replyToken,$userMessage1,$userMessage2);

              
//////ถามตอนเช้า
            }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '2005'  ) {

                  if($userMessage == 'แนะนำเมนูอาหาร'|| $userMessage == 'ความรู้เพิ่มเติม'|| $userMessage == 'แนะนำการออกกำลังกาย'|| $userMessage == 'บันทึกข้อมูลคุณแม่'|| $userMessage == 'แนะนำการใช้งาน'){
                      $case = 1;
                      $userMessage = 'กรุณาตอบคำถามด้านบนก่อนนะคะ หรือพิมพ์ Q เพื่อข้ามคำถามด้านบนค่ะ';
                  }else{

                    if((new checkmessageController)->match($array6, $userMessage) ){
                      $userMessage1 = 'อย่าลืมทานข้าวนะคะ และคุณแม่อย่าลืมบันทึกย้อนหลังที่ บันทึกข้อมูลอาหารเช้าด้วยนะคะ';
                       $userMessage2  = '😋';
                      (new ReplyMessageController)->replymessage2($replyToken,$userMessage1,$userMessage2);
                    }else{
                      $tracker = $userMessage;
                      // $tracker_insert =  $this->tracker_insert1($user,$tracker);
                      $column = 'breakfast';
                      $tracker_update = (new SqlController)->tracker_update($user,$column,$tracker); 
                     // dd($tracker_update);
                      $userMessage  = '😋';

                      $date = date('d-m-Y');
                      $dt = DateTime::createFromFormat('d-m-Y', $date  )->format('Y-m-d');   
                       (new ApiController)->check_ulife_tracker_edit($user,$dt);
                    } 
                      $case = 1;
                      $seqcode = '0000';
                      $nextseqcode = '0000';
                      $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                          // $reward_se =  (new SqlController)->reward_select1($user);

                          // if($reward_se == null){
                          //   $point = 0;
                          //   $feq_ans_meals = 1;
                          //   $feq_ans_week =0;
                          //   $reward_ins =  (new SqlController)->ins_reward1($user,$point,$feq_ans_week,$feq_ans_meals);

                          // }else{
                          //   $point = $reward_se->point;
                          //   $feq_ans_week = $reward_se->feq_ans_week;
                          //   $feq_ans_meals = 1 ;
                          //   $select_qs =  (new SqlController)->update_reward1($user,$point,$feq_ans_week,$feq_ans_meals);
                          // }    
                }
//////ถามตอนกลางวัน
            }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '2006'  ) {
            

                 if($userMessage == 'แนะนำเมนูอาหาร'|| $userMessage == 'ความรู้เพิ่มเติม'|| $userMessage == 'แนะนำการออกกำลังกาย'|| $userMessage == 'บันทึกข้อมูลคุณแม่'|| $userMessage == 'แนะนำการใช้งาน'){
                        $case = 1;
                        $userMessage = 'กรุณาตอบคำถามด้านบนก่อนนะคะ หรือพิมพ์ Q เพื่อข้ามคำถามด้านบนค่ะ';
                  }else{
                        if((new checkmessageController)->match($array6, $userMessage) ){
                            $userMessage1 = 'อย่าลืมทานข้าวนะคะ และคุณแม่อย่าลืมบันทึกย้อนหลังที่ บันทึกข้อมูลอาหารกลางวันด้วยนะคะ';
                            $userMessage2  = 'ทานขนมหรือของว่างระหว่างวันไหมคะ?';
                            (new ReplyMessageController)->replymessage2($replyToken,$userMessage1,$userMessage2);
                        }else{
                                $case = 1;
                                $tracker1 = $userMessage;
                                 $userMessage  = 'ทานขนมหรือของว่างระหว่างวันไหมคะ?';
                              $num = tracker::where('user_id', $user)
                                            ->whereNull('deleted_at')
                                            ->count();
                         if($num==0)         
                           {    
                                 $tracker= 'NULL';
                                 $tracker_insert =  (new SqlController)->tracker_insert1($user,$tracker);
                                 $column = 'lunch';
                                 $tracker= $tracker1 ;
                                 $tracker_update = (new SqlController)->tracker_update($user,$column,$tracker);
                           }else{
                                 $column = 'lunch';
                                 $tracker= $tracker1 ;
                                 $tracker_update = (new SqlController)->tracker_update($user,$column,$tracker); 
                           }
                          $date = date('d-m-Y');
                          $dt = DateTime::createFromFormat('d-m-Y', $date  )->format('Y-m-d');   
                           (new ApiController)->check_ulife_tracker_edit($user,$dt);

                       }
                      $seqcode = '2007';
                      $nextseqcode = '2008';
                 
                      $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);

                  }
                   
                     
            }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '2007'  ) {

                 if($userMessage == 'แนะนำเมนูอาหาร'|| $userMessage == 'ความรู้เพิ่มเติม'|| $userMessage == 'แนะนำการออกกำลังกาย'|| $userMessage == 'บันทึกข้อมูลคุณแม่'|| $userMessage == 'แนะนำการใช้งาน'){
                      $case = 1;
                      $userMessage = 'กรุณาตอบคำถามด้านบนก่อนนะคะ หรือพิมพ์ Q เพื่อข้ามคำถามด้านบนค่ะ';
                  }else{

                   if((new checkmessageController)->match($array6, $userMessage) ){
                            $userMessage1 = 'คุณแม่สามารถบันทึกอาหารว่างย้อนหลังได้นะคะ ไปที่บันทึกอาหารว่างย้อนหลังค่ะ';
                            $userMessage2  = '😋';
                            (new ReplyMessageController)->replymessage2($replyToken,$userMessage1,$userMessage2);
                        }else{
                      $tracker = $userMessage;
                      $column = 'dessert_lu';
                      $tracker_update = (new SqlController)->tracker_update($user,$column,$tracker); 
                      $userMessage  = '😋';

                          // $reward_se =  (new SqlController)->reward_select1($user);

                          // if($reward_se == null){
                          //   $point = 0;
                          //   $feq_ans_meals = 1;
                          //   $feq_ans_week =0;
                          //   $reward_ins =  (new SqlController)->ins_reward1($user,$point,$feq_ans_week,$feq_ans_meals);

                          // }else{
                          //   $point = $reward_se->point;
                          //   $feq_ans_week = $reward_se->feq_ans_week;
                          //   $feqmeals = $reward_se->feq_ans_meals;
                          //   $feq_ans_meals = $feqmeals+1;
                          //   $select_qs =  (new SqlController)->update_reward1($user,$point,$feq_ans_week,$feq_ans_meals);
                          // }    

                      $date = date('d-m-Y');
                      $dt = DateTime::createFromFormat('d-m-Y', $date  )->format('Y-m-d');   
                       (new ApiController)->check_ulife_tracker_edit($user,$dt);
                  }
                     $case = 1;
                  $seqcode = '0000';
                  $nextseqcode = '0000';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                }
               
            }elseif ($userMessage == 'test'  ) {
                  $case = 1;
                  (new ReplyMessageController)->replymessage7($replyToken,$user);
                  // $seqcode = '3009';
                  // $nextseqcode = '3010';
                  // $sequentsteps_insert =  $this->sequentsteps_update($user,$seqcode,$nextseqcode);
///[บันทึกย้อนหลังเช้า]     
/////ดูบันทึกย้อนหลัง             
            // }elseif ($userMessage == 'บันทึกอาหารย้อนหลัง'  ) {
            //       $case = 25;
                  // $seqcode = '3009';
                  // $nextseqcode = '3010';
                  // $sequentsteps_insert =  $this->sequentsteps_update($user,$seqcode,$nextseqcode);
///[บันทึกย้อนหลังเช้า]
            }elseif (strpos($userMessage, 'บันทึกมื้อเช้า:') !== false) {
                  $pieces = explode(":", $userMessage);
                  $pieces1  = str_replace("","",$pieces[1]);
                  $answer = $pieces1;
                  $case = 1;

                  $seqcode = '3011';
                  $nextseqcode = '3012';
                  $userMessage = 'มื้อเช้าคุณแม่ทานอะไรไปบ้างคะ?';
                  $sequentsteps_insert =  $sequentsteps = sequentsteps::where('sender_id', $user)
                                                                      ->update(['answer'=>$answer,'seqcode'=>$seqcode,'nextseqcode'=>$nextseqcode]);


                 //  $qcode = '3010';
                 //  $nextseqcode = '3011';
                 //  $userMessage ='คุณแม่ต้องการบันทึกย้อนหลังวันไหนค่ะ? พิมพ์ในรูปแบบนี้นะคะ 01-12-2018 (วัน-เดือน-ปี) ค่ะ หรือ พิมพ์ Q เพื่อข้ามคำถามนี้ค่ะ';
                
                 // $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
            // }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '3010' ) {

            //    if($userMessage == 'แนะนำเมนูอาหาร'|| $userMessage == 'คำถามที่ถามบ่อย'|| $userMessage == 'แนะนำการออกกำลังกาย'|| $userMessage == 'บันทึกข้อมูลคุณแม่'|| $userMessage == 'แนะนำการใช้งาน' ){
            //           $case = 1;
            //           $userMessage = 'กรุณาตอบคำถามด้านบนก่อนนะคะ หรือ พิมพ์ Q เพื่อข้ามคำถามด้านบนค่ะ';
            //     }else{
            //       $case = 1;
            //       $seqcode = '3011';
            //       $nextseqcode = '3012';
            //       $answer = $userMessage;

            //       $dt = DateTime::createFromFormat('d-m-Y', $userMessage)->format('Y-m-d');
            //       $num = tracker::where('user_id', $user)
            //                         ->whereNull('deleted_at')
            //                         ->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), $dt)
            //                         ->count();


              
            //       if($num >= '1' ){
            //         $userMessage = 'มื้อนี้คุณแม่ทานอะไรไปบ้างค่ะ';
            //         $sequentsteps_insert =  $sequentsteps = sequentsteps::where('sender_id', $user)
            //                                                              ->update(['answer'=>$answer,'seqcode'=>$seqcode,'nextseqcode'=>$nextseqcode]);
            //       }else{
                    
            //        // $userMessage = $a;
            //         $userMessage = 'คุณแม่อาจใส่ตัวเลขไม่ตรงตามรูปแบบ หรืออาจจะไม่มีวันที่ๆ ตรงกับวันที่ๆจะบันทึกค่ะ พิมพ์ใหม่หรือพิมพ์ Q เพื่อข้ามคำถามด้านบนค่ะ ';
            //       }

            //     }
                 
            }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '3011'  ) {
                if($userMessage == 'แนะนำเมนูอาหาร'|| $userMessage == 'ความรู้เพิ่มเติม'|| $userMessage == 'แนะนำการออกกำลังกาย'|| $userMessage == 'บันทึกข้อมูลคุณแม่'|| $userMessage == 'แนะนำการใช้งาน'){
                      $case = 1;
                      $userMessage = 'กรุณาตอบคำถามด้านบนก่อนนะคะ';
                 }else{
                      $sequentsteps = (new SqlController)->sequentsteps_seqcode($user);
                      $date = $sequentsteps->answer;
                      $dt = DateTime::createFromFormat('d-m-Y', $date)->format('Y-m-d');    

                      $tracker_update = tracker::where('user_id', $user)
                                             ->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), $dt)
                                             ->update(['breakfast' =>$userMessage]);

                      $seqcode = '0000';
                      $nextseqcode = '0000';
                      $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                      $case = 1;
                      $userMessage  = 'บันทึกมื้อเช้าเรียบร้อยแล้วนะคะ';
                       (new ApiController)->check_ulife_tracker_edit($user,$dt);
                  }

///[บันทึกย้อนหลังกลางวัน]
            }elseif (strpos($userMessage, 'บันทึกมื้อกลางวัน:') !== false) {

                  $pieces = explode(":", $userMessage);
                  $pieces1  = str_replace("","",$pieces[1]);
                  $answer = $pieces1;

                  $case = 1;
                  $seqcode = '3011_1';
                  $nextseqcode = '0000';
                  $userMessage = 'มื้อกลางวันคุณแม่ทานอะไรไปบ้างคะ?';
                  $sequentsteps_insert =  $sequentsteps = sequentsteps::where('sender_id', $user)
                                                                         ->update(['answer'=>$answer,'seqcode'=>$seqcode,'nextseqcode'=>$nextseqcode]);


                  // $userMessage ='คุณแม่ต้องการบันทึกย้อนหลังวันไหนค่ะ? พิมพ์ในรูปแบบนี้นะคะ 01-12-2018 (วัน-เดือน-ปี) ค่ะ หรือ พิมพ์ Q เพื่อข้ามคำถามนี้ค่ะ';
                 // $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);

            // }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '3010_1' ) {

            //      if($userMessage == 'แนะนำเมนูอาหาร'|| $userMessage == 'คำถามที่ถามบ่อย'|| $userMessage == 'แนะนำการออกกำลังกาย'|| $userMessage == 'บันทึกข้อมูลคุณแม่'|| $userMessage == 'แนะนำการใช้งาน'){
            //           $case = 1;
            //           $userMessage = 'กรุณาตอบคำถามด้านบนก่อนนะคะ หรือ พิมพ์ Q เพื่อข้ามคำถามด้านบนค่ะ';
            //     }else{
            //       $case = 1;
            //       $seqcode = '3011_1';
            //       $nextseqcode = '3012_1';
            //       $answer = $userMessage;

            //       $dt = DateTime::createFromFormat('d-m-Y', $userMessage)->format('Y-m-d');
            //       $num = tracker::where('user_id', $user)
            //                         ->whereNull('deleted_at')
            //                         ->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), $dt)
            //                         ->count();
            //       if($num >= '1'){
            //         $userMessage = 'มื้อนี้คุณแม่ทานอะไรไปบ้างคะ?';
            //         $sequentsteps_insert =  $sequentsteps = sequentsteps::where('sender_id', $user)
            //                                                              ->update(['answer'=>$answer,'seqcode'=>$seqcode,'nextseqcode'=>$nextseqcode]);
            //       }else{
            //         $userMessage = 'คุณแม่อาจใส่ตัวเลขไม่ตรงตามรูปแบบ หรืออาจจะไม่มีวันที่ๆ ตรงกับวันที่ๆจะบันทึกค่ะ พิมพ์ใหม่หรือพิมพ์ Q เพื่อข้ามคำถามด้านบนค่ะ';
            //       }

            //     }
            }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '3011_1'  ) {
                 if($userMessage == 'แนะนำเมนูอาหาร'|| $userMessage == 'ความรู้เพิ่มเติม'|| $userMessage == 'แนะนำการออกกำลังกาย'|| $userMessage == 'บันทึกข้อมูลคุณแม่'|| $userMessage == 'แนะนำการใช้งาน'){
                      $case = 1;
                      $userMessage = 'กรุณาตอบคำถามด้านบนก่อนนะคะ';
                 }else{
                  $sequentsteps = (new SqlController)->sequentsteps_seqcode($user);
                  $date = $sequentsteps->answer;
                  $dt = DateTime::createFromFormat('d-m-Y', $date)->format('Y-m-d');                   
                  $tracker_update = tracker::where('user_id', $user)
                                         ->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), $dt)
                                         ->update(['lunch' =>$userMessage]);
                  $seqcode = '0000';
                  $nextseqcode = '0000';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                  $case = 1;
                  //$userMessage  = 'คุณแม่ทานของว่างไหมคะ';
                  $userMessage  = 'บันทึกมื้ออาหารกลางวันเรียบร้อยแล้วนะคะ';
                    (new ApiController)->check_ulife_tracker_edit($user,$dt);
                }


            }elseif (strpos($userMessage, 'บันทึกมื้อว่างกลางวัน:') !== false) {

                  $pieces = explode(":", $userMessage);
                  $pieces1  = str_replace("","",$pieces[1]);
                  $answer = $pieces1;

                  $case = 1;
                  $seqcode = '3012_1';
                  $nextseqcode = '0000';
                  $userMessage = 'มื้อว่างช่วงกลางวันคุณแม่ทานอะไรไปบ้างคะ?';
                  $sequentsteps_insert =  $sequentsteps = sequentsteps::where('sender_id', $user)
                                                                         ->update(['answer'=>$answer,'seqcode'=>$seqcode,'nextseqcode'=>$nextseqcode]);
             }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '3012_1'  ) {

                if($userMessage == 'แนะนำเมนูอาหาร'|| $userMessage == 'ความรู้เพิ่มเติม'|| $userMessage == 'แนะนำการออกกำลังกาย'|| $userMessage == 'บันทึกข้อมูลคุณแม่'|| $userMessage == 'แนะนำการใช้งาน'){
                      $case = 1;
                      $userMessage = 'กรุณาตอบคำถามด้านบนก่อนนะคะ';
                 }else{
                  $sequentsteps = (new SqlController)->sequentsteps_seqcode($user);
                  $date = $sequentsteps->answer;
                  $dt = DateTime::createFromFormat('d-m-Y', $date)->format('Y-m-d');                   
                  $tracker_update = tracker::where('user_id', $user)
                                         ->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), $dt)
                                         ->update(['dessert_lu' =>$userMessage]);
                  $seqcode = '0000';
                  $nextseqcode = '0000';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                  $case = 1;
                  $userMessage  = 'บันทึกมื้อว่างกลางวันเรียบร้อยแล้วนะคะ';
                    (new ApiController)->check_ulife_tracker_edit($user,$dt);
                  }
///[บันทึกย้อนหลังเย็น]
            }elseif (strpos($userMessage, 'บันทึกมื้อเย็น:') !== false) {
                
                  $case = 1;
                 //  $seqcode = '3010_2';
                 //  $nextseqcode = '3011_2';
                 //  $userMessage ='คุณแม่ต้องการบันทึกย้อนหลังวันไหนค่ะ? พิมพ์ในรูปแบบนี้นะคะ 01-12-2018 (วัน-เดือน-ปี) ค่ะ หรือ พิมพ์ Q เพื่อข้ามคำถามนี้ค่ะ';
                 // $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                  $pieces = explode(":", $userMessage);
                  $pieces1  = str_replace("","",$pieces[1]);
                  $answer = $pieces1;

                  $seqcode = '3011_2';
                  $nextseqcode = '3012_2';
                  $userMessage = 'มื้อเย็นคุณแม่ทานอะไรไปบ้างคะ?';
                    $sequentsteps_insert =  $sequentsteps = sequentsteps::where('sender_id', $user)
                                                                         ->update(['answer'=>$answer,'seqcode'=>$seqcode,'nextseqcode'=>$nextseqcode]);

            // }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '3010_2' ) {

            //      if($userMessage == 'แนะนำเมนูอาหาร'|| $userMessage == 'คำถามที่ถามบ่อย'|| $userMessage == 'แนะนำการออกกำลังกาย'|| $userMessage == 'บันทึกข้อมูลคุณแม่'|| $userMessage == 'แนะนำการใช้งาน'){
            //           $case = 1;
            //           $userMessage = 'กรุณาตอบคำถามด้านบนก่อนนะคะ หรือ พิมพ์ Q เพื่อข้ามคำถามด้านบนค่ะ';
            //     }else{
            //       $case = 1;
            //       $seqcode = '3011_2';
            //       $nextseqcode = '3012_2';
            //       $answer = $userMessage;

            //       $dt = DateTime::createFromFormat('d-m-Y', $userMessage)->format('Y-m-d');
            //       $num = tracker::where('user_id', $user)
            //                         ->whereNull('deleted_at')
            //                         ->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), $dt)
            //                         ->count();
            //       if($num >= '1'){
            //         $userMessage = 'มื้อนี้คุณแม่ทานอะไรไปบ้างค่ะ';
            //         $sequentsteps_insert =  $sequentsteps = sequentsteps::where('sender_id', $user)
            //                                                              ->update(['answer'=>$answer,'seqcode'=>$seqcode,'nextseqcode'=>$nextseqcode]);
            //       }else{
            //         $userMessage = 'คุณแม่อาจใส่ตัวเลขไม่ตรงตามรูปแบบ หรืออาจจะไม่มีวันที่ๆ ตรงกับวันที่ๆจะบันทึกค่ะ พิมพ์ใหม่หรือพิมพ์ Q เพื่อข้ามคำถามด้านบนค่ะ ';
            //       }
            //     }
            }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '3011_2'  ) {
                 if($userMessage == 'แนะนำเมนูอาหาร'|| $userMessage == 'ความรู้เพิ่มเติม'|| $userMessage == 'แนะนำการออกกำลังกาย'|| $userMessage == 'บันทึกข้อมูลคุณแม่'|| $userMessage == 'แนะนำการใช้งาน'){
                      $case = 1;
                      $userMessage = 'กรุณาตอบคำถามด้านบนก่อนนะคะ หรือพิมพ์ Q เพื่อข้ามคำถามด้านบนค่ะ';
                }else{
                  $sequentsteps = (new SqlController)->sequentsteps_seqcode($user);
                  $date = $sequentsteps->answer;
                  $dt = DateTime::createFromFormat('d-m-Y', $date)->format('Y-m-d');                   
                  $tracker_update = tracker::where('user_id', $user)
                                         ->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), $dt)
                                         ->update(['dinner' =>$userMessage]);
                  // $seqcode = '3012_2';
                  // $nextseqcode = '3013_2';
                  $seqcode = '0000';
                  $nextseqcode = '0000';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                  $case = 1;
                  $userMessage  = 'บันทึกมื้อเย็นเรียบร้อยแล้วนะคะ';
                 // $userMessage  = 'คุณแม่ทานของว่างไหมคะ';
                    (new ApiController)->check_ulife_tracker_edit($user,$dt);
                }




            }elseif (strpos($userMessage, 'บันทึกมื้อว่างเย็น:') !== false) {
                
                  $case = 1;
                  $pieces = explode(":", $userMessage);
                  $pieces1  = str_replace("","",$pieces[1]);
                  $answer = $pieces1;

                  $seqcode = '3012_2';
                  $nextseqcode = '0000';
                  $userMessage = 'มื้อว่างช่วงเย็นคุณแม่ทานอะไรไปบ้างคะ?';
                    $sequentsteps_insert =  $sequentsteps = sequentsteps::where('sender_id', $user)
                                                                         ->update(['answer'=>$answer,'seqcode'=>$seqcode,'nextseqcode'=>$nextseqcode]);

            }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '3012_2'  ) {
                 if($userMessage == 'แนะนำเมนูอาหาร'|| $userMessage == 'ความรู้เพิ่มเติม'|| $userMessage == 'แนะนำการออกกำลังกาย'|| $userMessage == 'บันทึกข้อมูลคุณแม่'|| $userMessage == 'แนะนำการใช้งาน'){
                      $case = 1;
                      $userMessage = 'กรุณาตอบคำถามด้านบนก่อนนะคะ หรือพิมพ์ Q เพื่อข้ามคำถามด้านบนค่ะ';
                }else{
                  $sequentsteps = (new SqlController)->sequentsteps_seqcode($user);
                  $date = $sequentsteps->answer;
                  $dt = DateTime::createFromFormat('d-m-Y', $date)->format('Y-m-d');                   
                  $tracker_update = tracker::where('user_id', $user)
                                         ->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), $dt)
                                         ->update(['dessert_din' =>$userMessage]);
                  $seqcode = '0000';
                  $nextseqcode = '0000';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                  $case = 1;
                  $userMessage  = 'บันทึกอาหารมื้อว่างเย็นเรียบร้อยแล้วนะคะ';
                    (new ApiController)->check_ulife_tracker_edit($user,$dt);
              }
///vitamin diary
            }elseif (strpos($userMessage, 'บันทึกวิตามิน:') !== false) {
                  $pieces = explode(":", $userMessage);
                  $pieces1  = str_replace("","",$pieces[1]);
                  $case = 11;
                  $seqcode = '3011_3';
                  $nextseqcode = '3012_3';
                  $answer = $pieces1;
                  // $seqcode = '3010_3';
                  // $nextseqcode = '3011_3';
                  $userMessage = 'คุณแม่ได้ทานวิตามินไหมคะ?';
                  // $userMessage ='คุณแม่ต้องการบันทึกย้อนหลังวันไหนค่ะ? พิมพ์ในรูปแบบนี้นะคะ 01-12-2018 (วัน-เดือน-ปี) ค่ะ หรือ พิมพ์ Q เพื่อข้ามคำถามนี้ค่ะ';
                  // $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                  $sequentsteps_insert =  $sequentsteps = sequentsteps::where('sender_id', $user)
                                                                         ->update(['answer'=>$answer,'seqcode'=>$seqcode,'nextseqcode'=>$nextseqcode]);

                            
              // }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '3010_3' ) {
              //      if($userMessage == 'แนะนำเมนูอาหาร'|| $userMessage == 'คำถามที่ถามบ่อย'|| $userMessage == 'แนะนำการออกกำลังกาย'|| $userMessage == 'บันทึกข้อมูลคุณแม่'|| $userMessage == 'แนะนำการใช้งาน'){
              //         $case = 1;
              //         $userMessage = 'กรุณาตอบคำถามด้านบนก่อนนะคะ หรือ พิมพ์ Q เพื่อข้ามคำถามด้านบนค่ะ';
              //   }else{
                 
              //     $seqcode = '3011_3';
              //     $nextseqcode = '3012_3';
              //     $answer = $userMessage;

              //     $dt = DateTime::createFromFormat('d-m-Y', $userMessage)->format('Y-m-d');
              //     $num = tracker::where('user_id', $user)
              //                       ->whereNull('deleted_at')
              //                       ->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), $dt)
              //                       ->count();
              //     if($num >= '1'){
              //       $case = 11;
              //       $userMessage = 'คุณแม่ได้ทานวิตามินไหมคะ';
              //       $sequentsteps_insert =  $sequentsteps = sequentsteps::where('sender_id', $user)
              //                                                            ->update(['answer'=>$answer,'seqcode'=>$seqcode,'nextseqcode'=>$nextseqcode]);
              //     }else{
              //        $case = 1;
              //       $userMessage = 'คุณแม่อาจใส่ตัวเลขไม่ตรงตามรูปแบบ หรืออาจจะไม่มีวันที่ๆ ตรงกับวันที่ๆจะบันทึกค่ะ พิมพ์ใหม่หรือพิมพ์ Q เพื่อข้ามคำถามด้านบนค่ะ ';
              //     }
              //   }
            }elseif ($userMessage == 'ทานแล้ว'  && $sequentsteps->seqcode == '3011_3'  ) {
                  
                  $case = 1;
                  $sequentsteps = (new SqlController)->sequentsteps_seqcode($user);
                  $date = $sequentsteps->answer;
                  $dt = DateTime::createFromFormat('d-m-Y', $date)->format('Y-m-d');                   
                  $tracker_update = tracker::where('user_id', $user)
                                         ->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), $dt)
                                         ->update(['vitamin' =>'1']);
                  $seqcode = '0000';
                  $nextseqcode = '0000';
                  $userMessage  = 'บันทึกการทานวิตามินเรียบร้อยแล้วนะคะ';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                   (new ApiController)->check_ulife_tracker_edit($user,$dt);
        

            }elseif ($userMessage == 'ยังไม่ได้ทาน' && $sequentsteps->seqcode == '3011_3'  ) {
                
                  $case = 1;
                  $sequentsteps = (new SqlController)->sequentsteps_seqcode($user);
                  $date = $sequentsteps->answer;
                  $dt = DateTime::createFromFormat('d-m-Y', $date)->format('Y-m-d');                   
                  $tracker_update = tracker::where('user_id', $user)
                                         ->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), $dt)
                                         ->update(['vitamin' =>'0']);
                  $seqcode = '0000';
                  $nextseqcode = '0000';
                  $userMessage  = 'บันทึกการทานวิตามินเรียบร้อยแล้วนะคะ';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                    (new ApiController)->check_ulife_tracker_edit($user,$dt);
            
            }elseif ($userMessage =='ดูบันทึกออกกำลังกาย') {
               $case = 1;
               $tracker  = (new SqlController)->tracker_count($user);

               if($tracker == 0){
                 $userMessage = 'ตอนนี้คุณแม่ยังไม่มีบันทึกข้อมูลออกกำลังกายนะคะ';
               }else{
                 $log_message = (new ReplyMessageController)->info_exercise_diary($replyToken,$user);
               } 
               
               // $case = 1;
               // $log_message = (new ReplyMessageController)->info_exercise_diary($replyToken,$user);
            }elseif ($userMessage =='ดูบันทึกวิตามิน') {
               $case = 1;
               $tracker  = (new SqlController)->tracker_count($user);

               if($tracker == 0){
                 $userMessage = 'ตอนนี้คุณแม่ยังไม่มีบันทึกข้อมูลวิตามินนะคะ';
               }else{
                 $log_message = (new ReplyMessageController)->info_vitamin_diary($replyToken,$user);
               } 
               // $case = 1;
               // $log_message = (new ReplyMessageController)->info_vitamin_diary($replyToken,$user);
            }elseif ($userMessage =='ดูบันทึกอาหาร') {
               $case = 1;
               $tracker  = (new SqlController)->tracker_count($user);

               if($tracker == 0){
                 $userMessage = 'ตอนนี้คุณแม่ยังไม่มีบันทึกข้อมูลอาหารนะคะ';
               }else{
                 $log_message = (new ReplyMessageController)->info_food_diary($replyToken,$user);
               } 
               
           

            }elseif (strpos($userMessage, 'บันทึกออกกำลังกาย:') !== false) {
              $pieces = explode(":", $userMessage);
              $pieces1  = str_replace("","",$pieces[1]);
            

              $seqcode = '3011_4';
              $nextseqcode = '3012_4';
              $answer = $pieces1;

              $sequentsteps = sequentsteps::where('sender_id', $user)
                                          ->update(['answer'=>$answer,'seqcode'=>$seqcode,'nextseqcode'=>$nextseqcode]);
              $case = 1;
              $userMessage = 'คุณแม่ได้ออกกำลังกายอย่างไรบ้างคะ?';
                  // $case = 1;
                  // $seqcode = '3010_4';
                  // $nextseqcode = '3011_4';
                  // $userMessage ='คุณแม่ต้องการบันทึกย้อนหลังวันไหนค่ะ? พิมพ์ในรูปแบบนี้นะคะ 01-12-2018 (วัน-เดือน-ปี) ค่ะ หรือ พิมพ์ Q เพื่อข้ามคำถามนี้ค่ะ';
                  // $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);


            
             // }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '3010_4' ) {
             //    if($userMessage == 'แนะนำเมนูอาหาร'|| $userMessage == 'คำถามที่ถามบ่อย'|| $userMessage == 'แนะนำการออกกำลังกาย'|| $userMessage == 'บันทึกข้อมูลคุณแม่'|| $userMessage == 'แนะนำการใช้งาน'){
             //          $case = 1;
             //          $userMessage = 'กรุณาตอบคำถามด้านบนก่อนนะคะ หรือ พิมพ์ Q เพื่อข้ามคำถามด้านบนค่ะ';
             //    }else{
             //      $seqcode = '3011_4';
             //      $nextseqcode = '3012_4';
             //      $answer = $userMessage;

             //      $dt = DateTime::createFromFormat('d-m-Y', $userMessage)->format('Y-m-d');
             //      $num = tracker::where('user_id', $user)
             //                        ->whereNull('deleted_at')
             //                        ->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), $dt)
             //                        ->count();
             //      if($num >= '1'){
             //        $case = 1;
             //        $userMessage = 'คุณแม่ได้ออกกำลังกายอย่างไรบ้างค่ะ';
             //        $sequentsteps = sequentsteps::where('sender_id', $user)
             //                                                             ->update(['answer'=>$answer,'seqcode'=>$seqcode,'nextseqcode'=>$nextseqcode]);
             //      }else{
             //         $case = 1;
             //        $userMessage = 'คุณแม่อาจใส่ตัวเลขไม่ตรงตามรูปแบบ หรืออาจจะไม่มีวันที่ๆ ตรงกับวันที่ๆจะบันทึกค่ะ พิมพ์ใหม่หรือพิมพ์ Q เพื่อข้ามคำถามด้านบนค่ะ ';
             //      }
             //    }
            }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '3011_4'  ) {
                 if($userMessage == 'แนะนำเมนูอาหาร'|| $userMessage == 'ความรู้เพิ่มเติม'|| $userMessage == 'แนะนำการออกกำลังกาย'|| $userMessage == 'บันทึกข้อมูลคุณแม่'|| $userMessage == 'แนะนำการใช้งาน'){
                      $case = 1;
                      $userMessage = 'กรุณาตอบคำถามด้านบนก่อนนะคะ หรือพิมพ์ Q เพื่อข้ามคำถามด้านบนค่ะ';
                }else{
                  $sequentsteps = (new SqlController)->sequentsteps_seqcode($user);
                  $date = $sequentsteps->answer;
                  $dt = DateTime::createFromFormat('d-m-Y', $date)->format('Y-m-d');                   
                  $tracker_update = tracker::where('user_id', $user)
                                         ->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), $dt)
                                         ->update(['exercise' =>$userMessage]);
                  $seqcode = '0000';
                  $nextseqcode = '0000';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                  $case = 1;
                  $userMessage  = 'บันทึกการออกกำลังกายเรียบร้อยแล้วนะคะ';
                   (new ApiController)->check_ulife_tracker_edit($user,$dt);
                  }
            }elseif ($userMessage == 'บันทึกน้ำหนัก' ) {
                  // $case = 1;
                  (new ReplyMessageController)->info_weight_diary($replyToken,$user);
               
            }elseif (strpos($userMessage, 'บันทึกน้ำหนัก:') !== false) {
                  $case = 1;
                  $pieces = explode(":", $userMessage);
                  $pieces1  = str_replace("","",$pieces[1]);
                  $seqcode = '3011_5';
                  $nextseqcode = '3012_5';
                  $answer = $pieces1;
                  $userMessage = 'สัปดาห์ที่ '.$answer.'คุณแม่น้ำหนักเท่าไรคะ';
                  $sequentsteps_insert =  $sequentsteps = sequentsteps::where('sender_id', $user)
                                                                         ->update(['answer'=>$answer,'seqcode'=>$seqcode,'nextseqcode'=>$nextseqcode]);
                 //  $seqcode = '3010_5';
                 //  $nextseqcode = '3011_5';
                 //  $userMessage ='คุณแม่ต้องการแก้ไขบันทึกน้ำหนักย้อนหลังของสัปดาห์ไหนค่ะ? พิมพ์ตัวเลขของสัปดาห์ได้เลยค่ะ เช่น สัปดาห์ที่12 พิมพ์ 12 ได้เลยค่ะ รือ พิมพ์ Q เพื่อข้ามคำถามนี้ค่ะ';
                 // $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);  
            // }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '3010_5' ) {
            //    if($userMessage == 'แนะนำเมนูอาหาร'|| $userMessage == 'คำถามที่ถามบ่อย'|| $userMessage == 'แนะนำการออกกำลังกาย'|| $userMessage == 'บันทึกข้อมูลคุณแม่'|| $userMessage == 'แนะนำการใช้งาน'){
            //           $case = 1;
            //           $userMessage = 'กรุณาตอบคำถามด้านบนก่อนนะคะ หรือ พิมพ์ Q เพื่อข้ามคำถามด้านบนค่ะ';
            //     }else{


            //       $seqcode = '3011_5';
            //       $nextseqcode = '3012_5';
            //       $answer = $userMessage;


            //       $num = RecordOfPregnancy::where('user_id', $user)
            //                         ->whereNull('deleted_at')
            //                         ->where('preg_week', $answer)
            //                         ->count();
            //       if($num >= '1'){
            //         $case = 1;
            //         $userMessage = 'สัปดาห์ที่ '.$answer.'คุณแม่น้ำหนักเท่าไรคะ';
            //         $sequentsteps_insert =  $sequentsteps = sequentsteps::where('sender_id', $user)
            //                                                              ->update(['answer'=>$answer,'seqcode'=>$seqcode,'nextseqcode'=>$nextseqcode]);
            //       }else{
            //          $case = 1;
            //         $userMessage = 'คุณแม่อาจใส่ตัวเลขไม่ตรงตามรูปแบบ หรืออาจจะไม่มีวันที่ๆ ตรงกับวันที่ๆจะบันทึกค่ะ พิมพ์ใหม่หรือพิมพ์ Q เพื่อข้ามคำถามด้านบนค่ะ';
            //       }  
            //     }
            }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '3011_5'  ) {

                 if($userMessage == 'แนะนำเมนูอาหาร'|| $userMessage == 'ความรู้เพิ่มเติม'|| $userMessage == 'แนะนำการออกกำลังกาย'|| $userMessage == 'บันทึกข้อมูลคุณแม่'|| $userMessage == 'แนะนำการใช้งาน'){
                      $case = 1;
                      $userMessage = 'กรุณาตอบคำถามด้านบนก่อนนะคะ หรือพิมพ์ Q เพื่อข้ามคำถามด้านบนค่ะ';
                }else{

            if(is_numeric($userMessage) !== false && $userMessage<150 && $userMessage>0){
             
                  $sequentsteps = (new SqlController)->sequentsteps_seqcode($user);
                  $date = $sequentsteps->answer;
                             
                  $tracker_update = RecordOfPregnancy::where('user_id', $user)
                                         ->where('preg_week', $date)
                                         ->whereNull('deleted_at')
                                         ->update(['preg_weight' =>$userMessage]);
                  $users_register = (new SqlController)->users_register_select($user);
                  $preg_week = $users_register->preg_week;
                  $user_Pre_weight = $users_register->user_Pre_weight;
                  $user_weight = $users_register->user_weight;
                  $user_height =  $users_register->user_height;
                  $bmi  = (new CalController)->bmi_calculator($user_Pre_weight,$user_height);
                  $weight_cur = $userMessage;
                  $weight_status =  $users_register->weight_status;
                    if($weight_status=='4'){
                      $weight_status  = 'ภาวะแทรกซ้อน';
                    }else{
                      $weight_status  = (new CalController)->weight_criteria_status($bmi,$user,$weight_cur);
                    }
                  // dd($weight_status);
                  $seqcode = '0000';
                  $nextseqcode = '0000';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);

                  $update = 5;
                  $answer = $userMessage;
                  $user_update = (new SqlController)->user_update($user,$answer,$update);
                  $case = 1;
                  $userMessage  = 'บันทึกน้ำหนักเรียบร้อยแล้วนะคะ';
                  (new ApiController)->check_ulife_weight_edit($user,$date);
             
                 }else{
                     $case = 1;
                     $userMessage  = 'น้ำหนักตอบเป็นตัวเลขเท่านั้น หน่วยเป็นกิโลกรัม กรุณาพิมพ์ใหม่';
                }

              }       
///  Ulife.info
           }elseif ($userMessage == 'เชื่อม Ulife.info' && $sequentsteps->seqcode == '0000'  ) {
                  $case = 13;
                  $seqcode = '0000';
                  $nextseqcode = '0000';
                  $userMessage  = 'คุณแม่ต้องการเชื่อมข้อมูลไปยัง ulife.info หรือไม่?';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);

            }elseif ($userMessage == 'ไม่ต้องการเชื่อมข้อมูล' && $sequentsteps->seqcode == '0000'  ) {
                  $case = 1;
                  $seqcode = '0000';
                  $nextseqcode = '0000';
                  $userMessage  ='คุณแม่สามารถดูข้อมูลเกี่ยวกับการเชื่อมข้อมูลกับ Ulife.info ได้ทางเว็บไซต์ Ulife.info นะคะ';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
            }elseif ($userMessage == 'ต้องการเชื่อมข้อมูล' && $sequentsteps->seqcode == '0000'  ) {
                  $case = 32;
                  $seqcode = '3002';
                  $nextseqcode = '0000';
                  $users_register = users_register::whereNull('deleted_at')
                                                    ->where('user_id',$user)
                                                    ->first();

                  $email =   $users_register->email;
                  $userMessage  ='คุณแม่ยืนยันจะใช้ '.$email.' นี้ในการเชื่อมข้อมูลหรือไม่?';
                  // $userMessage  =$email.'ใช้อีเมลนี้เพื่อทำการเชื่อมต่อ'."\n".' ดิฉันขอทราบรหัสผ่าน ulife เพื่อยืนยันการเข้าถึงข้อมูลค่ะ หรือพิมพ์ Q เพื่อทำการออกจากการเชื่อมข้อมูลค่ะ';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
            // }elseif ($userMessage == 'เคยลงทะเบียน' && $sequentsteps->seqcode == '3002' ) {
            //       $case = 1;
            //       $seqcode = '3003';
            //       $nextseqcode = '0000';
            //       //$userMessage  = $this->sequents_question($seqcode);
            //       $userMessage = 'ดิฉันขออีเมลที่ลงทะเบียนกับ Ulife.info หน่อยค่ะ';
            //       $sequentsteps_insert =  $this->sequentsteps_update($user,$seqcode,$nextseqcode);

            // }elseif ($userMessage == 'ไม่เคยลงทะเบียน' && $sequentsteps->seqcode == '3002'  ) {
            //       $case = 1;
            //       $seqcode = '3003';
            //       $nextseqcode = '0000';
            //       $userMessage  ='ดิฉันขออีเมลที่คุณแม่จะเชื่อมกับ Ulife.info หน่อยค่ะ';
            //       $sequentsteps_insert =  $this->sequentsteps_update($user,$seqcode,$nextseqcode);

            }elseif ($userMessage == 'ยืนยัน' && $sequentsteps->seqcode == '3002'  ) {
                    $case = 1;
                    $seqcode = '3002_1';
                    $nextseqcode = '0000';
                    $users_register = users_register::whereNull('deleted_at')
                                                      ->where('user_id',$user)
                                                      ->first();

                    $email =   $users_register->email;
      
                    $userMessage  ='ใช้อีเมล '.$email.' เพื่อทำการเชื่อมต่อ'."\n".' ดิฉันขอทราบรหัสผ่าน ulife เพื่อยืนยันการเข้าถึงข้อมูลค่ะ';
                    $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
            }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '3002_1') {
//****emailที่ลงทะเบียนกับ ulife***********
                  // $answer = $userMessage;
                     $case = 1;
               
                     $password = $userMessage;
                     $users_register = users_register::whereNull('deleted_at')
                                                      ->where('user_id',$user)
                                                      ->first();

                     $email =   $users_register->email;
                     $name = $users_register->user_name;
                     $line_id = $users_register->user_id;
                     

               
                      $postData = array(
                              'client_id'=>'580653df7fab2a33c03896b9',
                              'client_secret'=>'Y6vtZlDibxbZXn4VzCdQ657phBPXMs',
                              'name'=>$name ,
                              'email'=>$email,
                              'password'=>$password,
                              'line_id'=>$line_id 
                            );

                      //set the url, number of POST vars, POST data
                      $data_json = json_encode($postData);    
                      $url ='http://128.199.147.57/api/OAuth2/LocalRegister';
                      $ch = curl_init();
                      //set the url, number of POST vars, POST data
                      curl_setopt($ch,CURLOPT_URL, $url);
                     // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                      curl_setopt($ch, CURLOPT_POST, 1);
                      curl_setopt($ch,CURLOPT_POSTFIELDS, $data_json);
                      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                      //execute post
                      $result = curl_exec($ch);

                      //close connection
                      curl_close($ch);
                  

                     $re = json_decode($result,true);
                     // $message = $re['message'];
                     // $userMessage = $result;
                         //$userMessage = $re;
              
                      if(strpos($result, 'errors') !== false ){
                          $userMessage  = 'รหัสผิดพลาดหรือไม่ กรุณาตรวจสอบ';
                      }else{    
                                  $code = $re['code'];
                                  if ($code == '200'){
                                      $seqcode = '3004';
                                      $nextseqcode = '0000';
                        
                                      $userMessage  = 'ไปยังอีเมลเพื่อรับรหัส เมื่อรับรหัสแล้วโปรดกรอกเพื่อยืนยัน';
                                      $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                                   $answer=$password;
                                   (new SqlController)->sequentsteps_update2($user,$answer);
                                      
                                  }else{
                                      $seqcode = '0000';
                                      $nextseqcode = '0000';
                                    // $message = $re['message'];
                                    // $userMessage = $message;
                                 
                                      // $sequentsteps_insert =  $this->sequentsteps_update($user,$seqcode,$nextseqcode);
                          
                          $users_register = users_register::whereNull('deleted_at')
                                                      ->where('user_id',$user)
                                                      ->first();
                          $email =   $users_register->email;


                          $localLogin =  array('client_id'=> '580653df7fab2a33c0387111a',
                                               'client_secret' => 'NevtZlDibxbZXn4VzCdQ657phBPzNe',
                                               'email'=> $email,
                                               'password'=> $password
                                              );               
                      
                          $localLogin_json = json_encode($localLogin);    
                          $url ='http://128.199.147.57/api/OAuth2/LocalLogin';
                          $ch = curl_init();
                          //set the url, number of POST vars, POST data
                          curl_setopt($ch,CURLOPT_URL, $url);
                          //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                          curl_setopt($ch, CURLOPT_POST, 1);
                          curl_setopt($ch,CURLOPT_POSTFIELDS, $localLogin_json);
                          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                          //execute post
                          $result = curl_exec($ch);
                          // dd($result);
                        
                          //close connection
                          curl_close($ch);
                          // echo $result;
                          // $userMessage  = $result;
                          // print($result);
                          $re = json_decode($result,true);
                          $code = $re['code'];


                         if($code == '409'){

                            $userMessage = "อีเมลหรือรหัสผ่าน ไม่ตรงกันค่ะ คุณแม่กรุณาพิมพ์รหัสผ่านอีกครั้ง หรือ พิมพ์ 'Q' เพื่อทำการข้ามการเชื่อมข้อมูลค่ะ"; 

                         }elseif($code == '200'){
                          $key = $re['user_data']['user_key'];
                          $token = $re['access_token'];
                          //$setgraph = $this->setgraph_api($key,$user);
                          $addChild = (new ApiController)->addChild_api($token,$user);
                          $setgraph = (new ApiController)->setgraph_api($key,$user);
                          $tracker = (new ApiController)->tracker_api($key,$user);
                          $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                          
                          $update = 15;
                          $answer = $key;
                          $user_update = (new SqlController)->user_update($user,$answer,$update);
                           $userMessage = 'ทำการเชื่อมต่อแล้วนะคะ คุณสามารถเข้าไปดูข้อมูลของคุณได้ที่ Ulife.info ค่ะ';
                         }else{
                              $userMessage  = $re['message'];
                         }
                        }

                      }


                     
            }elseif (is_numeric($userMessage) !== false && $sequentsteps->seqcode == '3004'  ) {
                      // print('sss');
                      $case = 1;
                      
                      $Data = array(
                               'token' => $userMessage,
                               'line_id' => $user
                            );
                      // print($Data);
                      $data_json = json_encode($Data);    
                      $url ='http://128.199.147.57/api/v1/peat/verify';
                      $ch = curl_init();
                      //set the url, number of POST vars, POST data
                      curl_setopt($ch,CURLOPT_URL, $url);
                      curl_setopt($ch,CURLOPT_POSTFIELDS, $data_json);
                      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                      //execute post
                      $result = curl_exec($ch);

                      //close connection
                      curl_close($ch);
                      $re = json_decode($result,true);
                      // print ($result);
                       if(strpos($result, 'errors') !== false ){
                          $userMessage  = 'รหัสผิดพลาด โปรดใส่รหัสอีกครั้ง';
                      }else{    
                                 $code = $re['code'];
                                 if ($code=='200'){
                                   
                                    $seqcode = '0000';
                                    $nextseqcode = '0000';
                                    $userMessage  = 'ทำการเชื่อมต่อแล้วนะคะ คุณสามารถเข้าไปดูข้อมูลของคุณได้ที่ Ulife.info ค่ะ';


                          $users_register = users_register::whereNull('deleted_at')
                                                      ->where('user_id',$user)
                                                      ->first();
                          $email =   $users_register->email;

                          $sequentsteps = (new SqlController)->sequentsteps_seqcode($user);
                          $password = $sequentsteps->answer;



                          $localLogin =  array('client_id'=> '580653df7fab2a33c0387111a',
                                               'client_secret' => 'NevtZlDibxbZXn4VzCdQ657phBPzNe',
                                               'email'=> $email,
                                               'password'=> $password
                                              );               
                      
                          $localLogin_json = json_encode($localLogin);    
                          $url ='http://128.199.147.57/api/OAuth2/LocalLogin';
                          $ch = curl_init();
                          //set the url, number of POST vars, POST data
                          curl_setopt($ch,CURLOPT_URL, $url);
                          //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                          curl_setopt($ch, CURLOPT_POST, 1);
                          curl_setopt($ch,CURLOPT_POSTFIELDS, $localLogin_json);
                          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                          //execute post
                          $result = curl_exec($ch);
                        
                          //close connection
                          curl_close($ch);
                          // echo $result;
                          // $userMessage  = $result;

                          $re = json_decode($result,true);

                                     $code = $re['code'];
                                     if($code == '409'){

                                        $userMessage = "อีเมลหรือรหัสผ่าน ไม่ตรงกันค่ะ คุณแม่กรุณาพิมพ์รหัสผ่านอีกครั้ง หรือ พิมพ์ 'Q' เพื่อทำการข้ามการเชื่อมข้อมูลค่ะ"; 

                                     }else{
                                      $key = $re['user_data']['user_key'];
                                      $token = $re['access_token'];
                                      // $setgraph = $this->setgraph_api($key,$user);
                                      $addChild = (new ApiController)->addChild_api($token,$user);
                                      $setgraph = (new ApiController)->setgraph_api($key,$user);
                                      $tracker = (new ApiController)->tracker_api($key,$user);
                                      $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                                      
                                      $update = 15;
                                      $answer = $key;
                                      $user_update = (new SqlController)->user_update($user,$answer,$update);

                                      }
                                      
                                 
                                }else{
                                    $userMessage  = $re['message'];
                                }

                                  
                      }
  

            // }elseif ((is_string($userMessage) !== false && $sequentsteps->seqcode == '3005' )||(is_string($userMessage) !== false && $sequentsteps->seqcode == '3006' ) ) {
            //                         $case = 1;
            //                         $seqcode = '0000';
            //                         $nextseqcode = '0000';
                      
            //                         $password = $userMessage;
            //                         $userMessage  = 'ทำการเชื่อมต่อแล้ว';
            //                         $sequentsteps_insert =  $this->sequentsteps_update($user,$seqcode,$nextseqcode);
                                    

            //                         $users_register = users_register::where('deleted_status','1')
            //                                                         ->where('user_id',$user)
            //                                                         ->first();
            //                         $email =  $users_register->email;
            //                         $regis = $this->localRegister_api($user,$password);
            //                         // $key = $this->localLogin_api($email,$password);
            //                         //$setgraph = $this->setgraph_api($key,$user);
            //                         $userMessage =  $regis;
                                          
                                          


            }elseif ($userMessage == 'คุณหมอประจำตัว' && $sequentsteps->seqcode == '0000'  ) {
                  $case = 33;
            }elseif ($userMessage == 'กรอกรหัสคุณหมอ' && $sequentsteps->seqcode == '0000'  ) {
                  $case = 1;
                  $seqcode = '0041';
                  $nextseqcode = '0000';
                  $userMessage  ='กรอกรหัสคุณหมอได้เลยค่ะ';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
           }elseif ($userMessage == 'ใช่' && $sequentsteps->seqcode == '0041'  ) {
                  $case = 1;
                  $seqcode = '0000';
                  $nextseqcode = '0000';

                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
                  $sequentsteps = (new SqlController)->sequentsteps_seqcode($user);
                  $doctor_id = $sequentsteps->answer;
                  $user_id = $user;
                  $mom_doctor = (new SqlController)->personal_doctor_mom_count($user_id);
                  if($mom_doctor == null){
                     $sequentsteps = (new SqlController)->personal_doctor_mom($user_id,$doctor_id);
                  }else{
                     $update = (new SqlController)->personal_doctor_mom_update($user_id);
                     $sequentsteps = (new SqlController)->personal_doctor_mom($user_id,$doctor_id);
                  }
                  $userMessage = 'ยืนยันคุณหมอประจำตัวเรียบร้อยแล้วค่ะ';
                  $sequentsteps = (new SqlController)->sequentsteps_seqcode($user);

          }elseif ($userMessage == 'ไม่ใช่' && $sequentsteps->seqcode == '0041'  ) {
                  $case = 1;
                  $seqcode = '0041';
                  $nextseqcode = '0000';
                  $userMessage  ='กรอกรหัสคุณหมออีกครั้ง หรือพิมพ์ Q เพื่อข้ามคำถามค่ะ';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
           }elseif (is_string($userMessage) !== false && $sequentsteps->seqcode == '0041'  ) {
                 
                  $doctor_id = $userMessage;
                  $doctor = (new SqlController)->personal_doctor_select($doctor_id);
 
                  if($doctor == NULL){
                  $case = 1;
                 
                    $userMessage = 'ดูเหมือนรหัสคุณหมอไม่ถูกต้องนะคะ พิมพ์ใหม่ หรือคุณแม่พิมพ์ Q เพื่อข้ามคำถามนี้ค่ะ';
                  }else{
                     $case = 34;
                     $name = $doctor->name;
                     $lastname = $doctor->lastname;
                     $userMessage  ='คุณหมอ ' .$name.' '.$lastname.' ใช่ไหมคะ';
                     (new SqlController)->sequentsteps_update2($user,$doctor_id);

                  }
                  $seqcode = '0041';
                  $nextseqcode = '0000';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
            }elseif ($userMessage == 'ข้อมูลการใช้งาน' && $sequentsteps->seqcode == '0000'  ) {
                  $case = 1;
                  $userMessage  = 'คุณสามารถพิมพ์'. "\n".
                                  // '1."เริ่มต้นการใช้งาน" :ลบข้อมูลทั้งหมดเพื่อบันทึกข้อมูลใหม่'. "\n".
                /*  แนน*/
                                  // '👉 "ดูข้อมูล" สำหรับ ดูข้อมูลของตัวเอง'. "\n".
                                  '👉 "เริ่มการแจ้งเตือน" สำหรับ เริ่มการแจ้งเตือนทั้งหมด'. "\n".
                                  '👉 "หยุดการแจ้งเตือนทั้งหมด" สำหรับ หยุดการแจ้งเตือนทั้งหมด'. "\n".
                                  '👉 "หยุดการแจ้งเตือนรายวัน" สำหรับ หยุดการแจ้งเตือนรายวัน'. "\n".
                                  '👉 "หยุดการแจ้งเตือนรายสัปดาห์" สำหรับ หยุดการแจ้งเตือนรายสัปดาห์'. "\n".
                                  '👉 "ทำอะไรได้บ้าง" แนะนำ REMI ว่าสามารถทำอะไรได้บ้าง';
                  // $sequentsteps_insert =  $this->sequentsteps_update($user,$seqcode,$nextseqcode);
            // }elseif (strpos($userMessage, 'แนะนำเมนูอาหาร') !== false ||strpos($userMessage, 'เมนูอาหาร') !== false ||strpos($userMessage, 'แนะนำเมนู') !== false ||strpos($userMessage, 'แนะนำอาหาร') !== false ){
                   
            //      // $case = 26;     
            //    $case = 1;
            //        //   $userMessage  = 'ว่าไงคะ มีอะไรให้ช่วยไหมคะ';
            //         $log_message = (new ReplyMessageController)->replymessage_food1($replyToken,$user);
        
//////////////////////////////////////////////
            }elseif ($userMessage == 'หยุดการแจ้งเตือนทั้งหมด' || strpos($userMessage, 'ไม่ต้องแจ้งเตือนแล้ว') !== false || $userMessage == 'รำคาญหยุดเตือนที' || $userMessage == 'ไม่ต้องเตือนแล้ว' || strpos($userMessage, 'ปิดแจ้งเตือน') !== false) {
                  $answer = '0';
                  $case = 1;
                  $seqcode = '0000';
                  $nextseqcode = '0000';
                  $update = 14;
                  $user_update = (new SqlController)->user_update($user,$answer,$update); 
                  $userMessage  = 'หยุดการแจ้งเตือนทั้งหมดแล้วนะคะ';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
            }elseif ($userMessage == 'หยุดการแจ้งเตือนรายสัปดาห์') {
                  $answer = '3';
                  $case = 1;
                  $seqcode = '0000';
                  $nextseqcode = '0000';
                  $update = 14;
                  $user_update = (new SqlController)->user_update($user,$answer,$update); 
                  $userMessage  = 'หยุดการแจ้งเตือนรายสัปดาห์แล้วนะคะ';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);

             }elseif ($userMessage == 'หยุดการแจ้งเตือนรายวัน') {
                  $answer = '2';
                  $case = 1;
                  $seqcode = '0000';
                  $nextseqcode = '0000';
                  $update = 14;
                  $user_update = (new SqlController)->user_update($user,$answer,$update); 
                  $userMessage  = 'หยุดการแจ้งเตือนรายวันแล้วนะคะ';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);
            }elseif ($userMessage == 'เริ่มการแจ้งเตือน') {
                  $answer = '1';
                  $case = 1;
                  $seqcode = '0000';
                  $nextseqcode = '0000';
                  $update = 14;
                  $user_update = (new SqlController)->user_update($user,$answer,$update); 
                  $userMessage  = 'เริ่มการแจ้งเตือนแล้วนะคะ';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);

                  $update = 6;
                  $update_preg =  (new CalController)->pregnancy_calculator_block($user);
                  $answer = $update_preg;
                  $user_update = (new SqlController)->user_update($user,$answer,$update); 

//กราฟน้ำหนัก
           }elseif ($userMessage == 'กราฟน้ำหนัก') {
                 
                  $case = 18;
                  $sequentsteps = (new SqlController)->sequentsteps_seqcode($user);
                  $img = $sequentsteps->answer;
                  $userMessage = 'https://health-track.in.th/uploads/'.$img ;
//เพิ่ม
           }elseif (strpos($userMessage, 'ยืนยันคุณหมอ') !== false ) {
                 
                  $case = 1;
                  $pieces = explode(":", $userMessage);
                  $doctor_id  = str_replace("","",$pieces[1]);
                  $user_id = $user;
                  $mom_doctor = (new SqlController)->personal_doctor_mom_count($user_id);
                  if($mom_doctor == null){
                     $sequentsteps = (new SqlController)->personal_doctor_mom($user_id,$doctor_id);
                  }else{
                     $update = (new SqlController)->personal_doctor_mom_update($user_id);
                     $sequentsteps = (new SqlController)->personal_doctor_mom($user_id,$doctor_id);
                  }
                 

                  $userMessage = 'ยืนยันคุณหมอประจำตัวเรียบร้อยแล้วค่ะ';
            
//////////////////////////////////////////////////////////////////
             }elseif ($userMessage == 'ดูข้อมูล'  ) {

                  $case = 5;
                  // $update = 13;
                  //$case = 1;
                  // $userMessage  = (new checkmessageController)->user_data($user);
                  $log_message = (new ReplyMessageController)->resultinfo($replyToken,$user);
                  // $user_update = $this->user_update($user,$answer,$update);
                  $seqcode = '0000';
                  $nextseqcode = '0000';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);

             }elseif ($userMessage == 'ไม่กิน [อาหารบางชนิด] กินอะไรแทนดี?' || $userMessage == 'แพ้ท้อง กินอย่างไร?'||$userMessage == 'ผลไม้ 1 ส่วนคือเท่าไร?'||$userMessage == 'ซื้ออาหารกินข้างนอก จะกะปริมาณอย่างไร?'||$userMessage == 'กินไม่ถึง หรือกินเกิน ทำอย่างไร?'||$userMessage == 'ท้องผูก ท้องอืด ทำอย่างไร?'||$userMessage == 'อื่น ๆ (ฝากคำถามไว้ได้)'||$userMessage == 'อาหารอะไรที่ควรหลีกเลี่ยง?'||$userMessage == 'ไม่อิ่ม ทำอย่างไร?' ||$userMessage == 'สัดส่วนอาหาร' ||$userMessage == 'อาการผิดปกติ' ||$userMessage == 'พัฒนาการลูกน้อย' ||$userMessage == 'การเตรียมตัวคลอด' || $userMessage == 'คำนวณค่าดัชนีมวลกายอย่างไร?'  ||$userMessage == 'คุณแม่รูปร่างต่างกันน้ำหนักควรเพิ่มเท่าไร?'|| $userMessage == 'อาหารที่คุณแม่ควรทาน'&& $sequentsteps->seqcode == '0000' ) {

                      $case = 1;
                    switch($userMessage) {
                        
                  case ($userMessage == 'ไม่กิน [อาหารบางชนิด] กินอะไรแทนดี?'): 
                        $userMessage = '👼 ไม่ต้องห่วงค่ะ หากคุณไม่กินอาหารบางชนิด สามารถเปลี่ยนเป็นอาหารอย่างอื่นได้ โดยสามารถแลกเปลี่ยนคร่าว ๆ ดังนี้ค่ะ'."\n".'👉 ข้าวสวย 1 ทัพพี สามารถเปลี่ยนเป็น เส้นก๋วยเตี๋ยว เส้นบะหมี่สุก 2/3 ถ้วยตวง หรือ ขนมจีน 1 จับใหญ่ หรือ ข้าวเหนียว ½ ทัพพี หรือ มันฝรั่ง ½ ลูก หรือข้าวโพดต้ม ½ ฝัก หรือ ขนมปัง 1 แผ่น'."\n".
                          '👉 ผัก 1 ทัพพี สามารถเปลี่ยนผักเป็นผักชนิดอื่นได้ โดยเน้นผักให้หลากหลาย ทั้งผักที่มีแป้งมาก (เช่น ฟักทอง แครอท ถั่วลันเตา ฯลฯ) และผักที่ไม่มีแป้งมาก (ผักกาดขาว ผักบุ้ง กะหล่ำปลี มะเขือเทศ แตงกวา ฯลฯ)'."\n".
                          '👉 เนื้อสัตว์ แลกเปลี่ยนในปริมาณที่เท่ากัน (เช่นเนื้อไก่ 2 ช้อนโต๊ะ ก็กินเนื้อปลา 2 ช้อนโต๊ะแทน) หรือเปลี่ยนเป็นเต้าหู้แข็ง ½ แผ่น หรือเต้าหู้ไข่ 2/3 หลอดแทนได้'."\n".
                          '👉 ไขมัน น้ำมัน 1 ช้อนชา แลกเปลี่ยนเป็น กะทิ 1 ช้อนโต๊ะ หรือ น้ำสลัด 1 ช้อนโต๊ะ หรือ ถั่วลิสง 10 เม็ด หรือ เนยสด 1 ช้อนชา หรือ มายองเนส 1 ช้อนชา ได้ อย่างไรก็ตาม ควรเลือกกินน้ำมันชนิดดี เช่น น้ำมันรำข้าว สลับกับน้ำมันถั่วเหลือง มากกว่ากะทิ หรือเนยสด';
                    break;
                  case ($userMessage == 'ผลไม้ 1 ส่วนคือเท่าไร?' ): 
                        $userMessage = '👉 ผลไม้ 1 ส่วน คือ กล้วยน้ำว้า 1 ลูก หรือ กล้วยหอม ½ ลูก หรือ เงาะ 4 ลูก หรือ ชมพู่ 4 ลูก หรือ แตงโม 1 ชิ้น หรือ ฝรั่ง ½ ผล หรือ มะม่วงสุก ½ ผลกลาง หรือ มะละกอ สับปะรด 8 ชิ้นคำ หรือ ส้มเขียวหวาน 2 ผลกลาง หรือ ส้มโอ 2 กลีบใหญ่ หรือแอปเปิ้ล 1 ผลเล็ก หรือ องุ่น 20 เม็ด'."\n".
                        '👉 หมายความว่า ทุกตัวเลือกให้พลังงานเท่ากัน คือ 60 กิโลแคลอรีต่อส่วน เช่น ถ้าหนึ่งวันกินกล้วยน้ำว้าได้ 2 ลูก อาจเปลี่ยนเป็นกล้วยน้ำว้า 1 ลูก และ สับปะรด 8 ชิ้นคำก็ได้ค่ะ';
                    break;
                  case ($userMessage == 'ซื้ออาหารกินข้างนอก จะกะปริมาณอย่างไร?'): 
                        $userMessage = '👉 ถ้าไม่ได้ทำอาหารเอง บางครั้งเราอาจจะพบว่าการกะปริมาณอาหารทำได้ยากขึ้น โดยเฉพาะอย่างยิ่งปริมาณน้ำมันนะคะ วิธีที่ง่ายที่สุดคือ พยายามเลือกอาหารที่ใช้น้ำมันในการปรุงประกอบ สลับกับอาหารที่ไม่ได้มีน้ำมันเป็นส่วนประกอบ แล้วพยายามกะปริมาณของอาหารหมวดที่กะได้ เช่น ปริมาณข้าว ปริมาณเนื้อสัตว์ ปริมาณผัก แล้วติดตามน้ำหนักตัวค่ะ ถ้าน้ำหนักตัวเพิ่มขึ้นในอัตราที่เหมาะสม ก็แสดงว่าปริมาณอาหารที่กินเหมาะสมแล้วค่ะ ถ้าเกิดน้ำหนักตัวขึ้นเยอะเกินไป ก็อาจจะลดความถี่ของการกินอาหารที่ใช้ไขมันปรุงประกอบมาก ๆ ร่วมกับการลดปริมาณข้าวแป้งและผลไม้ทีละน้อย ถ้าเกิดน้ำหนักตัวขึ้นน้อยเกินไป ก็อาจเพิ่มปริมาณอาหาร ร่วมกับการเลือกอาหารที่มีการใช้น้ำมันปรุงประกอบให้บ่อยขึ้นค่ะ';
                    break;

                  case ($userMessage == 'กินไม่ถึง หรือกินเกิน ทำอย่างไร?'): 
                        $userMessage = '👉 ไม่ต้องกังวลนะคะ ว่าถ้าไม่ได้กินอาหารตามปริมาณที่แนะนำเป๊ะ ๆ แล้วจะได้รับสารอาหารไม่พอ หรือได้รับสารอาหารเกิน เพียงแต่ให้ในภาพรวมในแต่ละวันเราได้ตามปริมาณที่แนะนำก็เพียงพอค่ะ บางมื้อเราอาจจะกินน้อย ก็ไปชดเชยในมื้อถัดไป บางมื้อกินเยอะ ก็ไปลดลงในมื้อถัดไป โดยเฉพาะอย่างยิ่งผัก ถ้าบางมื้อกินน้อย ก็ไปชดเชย กินผักให้มากขึ้นในมื้อถัดไป ก็ได้เช่นกันค่ะ ';
                    break;
                  case ($userMessage == 'ท้องผูก ท้องอืด ทำอย่างไร?'): 
                        $userMessage = '👼 จริง ๆ แล้วปัญหาท้องผูกหรือท้องอืดอาจจะเกิดขึ้นได้เป็นปกติในคุณแม่ที่ตั้งครรภ์นะคะ เพราะมดลูกมีขนาดใหญ่ขึ้น เลยอาจจะไปกดทับลำไส้ได้ อย่างไรก็ตามคำแนะนำโดยทั่วไปเพื่อช่วยลดอาการท้องผูกก็คือ'."\n".
                        '👉 เพิ่มการกินผักผลไม้ทีละน้อย เพื่อให้ได้รับใยอาหารมากขึ้น'."\n".
                        '👉 ดื่มน้ำให้เพียงพอ โดยเฉพาะอย่างยิ่งเมื่อเพิ่มผักผลไม้ เพื่อให้อาหารผ่านลำไส้ได้ดีขึ้น'."\n".
                        '👉 ออกกำลังกายอย่างสม่ำเสมอ ภายใต้คำแนะนำของแพทย์ผู้ดูแล เพราะการออกกำลังกายจะช่วยให้ลำไส้มีการเคลื่อนไหวมากขึ้นค่ะ'."\n".
                        '👉 ถ้าท้องอืด อาจจะเกิดจากการกินอาหารด้วยความรวดเร็วเกินไป อาจจะลดปริมาณอาหารในแต่ละมื้อ แต่เพิ่มความถี่แทน หลีกเลี่ยงอาหารที่ทำให้เกิดแก๊ส เช่น กะหล่ำปลี หัวหอมใหญ่ ถั่วเมล็ดแห้ง และน้ำอัดลมค่ะ';
                    break;
                  case ($userMessage == 'แพ้ท้อง กินอย่างไร?'): 
                        $userMessage = '👼 ปกติแล้วอาการแพ้ท้องสามารถเกิดขึ้นได้จากการแปรปรวนของระดับฮอร์โมนในคุณแม่ตั้งครรภ์ และสามารถหายได้เองนะคะ โดยทั่วไปแล้วคำแนะนำด้านอาหารเพื่อช่วยบรรเทาอาการแพ้ท้องก็คือ'."\n".
                        '👉 กินอาหารทีละน้อย แต่บ่อยครั้งขึ้น'."\n".
                        '👉 หลีกเลี่ยงอาหารผัด ทอด ที่มีน้ำมันในปริมาณมาก เพราะอาจทำให้คลื่นไส้เพิ่มขึ้นได้'."\n".
                        '👉 ดื่มเครื่องดื่มที่มีรสเปรี้ยว เช่น น้ำมะนาว น้ำส้ม หรือน้ำขิง อาจช่วยบรรเทาอาการได้ค่ะ'."\n".
                        '👉 หลีกเลี่ยงอาหารที่มีกลิ่นแรง เพราะอาจทำให้คลื่นไส้ได้'."\n".
                        '👉 คุณแม่บางท่านจะมีอาการแพ้ท้องเป็นช่วงเวลาในแต่ละวัน พยายามสังเกตตัวเอง และกินอาหารในช่วงเวลาที่ไม่มีอาการแพ้ท้องค่ะ'."\n".
                        '👼 อย่างไรก็ตาม ถ้าอาการแพ้ท้องเป็นรุนแรง ไม่สามารถกินอาหารได้เป็นระยะเวลานาน ควรไปพบแพทย์เพื่อให้การรักษาที่เหมาะสมค่ะ';
                    break;
                   case ($userMessage == 'ไม่อิ่ม ทำอย่างไร?'): 
                        $userMessage = '👉 ก่อนอื่นเลย ให้ตรวจสอบตัวเองก่อนนะคะว่าเป็นคนที่กินอาหารเร็วแค่ไหน เพราะในบางครั้ง เพียงแต่กินอาหารให้ช้าลง เราก็จะพบว่าปริมาณอาหารเท่าเดิม ก็ทำให้เราอิ่มได้ค่ะ'."\n".
                        '👉 แต่ถ้าเรากินอาหารช้าลงแล้ว ยังพบว่าปริมาณอาหารที่แนะนำ ยังไม่อิ่ม ก็อาจจะค่อย ๆ เพิ่มปริมาณอาหารได้นะคะ อย่างไรก็ตามควรเพิ่มทีละน้อย และเพิ่มในสัดส่วนที่เหมาะสม (เพิ่มผักก่อน แล้วจึงเพิ่มเนื้อสัตว์ แล้วจึงเพิ่มข้าว) แล้วค่อย ๆ ตรวจสอบความอิ่มดู ก็จะทำให้เราหาปริมาณการกินที่เหมาะสมของตัวเองได้ในที่สุดค่ะ';
                    break;
                  case ($userMessage == 'อาหารอะไรที่ควรหลีกเลี่ยง?'): 
                        // $userMessage = '👉 อาหารหมักดอง เพราะอาจมีสารพิษปนเปื้อน '."\n".
                        // '👉 อาหารรสจัด เพราะอาจทำให้ไม่สบายท้องได้ง่ายขึ้น'."\n".
                        // '👉 อาหารสุก ๆ ดิบ ๆ ไม่สะอาด เรื่องของความปลอดภัยในอาหารถือว่าสำคัญมากในช่วงนี้ค่ะ'."\n".
                        // '👉 เครื่องดื่มที่มีแอลกอฮอล์ เพราะอาจเกิดอันตรายแก่ทารกในครรภ์ได้ค่ะ'."\n".
                        // '👉 เครื่องดื่มที่มีคาเฟอีน ในปริมาณน้อยอาจไม่มีปัญหา แต่ในบางคนอาจกระตุ้นให้เกิดอาการใจสั่น นอนไม่หลับ และทำให้ระบบขับถ่ายและระบบปัสสาวะรวนได้ ดังนั้นควรหลีกเลี่ยงในช่วงนี้ค่ะ';
                        $case = 1;
                        (new ReplyMessageController)->replymessage_menu11($replyToken,$user);
                    break;
                  case ($userMessage == 'อื่น ๆ (ฝากคำถามไว้ได้)'): 
                        $userMessage = 'หากมีข้อสงสัยสามารถสอบถามได้เลยค่ะ';
                    break;
                  case ($userMessage == 'สัดส่วนอาหาร'): 
                       $log_message = (new ReplyMessageController)->replymessage_food1($replyToken,$user);
                    break;
                  case ($userMessage == 'อาการผิดปกติ'): 
                        $userMessage = 'อาการผิดปกติ';
                    break;
                  case ($userMessage == 'พัฒนาการลูกน้อย'): 
                        $case = 35;
                        $userMessage = $user;
                    break;
                  case ($userMessage == 'การเตรียมตัวคลอด'): 
                        $userMessage = 'การเตรียมตัวคลอด';
                    break;
                  case ($userMessage == 'คำนวณค่าดัชนีมวลกายอย่างไร?'): 
                        $case = 18;
                        $userMessage  = 'https://health-track.in.th/knowledge/bmi-cal.jpg';
                    break;
                  case ($userMessage == 'คุณแม่รูปร่างต่างกันน้ำหนักควรเพิ่มเท่าไร?'): 
                        $case = 18;
                        $userMessage  = 'https://health-track.in.th/knowledge/incre-weight.jpg';
                    break;
                  case ($userMessage == 'อาหารที่คุณแม่ควรทาน'): 
                        $case = 1;
                        (new ReplyMessageController)->replymessage_menu3($replyToken,$user);
                    break;
                  // $case = 18;
                  // $userMessage  = 'https://remi.softbot.ai/food/'.$result.'.jpg';
                }
             }elseif ($userMessage == 'กระดกข้อเท้า' || $userMessage == 'ยกก้น'||$userMessage == 'นอนเตะขา'||$userMessage == 'นอนตะแคงยกขา'||$userMessage == 'คลานสี่ขา'||$userMessage == 'แมวขู่'||$userMessage == 'นั่งโยกตัว'||$userMessage == 'นั่งเตะขา'||$userMessage == 'ยืนงอเข่า' || $userMessage == 'ยืนเตะขาไปข้างหลัง'||$userMessage == 'ยืนเตะขาไปด้านข้าง'||$userMessage == 'ยืนเขย่งเท้า'||$userMessage == 'ยืนกางแขน'||$userMessage == 'ยืนแกว่งแขนสลับขึ้นลง'||$userMessage == 'ยืนย่ำอยู่กับที่' && $sequentsteps->seqcode == '0000') {

                      $case = 21;
                    switch($userMessage) {
                        
                  case ($userMessage == 'กระดกข้อเท้า'): 

                        $userMessage = '1';
                    break;
                  case ($userMessage == 'ยกก้น'): 
                        $userMessage = '2';
                    break;
                  case ($userMessage == 'นอนเตะขา'): 
                        $userMessage = '3';
                    break;

                  case ($userMessage == 'นอนตะแคงยกขา'): 
                        $userMessage = '4';
                    break;
                  case ($userMessage == 'คลานสี่ขา'): 
                        $userMessage = '5';
                    break;
                  case ($userMessage == 'แมวขู่'): 
                        $userMessage = '6';
                    break;

                   case ($userMessage == 'นั่งโยกตัว'): 
                        $userMessage = '7';
                    break;
                  case ($userMessage == 'นั่งเตะขา'): 
                        $userMessage = '8';
                    break;
                  case ($userMessage == 'ยืนงอเข่า'): 
                        $userMessage = '9';
                    break;


                  case ($userMessage == 'ยืนเตะขาไปข้างหลัง'): 
                        $userMessage = '10';
                    break;
                  case ($userMessage == 'ยืนเตะขาไปด้านข้าง'): 
                        $userMessage = '11';
                    break;
                  case ($userMessage == 'ยืนเขย่งเท้า'): 
                        $userMessage = '12';
                    break;

                   case ($userMessage == 'ยืนกางแขน'): 
                        $userMessage = '13';
                    break;
                  case ($userMessage == 'ยืนแกว่งแขนสลับขึ้นลง'): 
                        $userMessage = '14';
                    break;
                  case ($userMessage == 'ยืนย่ำอยู่กับที่'): 
                        $userMessage = '15';
                    break;

                }
            
             }elseif ($userMessage == 'น้ำหนักตัวที่เหมาะสม' ||(new checkmessageController)->match($array4, $userMessage) ) {
    
                  $users_register = (new SqlController)->users_register_select($user);
                  $user_Pre_weight = $users_register->user_Pre_weight;
                  $user_weight = $users_register->user_weight;
                  $user_height =  $users_register->user_height;
                  $bmi  = (new CalController)->bmi_calculator($user_Pre_weight,$user_height);
                  $weight_criteria  = (new CalController)->weight_criteria($bmi);

                    if ($weight_criteria =='น้ำหนักน้อย') {
                      $result='1';
                    } elseif ($weight_criteria =='น้ำหนักปกติ') {
                      $result='2';
                    } elseif ($weight_criteria == 'น้ำหนักเกิน') {
                      $result='3';
                    } elseif ($weight_criteria =='อ้วน') {
                      $result='4';
                    }
                  
                  $case = 18;
                  $userMessage  = 'https://health-track.in.th/food/'.$result.'.jpg';

             // }elseif ((new checkmessageController)->match($array, $userMessage )){
             //      // $userMessage = 'hhihih';
             //          $message_type = '03';
             //          $Message = $userMessage;
             //          $log_message = (new SqlController)->log_message($user,$Message,$message_type);

             //          $json1 = file_get_contents('data.json');
             //          $json= json_decode($json1);

             //                if(strpos($userMessage, 'อาบน้ำ') !== false ){
             //                  $input = 'อาบน้ำ';
             //                }elseif (strpos($userMessage, 'อุจจาระ') !== false || strpos($userMessage, 'ขี้') !== false || strpos($userMessage, 'อึ') !== false ) {
             //                  $input = 'อุจจาระ';
             //                }elseif (strpos($userMessage, 'ทาครีม') !== false ) {
             //                  $input = 'การดูแลผิวพรรณ';
             //                }elseif (strpos($userMessage, 'ครีมช่วยลดท้องลาย') !== false ) {
             //                  $input = 'ยาหรือครีมลดท้องลาย';
             //                }elseif (strpos($userMessage, 'แต่งตัว') !== false ||strpos($userMessage, 'เสื้อผ้า') !== false  ) {
             //                  $input = 'แต่งตัว';
             //                }elseif (strpos($userMessage, 'รองเท้า') !== false ) {
             //                  $input = 'รองเท้า';
             //                }elseif (strpos($userMessage, 'แหวน') !== false ) {
             //                  $input = 'แหวน';
             //                }elseif (strpos($userMessage, 'เพศสัมพันธ์') !== false ||strpos($userMessage, 'มีอะไรกัน') !== false ||strpos($userMessage, 'มีอะไรกับแฟน') !== false) {
             //                  $input = 'เพศสัมพันธ์';
             //                }elseif (strpos($userMessage, 'เดินห้าง') !== false ) {
             //                  $input = 'เดินห้าง';
             //                }elseif (strpos($userMessage, 'ใส่ตุ้มสะดือ') !== false ) {
             //                  $input = 'ใส่ตุ้มสะดือ';
             //                }elseif (strpos($userMessage, 'ทาเล็บ') !== false ) {
             //                  $input = 'การทาเล็บ';
             //                }elseif (strpos($userMessage, 'ย้อมผม') !== false || strpos($userMessage, 'สีผม') !== false || strpos($userMessage, 'ไฮไลต์') !== false) {
             //                  $input = 'ย้อมหรือไฮไลต์สีผม';
             //                }elseif (strpos($userMessage, 'แต่งหน้า') !== false ||strpos($userMessage, 'ทาลิปสติก') !== false||strpos($userMessage, 'ทาปาก') !== false ||strpos($userMessage, 'ทาลิป') !== false ) {
             //                  $input = 'แต่งหน้าทาปาก';
             //                }elseif (strpos($userMessage, 'ทำงาน') !== false ) {
             //                  $input = 'การทำงาน';
             //                }elseif (strpos($userMessage, 'เดินทาง') !== false ) {
             //                  $input = 'เดินทาง';
             //                }elseif (strpos($userMessage, 'ทำฟัน') !== false ) {
             //                  $input = 'ทำฟัน';
             //                }elseif (strpos($userMessage, 'ออกกำลังกาย') !== false ) {
             //                  $input = 'ออกกำลังกาย';
             //                }elseif (strpos($userMessage, 'กินยา') !== false ) {
             //                  $input = 'การใช้ยา';
             //                }elseif (strpos($userMessage, 'ปัสสาวะบ่อย') !== false || strpos($userMessage, 'ฉี่บ่อย') !== false) {
             //                  $input = 'ปัสสาวะบ่อย';
             //                }elseif (strpos($userMessage, 'ปัสสาวะ') !== false || strpos($userMessage, 'ฉี่') !== false ) {
             //                  $input = 'ปัสสาวะ';
             //                }elseif (strpos($userMessage, 'เหนื่อย') !== false ) {
             //                  $input = 'เหนื่อยง่ายเวลาออกแรง';
             //                }elseif (strpos($userMessage, 'คัดตึงเต้านม') !== false ||strpos($userMessage, 'เจ็บเต้านม') !== false ||strpos($userMessage, 'เจ็บนม') !== false ) {
             //                  $input = 'คัดตึงเต้านม';
             //                }elseif (strpos($userMessage, 'คันบริเวณหน้าท้อง') !== false ||strpos($userMessage, 'คันหน้าท้อง') !== false||strpos($userMessage, 'คันท้อง') !== false ||strpos($userMessage, 'คันตรงท้อง') !== false ||strpos($userMessage, 'คันตรงหน้าท้อง') !== false ) {
             //                  $input = 'คันบริเวณหน้าท้อง';
             //                }elseif (strpos($userMessage, 'ปวดเมื่อยบริเวณหลัง') !== false ||strpos($userMessage, 'ปวดหลัง') !== false||strpos($userMessage, 'เมื่อยหลัง') !== false ||strpos($userMessage, 'เจ็บเอว') !== false ||strpos($userMessage, 'ปวดเอว') !== false ||strpos($userMessage, 'เจ็บหลัง') !== false) {
             //                  $input = 'ปวดเมื่อยบริเวณหลัง';
             //                }elseif (strpos($userMessage, 'ตะคริวที่ขา') !== false ||strpos($userMessage, 'ตะคริว') !== false) {
             //                  $input = 'ตะคริวที่ขา';
             //                }elseif (strpos($userMessage, 'เท้าบวม') !== false ) {
             //                  $input = 'เท้าบวม';
             //                }elseif (strpos($userMessage, 'เส้นเลือดขอด') !== false ) {
             //                  $input = 'ป้องกันเส้นเลือดขอดที่ขา';
             //                }elseif (strpos($userMessage, 'เลือดออกจากช่องคลอด') !== false ||strpos($userMessage, 'เลือดออก') !== false ) {
             //                  $input = 'เลือดออกจากช่องคลอด';
             //                }elseif (strpos($userMessage, 'แพ้ท้องรุนแรง') !== false ||strpos($userMessage, 'แพ้ท้องหนัก') !== false) {
             //                  $input = 'แพ้ท้องรุนแรง';
             //                }elseif (strpos($userMessage, 'แพ้ท้อง') !== false ||strpos($userMessage, 'อ้วก') !== false ||strpos($userMessage, 'อาเจียน') !== false  ) {
             //                  $input = 'แพ้ท้อง';
             //                }elseif (strpos($userMessage, 'เจ็บครรภ์คลอดก่อนกำหนด') !== false ||strpos($userMessage, 'เจ็บท้องคลอดก่อนกำหนด') !== false ||strpos($userMessage, 'เจ็บท้องคลอด') !== false ||strpos($userMessage, 'ปวดท้อง') !== false ||strpos($userMessage, 'เจ็บท้อง') !== false ) {
             //                  $input = 'เจ็บครรภ์คลอดก่อนกำหนด';
             //                }elseif (strpos($userMessage, 'น้ำเดิน') !== false ) {
             //                  $input = 'น้ำเดิน';
             //                }elseif (strpos($userMessage, 'ปวดศีรษะ') !== false || strpos($userMessage, 'ตามัว') !== false||strpos($userMessage, 'จุกแน่นใต้ลิ้นปี่') !== false || strpos($userMessage, 'ปวดหัว') !== false || strpos($userMessage, 'อุจจาระลำบาก') !== false || strpos($userMessage, 'ขี้ลำบาก') !== false || strpos($userMessage, 'เวียนหัว') !== false ) {
             //                  $input = 'ปวดศีรษะ/ตามัว/จุกแน่นใต้ลิ้นปี่';
             //                }elseif (strpos($userMessage, 'ลูกดิ้นลดลง') !== false ||strpos($userMessage, 'ลูกไม่ดิ้น') !== false ||strpos($userMessage, 'ลูกไม่ค่อยดิ้น') !== false) {
             //                  $input = 'ลูกดิ้นลดลงหรือไม่ดิ้น';
             //                }elseif (strpos($userMessage, 'ไข้') !== false ) {
             //                  $input = 'ไข้ระหว่างการตั้งครรภ์';
             //                }elseif (strpos($userMessage, 'อาหารเสริม') !== false ) {
             //                  $input = 'อาหารเสริมขณะตั้งครรภ์';
             //                }elseif (strpos($userMessage, 'กลัวอ้วน') !== false ) {
             //                  $input = 'ความจำเป็นของอาหารขณะตั้งครรภ์';
             //                }elseif (strpos($userMessage, 'ของแสลง') !== false ||strpos($userMessage, 'ของที่ห้ามกิน') !== false ||strpos($userMessage, 'ของที่ไม่ควรกิน') !== false) {
             //                  $input = 'ของแสลง';
             //                }elseif (strpos($userMessage, 'ริดสีดวงทวารหนัก') !== false ||strpos($userMessage, 'ท้องผูก') !== false ||strpos($userMessage, 'ริดสีดวง') !== false ) {
             //                  $input = 'ริดสีดวงทวารหนัก';
             //                }elseif (strpos($userMessage, 'ท้องอืดหลังรับประทานอาหาร') !== false ||strpos($userMessage, 'ท้องอืดหลังกินข้าว') !== false ||strpos($userMessage, 'ท้องอืด') !== false ) {
             //                  $input = 'ท้องอืดหลังรับประทานอาหาร';
             //                }elseif (strpos($userMessage, 'ท้องลาย') !== false ) {
             //                  $input = 'ท้องลาย';
             //                }elseif (strpos($userMessage, 'คลอดตอนไหน') !== false ||strpos($userMessage, 'เมื่อไรจะคลอด') !== false ||strpos($userMessage, 'คลอดเมื่อไร') !== false  ) {
             //                  $input = 'คลอดตอนไหน';
             //                }elseif (strpos($userMessage, 'อาการใกล้คลอด') !== false ||strpos($userMessage, 'ใกล้คลอด') !== false ||strpos($userMessage, 'ใกล้คลอดจะมีอาการ') !== false ) {
             //                  $input = 'อาการแบบนี้แหละที่คุณแม่กำลังจะคลอด';
             //                }elseif (strpos($userMessage, 'คลอดเจ็บ') !== false ) {
             //                  $input = 'เวลาคลอดเจ็บไหม';
             //                }elseif (strpos($userMessage, 'พ่อ') !== false ) {
             //                  $input = 'คุณพ่อกับการคลอด';
             //                }elseif (strpos($userMessage, 'เตรียมตัวไปคลอด') !== false || strpos($userMessage, 'เตรียมตัวคลอด') !== false ) {
             //                  $input = 'เตรียมตัวไปคลอด';

             //                }elseif (strpos($userMessage, 'ดื่มกาแฟ') !== false || strpos($userMessage, 'กินกาแฟ') !== false ) {
             //                  $input = 'ดื่มกาแฟ';
             //                }elseif (strpos($userMessage, 'วัคซีน') !== false || strpos($userMessage, 'ฉีดยา') !== false ) {
             //                  $input = 'วัคซีนต่างๆระหว่างตั้งครรภ์';
             //                }elseif (strpos($userMessage, 'ยารักษาสิว') !== false || strpos($userMessage, 'ยาอันตราย') !== false ) {
             //                  $input = 'การใช้ยาอันตรายยารักษาสิว';
             //                }elseif (strpos($userMessage, 'วิตามินเสริม') !== false || strpos($userMessage, 'ยาบำรุง') !== false ) {
             //                  $input = 'ควรทานวิตามินเสริมหรือยาบำรุง';
             //                }elseif (strpos($userMessage, 'ดื่มนมวัว') !== false || strpos($userMessage, 'กินนมวัว') !== false ) {
             //                  $input = 'ดื่มนมวัว';
             //                }elseif (strpos($userMessage, 'ภาวะครรภ์เสี่ยง') !== false ) {
             //                  $input = 'ภาวะครรภ์เสี่ยง';
             //                }elseif (strpos($userMessage, 'เนื้องอก') !== false || strpos($userMessage, 'กินนมวัว') !== false ) {
             //                  $input = 'เนื้องอกระหว่างตั้งครรภ์';
             //                }elseif (strpos($userMessage, 'ปวดนิ้วมือ') !== false || strpos($userMessage, 'นิ้วเท้า') !== false ) {
             //                  $input = 'ปวดนิ้วมือนิ้วเท้า';
             //                }elseif (strpos($userMessage, 'ดื่มนม') !== false || strpos($userMessage, 'กินนม') !== false ) {
             //                  $input = 'การดื่มนม';
             //                }elseif (strpos($userMessage, 'นอนคว่ำ') !== false ) {
             //                  $input = 'นอนคว่ำ';
             //                }elseif (strpos($userMessage, 'อัลตร้าซาวด์') !== false ) {
             //                  $input = 'อัลตร้าซาวด์';
             //                }elseif (strpos($userMessage, 'ห้ามวิ่ง') !== false ) {
             //                  $input = 'ห้ามวิ่ง';
             //                }elseif (strpos($userMessage, 'ป่วยกินยา') !== false || strpos($userMessage, 'ป่วยทานยา') !== false|| strpos($userMessage, 'ไม่สบายทานยา' ) !== false|| strpos($userMessage, 'ไม่สบายกินยา') !== false ) {
             //                  $input = 'ป่วยกินยา';
             //                }elseif (strpos($userMessage, 'บุหรี่') !== false ) {
             //                  $input = 'บุหรี่';
             //                }elseif (strpos($userMessage, 'เหล้า') !== false ) {
             //                  $input = 'เหล้า';
             //                }elseif (strpos($userMessage, 'ลูกโต') !== false ) {
             //                  $input = 'ทำให้ลูกโต';
             //                }elseif (strpos($userMessage, 'น้ำมะพร้าว') !== false ) {
             //                  $input = 'น้ำมะพร้าว';
             //                }elseif (strpos($userMessage, 'ทุเรียน') !== false) {
             //                  $input = 'ทุเรียน';
             //                }elseif (strpos($userMessage, 'เพลงโมสาท') !== false ) {
             //                  $input = 'เพลงโมสาท';
             //                }elseif (strpos($userMessage, 'เสียงดนตรี') !== false ) {
             //                  $input = 'เสียงดนตรี';
             //                }elseif (strpos($userMessage, 'ความเครียด') !== false ||strpos($userMessage, 'รู้สึกเครียด') !== false) {
             //                  $input = 'ความเครียดของแม่';
             //                }elseif (strpos($userMessage, 'เก้าอี้โยก') !== false) {
             //                  $input = 'เก้าอี้โยก';
             //                }elseif (strpos($userMessage, 'คุยกับลูก') !== false ||strpos($userMessage, 'คุยกับเด็ก') !== false) {
             //                  $input = 'การพูดคุยกับเด็ก';
             //                }elseif (strpos($userMessage, 'เครื่องบิน') !== false) {
             //                  $input = 'คนท้องขึ้นเครื่องบิน';
             //                }elseif (strpos($userMessage, 'ลูกสะอึก') !== false) {
             //                  $input = 'ลูกสะอึก';
             //                }elseif (strpos($userMessage, 'อาหารที่ควรหลีกเลี่ยง') !== false || strpos($userMessage, 'อาหารที่ไม่ควรกิน') !== false || strpos($userMessage, 'อาหารที่ควรงด') !== false|| strpos($userMessage, 'อาหารที่ห้ามกิน') !== false) {
             //                  $input = 'อาหารที่ควรหลีกเลี่ยง';
             //                }elseif (strpos($userMessage, 'เจาะถุงน้ำคร่ำ') !== false) {
             //                  $input = 'เจาะถุงน้ำคร่ำ';
             //                }
             //                elseif (strpos($userMessage, 'แกงบอน') !== false) {
             //                  $input = 'แกงบอน';
             //                }elseif (strpos($userMessage, 'ลาบดิบ') !== false) {
             //                  $input = 'ลาบดิบ';
             //                }elseif (strpos($userMessage, 'ซูชิ') !== false) {
             //                  $input = 'ซูชิ';
             //                }elseif (strpos($userMessage, 'เบียร์') !== false) {
             //                  $input = 'เบียร์';
             //                }elseif (strpos($userMessage, 'น้ำชา') !== false) {
             //                  $input = 'น้ำชา';
             //                }elseif (strpos($userMessage, 'ชาดอกคำฝอย') !== false) {
             //                  $input = 'ชาดอกคำฝอย';
             //                }elseif (strpos($userMessage, 'ชาสมุนไพร') !== false) {
             //                  $input = 'ชาสมุนไพร';
             //                }elseif (strpos($userMessage, 'ชาขิง') !== false) {
             //                  $input = 'ชาขิง';
             //                }elseif (strpos($userMessage, 'ชาตะไคร้') !== false) {
             //                  $input = 'ชาตะไคร้';
             //                }elseif (strpos($userMessage, 'ชาใบเตย') !== false) {
             //                  $input = 'ชาใบเตย';
             //                }elseif (strpos($userMessage, 'ชามะตูม') !== false) {
             //                  $input = 'ชามะตูม';
             //                }elseif (strpos($userMessage, 'ชาโป๊ยกั๊ก') !== false) {
             //                  $input = 'ชาโป๊ยกั๊ก';
             //                }elseif (strpos($userMessage, 'ชาเปปเปอร์มินต์') !== false) {
             //                  $input = 'ชาเปปเปอร์มินต์';
             //                }elseif (strpos($userMessage, 'ชากุหลาบ') !== false) {
             //                  $input = 'ชากุหลาบ';
             //                }elseif (strpos($userMessage, 'ชาเขียว') !== false) {
             //                  $input = 'ชาเขียว';
             //                }elseif (strpos($userMessage, 'ชานมไข่มุก') !== false) {
             //                  $input = 'ชานมไข่มุก';
             //                }elseif (strpos($userMessage, 'กุ้งเต้น') !== false) {
             //                  $input = 'กุ้งเต้น';
             //                }elseif (strpos($userMessage, 'ส้มตำ') !== false) {
             //                  $input = 'ส้มตำ';
             //                }elseif (strpos($userMessage, 'กิมจิ') !== false) {
             //                  $input = 'กิมจิ';
             //                }elseif (strpos($userMessage, 'รสจัด') !== false ||strpos($userMessage, 'ทานเผ็ดมาก') !== false ||strpos($userMessage, 'กินเผ็ดมาก') !== false|| strpos($userMessage, 'กินเผ็ดบ่อย') !== false  ) {
             //                  $input = 'รสจัด';
             //                }elseif (strpos($userMessage, 'ปลาแซลมอน') !== false) {
             //                  $input = 'ปลาแซลมอน';
             //                }elseif (strpos($userMessage, 'มะม่วงหาวมะนาวโห่') !== false) {
             //                  $input = 'มะม่วงหาวมะนาวโห่';
             //                }elseif (strpos($userMessage, 'ยาระบาย') !== false) {
             //                  $input = 'ยาระบาย';
             //                }elseif (strpos($userMessage, 'กินคลีน') !== false || strpos($userMessage, 'กินอาหารคลีน') !== false || strpos($userMessage, 'ทานคลีน') !== false|| strpos($userMessage, 'ทานอาหารคลีน') !== false) {
             //                  $input = 'กินคลีน';
             //                }elseif (strpos($userMessage, 'ถั่วงอก') !== false) {
             //                  $input = 'ถั่วงอก';
             //                }elseif (strpos($userMessage, 'ว่านหางจรเข้') !== false ||strpos($userMessage, 'ว่านหางจระเข้') !== false ) {
             //                  $input = 'ว่านหางจระเข้';
             //                }elseif (strpos($userMessage, 'ปลาร้า') !== false) {
             //                  $input = 'ปลาร้า';
             //                }elseif (strpos($userMessage, 'โกโก้') !== false) {
             //                  $input = 'โกโก้';
             //                }elseif (strpos($userMessage, 'กรดไหลย้อน') !== false) {
             //                  $input = 'กรดไหลย้อน';
             //                }elseif (strpos($userMessage, 'เบื่ออาหาร') !== false ||strpos($userMessage, 'ไม่อยากกินข้าว') !== false||strpos($userMessage, 'ไม่อยากอาหาร') !== false) {
             //                  $input = 'เบื่ออาหาร';
             //                }
             //      foreach($json->data as $item)
             //      {
             //          if($item->id == $input)
             //          {
             //             $userMessage = $item->content;
             //             $case = 1;
             //          }
             //      }
            }elseif (strpos($userMessage, 'hello') !== false || strpos($userMessage, 'สวัสดี') !== false || strpos($userMessage, 'ดีจ้า') !== false || strpos($userMessage, 'เห้ย') !== false || strpos($userMessage, 'เฮ้ย') !== false || strpos($userMessage, 'Hello') !== false || strpos($userMessage, 'หวัดดี') !== false || strpos($userMessage, 'ว่าไง') !== false || strpos($userMessage, 'hi') !== false || strpos($userMessage, 'ฮาย') !== false || strpos($userMessage, 'Hi') !== false || strpos($userMessage, 'ฮะโหล') !== false) {
           
                    $message_type = '01';
                    $Message = $userMessage;
                    $log_message = (new SqlController)->log_message($user,$Message,$message_type);

                    $case = 1; 
                    $res = $bot->getProfile($user);
                    if ($res->isSucceeded()) {
                        $profile = $res->getJSONDecodedBody();
                        $userMessage  = $profile['displayName'];
                       
                    } 
                    $userMessage  = 'สวัสดีค่ะ คุณ'.$userMessage;

                    $message_type = '02';
                    $Message = $userMessage;
                   $log_message = (new SqlController)->log_message_bot_to_mom($user,$Message,$message_type);
            }elseif (strpos($userMessage, 'ขอบคุณ') !== false ||strpos($userMessage, 'โอเค') !== false ) {
                    $message_type = '01';
                    $Message = $userMessage;
                    $log_message = (new SqlController)->log_message($user,$Message,$message_type);
                    $case = 1; 
                    $userMessage  = 'ยินดีค่ะ^^';

                    $message_type = '02';
                    $Message = $userMessage;
                     $log_message = (new SqlController)->log_message_bot_to_mom($user,$Message,$message_type);
            }elseif ( $userMessage == 'เรมี่' ||$userMessage == 'Remi'||$userMessage == 'remi' || strpos($userMessage, 'เรมี่') !== false  ) {
                    $message_type = '01';
                    $Message = $userMessage;
                    $log_message = (new SqlController)->log_message($user,$Message,$message_type);
                    $case = 1; 
                    $userMessage  = 'ว่าไงคะ มีอะไรให้ช่วยไหมคะ';

                    $message_type = '02';
                    $Message = $userMessage;
                    $log_message = (new SqlController)->log_message_bot_to_mom($user,$Message,$message_type);
            }elseif ( $userMessage == 'ทดลอง') {
                   $case = 1;
                   //   $userMessage  = 'ว่าไงคะ มีอะไรให้ช่วยไหมคะ';
                    // $log_message = (new noticeController)->test_noti();
                  //(new ReplyMessageController)->info_exercise_diary($replyToken,$user);
                   (new ReplyMessageController)->replymessage_menu11($replyToken,$user);

            }elseif (strpos($userMessage, 'ลูกน้อยสัปดาห์ที่:') !== false) {
                  $case = 1;
                  $pieces = explode(":", $userMessage);
                  $preg_week  = str_replace("","",$pieces[1]);
                  $pregnants = (new SqlController)->pregnants($preg_week);
                  $descript = $pregnants->descript;
                  $userMessage  =  $descript;
                 
            }elseif ( $userMessage == 'วิดีโอการใช้งาน') {
                  $case = 1; 
                  $userMessage  = 'https://www.youtube.com/watch?v=8A7Q74ZZGgI&feature=youtu.be';

            }elseif ($userMessage == 'ความรู้เพิ่มเติม' && $sequentsteps->seqcode == '0000'  ) {
                  $case = 19;
                  $userMessage  = '0';

            }elseif ($userMessage == 'บันทึกประจำวัน' && $sequentsteps->seqcode == '0000'  ) {
                  $case = 41;
                  $userMessage  = '0';

            }elseif ($userMessage == 'แนะนำการออกกำลังกาย' && $sequentsteps->seqcode == '0000'  ) {
                  //$case = 16;
                  $case = 20 ;
            }elseif ($userMessage == 'บันทึกข้อมูลคุณแม่' && $sequentsteps->seqcode == '0000'  ) {
                  $case = 24;
                  $userMessage  = $user;

            }elseif (strpos($userMessage, 'แนะนำเมนูอาหาร') !== false ||strpos($userMessage, 'เมนูอาหาร') !== false ||strpos($userMessage, 'แนะนำเมนู') !== false && $sequentsteps->seqcode == '0000'  ){
                   
                $case = 26;

            // }elseif (strpos($userMessage, 'อาหารเช้า') !== false ||strpos($userMessage, 'อาหารกลางวัน') !== false ||strpos($userMessage, 'อาหารเย็น') !== false||strpos($userMessage, 'อาหารว่าง') !== false){
 //ข้อมูลการใช้งาน
            }elseif ($userMessage == 'แนะนำการใช้งาน' && $sequentsteps->seqcode == '0000'  ) {
                $case = 27;    

//Liff กราฟ for GDM
            

            }elseif($userMessage == 'quick' || $userMessage == 'GDM'|| $userMessage == 'gdm'){

              return (new ReplyMessageController)->quick_reply($replyToken,$user);

            }elseif ($userMessage == 'กราฟน้ำตาลในเลือด') {
              
              $graph_blood = (new diaryController)->graph_sugar_blood($user);
            }else{
                    $da =  (new CalController)->cal_food($userMessage);
                         if($da==null){
                            $Message = $userMessage;
                               //  $x_tra = "ตั้งครรภ์".$userMessage;
                               //  $newStr =  preg_replace("[ ]","",$x_tra);

                               //  $url = 'https://www.googleapis.com/customsearch/v1?&cx=011030528095328264272:_0c9oat4ztq&key=AIzaSyDmVU8aawr5mNpqbiUdYMph8r7K-siKn-0&q='. $newStr;

                               //  $json= file_get_contents($url);
                               //  $events = json_decode($json, true);
                               //  // $title= $events['items'][0]['title'];
                               //  $userMessage = 'ฉันยังมีความรู้ไม่มากพอลองเข้าไปดูเพิ่มเติมได้ตามลิงค์นี้เลยนะคะ '."\n".$events['items'][0]['link'];
                               // // $userMessage = 'ดิฉันไม่เข้าใจค่ะ';
                               //  $case = 1;
                               //  $message_type = '01';
                               //  $log_message = (new SqlController)->log_message($user,$Message,$message_type);
                                // DB::insert('insert into log_message (user_id,message,created_at) values (?, ?, ?)', [$user, $Message, NOW()]);
                          
                          $text =  json_encode($Message, JSON_UNESCAPED_UNICODE );
                          $text1 = str_replace('"', "", $text);
                          $projectId = 'remiai-29f47';
                          $sessionId = '2f77c150-fc27-fc5b-b1c9-828de82d2d82';
                          $languageCode = 'th';
                          $userMessage =  $this->detect_intent_texts($projectId, $text1, $sessionId,$languageCode);
                          $case = 1;
                          // $userMessage =  'ยังไม่เข้าใจ';
                            if(strpos($userMessage, 'ยังไม่เข้าใจ') !== false){
                              $x_tra = "ตั้งครรภ์".$Message;
                                $newStr =  preg_replace("[ ]","",$x_tra);

                                // $url = 'https://www.googleapis.com/customsearch/v1?&cx=011030528095328264272:_0c9oat4ztq&key=AIzaSyDmVU8aawr5mNpqbiUdYMph8r7K-siKn-0&q='. $newStr;
                                $url = 'https://www.googleapis.com/customsearch/v1?&cx=011030528095328264272:vkf0_xinhse&key=AIzaSyDbF0EljLtUDmazRWJObKL9TeRDxXJk5Ns&q='. $newStr;
                                

                                $json= file_get_contents($url);
                                $events = json_decode($json, true);
                                // $title= $events['items'][0]['title'];
                                // $userMessage = 'ฉันยังมีความรู้ไม่มากพอลองเข้าไปดูเพิ่มเติมได้ตามลิงค์นี้เลยนะคะ '."\n".$events['items'][0]['link'];
                                 $userMessage = 'ฉันยังมีความรู้ไม่มากพอลองเข้าไปดูเพิ่มเติมได้ตามลิงค์นี้เลยนะคะ '."\n".$events['items'][0]['link'];
                               // $userMessage = 'ดิฉันไม่เข้าใจค่ะ';
                                $case = 1;
                                $message_type = '01';
                                $log_message = (new SqlController)->log_message($user,$Message,$message_type);

                                $message_type = '02';
                                $Message = $userMessage;
                                $log_message = (new SqlController)->log_message_bot_to_mom($user,$Message,$message_type);
                          }else{
                                $message_type = '01';
                                $log_message = (new SqlController)->log_message($user,$Message,$message_type);

                                $message_type = '02';
                                $Message = $userMessage;
                                $log_message = (new SqlController)->log_message_bot_to_mom($user,$Message,$message_type);
                          }
                         }else{
                                $case = 1;
                                $comma_separated = implode("\n", $da);
                                $userMessage = $comma_separated." นะคะ";
                                $Message =$userMessage;
                                $message_type = '01';
                                $log_message = (new SqlController)->log_message($user,$Message,$message_type);
                                $message_type = '02';
                                $Message = $userMessage;
                                $log_message = (new SqlController)->log_message_bot_to_mom($user,$Message,$message_type);
                         }
            }
           
            $last_chat = (new SqlController)->last_chat($user);
            return (new ReplyMessageController)->replymessage($replyToken,$userMessage,$case,$user);
    }

//api dialogflow
        public function detect_intent_texts($projectId, $text, $sessionId , $languageCode)
    {
        // new session
        $test = array('credentials' => 'client-secret.json');


        $sessionsClient = new SessionsClient($test);
        $session = $sessionsClient->sessionName($projectId, $sessionId ?: uniqid());
        // printf('Session path: %s' . PHP_EOL, $session);
     
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

        // output relevant info
        // print(str_repeat("=", 20) . PHP_EOL);
        // printf('Query text: %s' . PHP_EOL, $queryText);
        // printf('Detected intent: %s (confidence: %f)' . PHP_EOL, $displayName,
        //     $confidence);
        // print(PHP_EOL);
        // printf('Fulfilment text: %s' . PHP_EOL, $fulfilmentText);
        $sessionsClient->close();
         return $fulfilmentText;
       
    }
   
}
