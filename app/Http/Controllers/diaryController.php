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
use App\Models\doctor as doctor;
use App\Models\birth_date as birth_date;
use App\Models\blood_sugar as blood_sugar;
use App\Models\fetal_movement as fetal_movement;
use App\Models\tracker_activity as tracker_activity;

use Illuminate\Database\Query\Builder;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use View;
use DB;
use Carbon\Carbon;
use DateTime;

use Auth;
use Hash;
use Session;

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
//define('LINE_MESSAGE_CHANNEL_SECRET','a06f8f521aabe202f1ce7427b4e52d1b');
//define('LINE_MESSAGE_ACCESS_TOKEN','UWrfpYzUUCCy44R4SFvqITsdWn/PeqFuvzLwey51hlRA1+AX/jSyCVUY7V2bPTkuoaDzmp1AY5CfsgFTIinxzxIYViz+chHSXWsxZdQb5AyZu7U67A9f18NQKE/HfGNrZZrwNxWNUwVJf2AszEsCvgdB04t89/1O/w1cDnyilFU=');

class diaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
   
    public function show_food($id)
    {

        //$user = 'U2dc636d2cd052e82c29f5284e00f69b9';

           $record = tracker::where('user_id',$id)
                               ->whereNull('deleted_at')
                            // ->where('created_at', '>=',Carbon::now()->subDays(15))
                               ->get();
        return View::make('food_diary')->with('record',$record);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_vitamin($user)
    {

        //$user = 'U2dc636d2cd052e82c29f5284e00f69b9';
           $record = tracker::where('user_id',$user)
                               ->whereNull('deleted_at')
                            // ->where('created_at', '>=',Carbon::now()->subDays(15))
                               ->get();
        return View::make('vitamin_diary')->with('record',$record);
    }

     public function show_exercise($user)
    {

        //$user = 'U2dc636d2cd052e82c29f5284e00f69b9';
          
           $record = tracker::where('user_id',$user)
                               ->whereNull('deleted_at')
                            //    ->where('created_at', '>=',Carbon::now()->subDays(15))
                               ->get();


        return View::make('exercise_diary')->with('record',$record);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

      public function show_weight($user)
    {

        //$user = 'U2dc636d2cd052e82c29f5284e00f69b9';
         // $user = 'Udb5efc89a4729c093051ce8813454223';
             $pre_weight = users_register::where('user_id', $user)
                     ->whereNull('deleted_at')
                     ->first();
              $record = DB::table('RecordOfPregnancy')
                     ->select('preg_week','preg_weight')
                     ->where('user_id', $user)
                     ->whereNull('deleted_at')
                     ->distinct()
                     ->orderBy('preg_week', 'asc')
                     ->get();

        return View::make('weight_diary')->with('record',$record)->with('pre_weight',$pre_weight);
    }
     public function personal_doctor_confirm(Request $request)
    {
        // $url= $request->getRequestUri();
        // $parsedUrl = parse_url($url);
        // $parsedUrl = $parsedUrl['query'];
        // $id = str_replace('liff.state=%3Fkey%3D','', $parsedUrl);
        // $id = str_replace('key=','', $parsedUrl);
        // dd();
        // $doctor =  doctor::where('doctor_id',$id)->first();
        // return View::make('personal_doctor_confirm')->with('record',$doctor);
         parse_str($request->getQueryString(), $query);

              // 🔑 Laravel จะเปลี่ยน liff.state -> liff_state
    $stateRaw = $request->query('liff_state');

    if (!$stateRaw) {
        abort(400, 'missing liff.state');
    }

    // decode
    $state = urldecode($stateRaw);
    // ?user_id=https://health-track.in.th/personal_doctor/tu1234

    parse_str(ltrim($state, '?'), $stateParams);

    if (!isset($stateParams['user_id'])) {
        abort(400, 'missing user_id');
    }

    // ดึง tu1234
    $userId = basename($stateParams['user_id']);
            dd($userId);
            //return View::make('personal_doctor_confirm')->with('record',$doctor);
    }
       public function p_doctor(Request $request)
    {
        $doctor_id = $request->input('doctor_id'); 
        $roomId = $request->input('roomId'); 
        $user_id_line = $request->input('user_id_line'); 
        $mom_doctor = (new SqlController)->personal_doctor_mom_count($user_id_line);
        $mom_doctor = json_encode($mom_doctor,true); 
                  if($mom_doctor == null){
                     $sequentsteps = (new SqlController)->personal_doctor_mom($user_id_line,$doctor_id);
                  }else{
                     $update = (new SqlController)->personal_doctor_mom_update($user_id_line);
                     $sequentsteps = (new SqlController)->personal_doctor_mom($user_id_line,$doctor_id);
                  }
        
        $httpClient = new CurlHTTPClient(config('line.access_token'));
        $bot = new LINEBot($httpClient, [
            'channelSecret' => config('line.channel_secret')
        ]);
                $Message1 =  'เรียบร้อยแล้วค่ะ';
        $textMessageBuilder = new TextMessageBuilder($Message1);
          

        $response = $bot->pushMessage( $user_id_line ,$textMessageBuilder);
        $response->getHTTPStatus() . ' ' . $response->getRawBody();



      
    }
      public function weight_warning(Request $request)
    {

        $doctor_id = $request->input('doctor_id');  
        $user = $request->input('user_id_line'); 
        $Message = $request->input('text'); 
        if ($Message !=  '') {
            $doctor = (new SqlController)->personal_doctor_select($doctor_id);
    
            $message_type = '03';
            $log_message = (new SqlController)->log_message_doctor_to_mom($doctor_id,$user,$Message,$message_type);
            
            $httpClient = new CurlHTTPClient(config('line.access_token'));
            $bot = new LINEBot($httpClient, [
                'channelSecret' => config('line.channel_secret')
            ]);
    
            $textMessageBuilder = new TextMessageBuilder('👩‍⚕ : '.$Message);
            $response = $bot->pushMessage( $user ,$textMessageBuilder);
            $response->getHTTPStatus() . ' ' . $response->getRawBody();  
            		
        }

        //return view('management.info',["doctor_id" => $doctor_id,'user_id' => $user,'all_message' => $message]);
        //retrun redirect('management.info')->with( ["doctor_id" => $doctor_id,'user_id' => $user,'all_message' => $message] );
        // return redirect('info/'.$user)->with('status', 'Profile updated!');
        return redirect('info/'.$user)->with('status', 'Profile updated!');

    }
      public function disclaimer()
    {
      return View::make('disclaimer');
    }
///liff gdm
    public function record_sugar_blood()
    {
        return View::make('liff_sugar_blood');
    }  
    
    public function graph_sugar_blood($user_id)
    {
        $graphdata = (new SqlController)->blood_sugar_select($user_id);
        $blood_sugar = blood_sugar::where('user_id',$user_id)
                        ->whereNull('deleted_at')
                        ->get();   
        // $blood_sugar =  blood_sugar::where('user_id',$user_id)->whereNull('deleted_at')->select("datetime", \DB::raw('(CASE 
        // WHEN (( blood_sugar.meal = 4 AND blood_sugar.blood_sugar >120) OR (( blood_sugar.time_of_day  = 1 AND blood_sugar.blood_sugar>95) OR (blood_sugar.time_of_day = 3 and blood_sugar.blood_sugar>140 ))) THEN "HIGHเกินเกณฑ์" 
        // WHEN blood_sugar.blood_sugar < 60 THEN "LOWต่ำกว่าเกณฑ์" 
        // ELSE "NORMALปกติ" 
        // END) AS status_lable'))->groupBy('datetime')
        // ->get();    

      $blood_sugar = blood_sugar::where('user_id', $user_id)
                    ->whereNull('deleted_at')
                    ->select(
                        'datetime',
                        \DB::raw('MAX(meal) as meal'),
                        \DB::raw('MAX(time_of_day) as time_of_day'),
                        \DB::raw('MAX(blood_sugar) as blood_sugar'),
                        \DB::raw("
                            CASE
                                WHEN (
                                    (MAX(meal) = 4 AND MAX(blood_sugar) > 120)
                                    OR (
                                        (MAX(time_of_day) = 1 AND MAX(blood_sugar) > 95)
                                        OR (MAX(time_of_day) = 3 AND MAX(blood_sugar) > 140)
                                    )
                                ) THEN 'HIGHเกินเกณฑ์'
                                WHEN MAX(blood_sugar) < 60 THEN 'LOWต่ำกว่าเกณฑ์'
                                ELSE 'NORMALปกติ'
                            END AS status_lable
                        ")
                    )
                    ->groupBy('datetime')
                    ->get();

        
        // $bargraph = blood_sugar::where('user_id',$user_id)
        // ->whereNull('deleted_at')
        // ->get();   

        return View::make("liff_graph_sugar")->with('graphdata', $graphdata)->with('blood_sugar',$blood_sugar);
    }    

    public function remove_bloodsugar($id){
        $users_register = blood_sugar::where('id', $id)
                           ->update(['deleted_at'=>NOW()]);
                     
        return redirect()->back();

    }
    public function fetal_movement($id)
    {


        $today = date("Y-m-d");
        // $id = 'U2fc167382b22b0c70c0a2a16081a475e';
        $fetal_movement = fetal_movement::where('user_id',  $id)
                                        // ->where('date', $today)
                                        ->whereNull('deleted_at')
                                        ->orderBy('date', 'DESC')
                                        ->get(); 

        $fetal_movement_today = fetal_movement::where('user_id',  $id)
                                        ->where('date', $today)
                                        ->whereNull('deleted_at')
                                        ->get(); 
        
        // if ( $kickdate->isEmpty()) {
 
        return View::make("liff_fetal_movement",compact('id'))->with('fetal_movement', $fetal_movement)->with('fetal_movement_today', $fetal_movement_today);

     
        // // return redirect()->back()->with('message', 'query data that day!' );  
       
    // return redirect()->back()->with('message', 'query data that day!' );    
    // \\return View::make('liff_fetal_movement');
    }
    public function getbabykick(Request $request)
    {


        // $url = explode(',', $date);
        // $id = $url[1]; 
        // // // return $user_id ;
        // $date = $url[0] ; 
        $id = $request->input('user_id'); 
        $date = $request->input('date'); 
        // dd($date);
        $fetal_movement = fetal_movement::where('user_id', $id)
                                        ->where('date',$date)
                                        ->whereNull('deleted_at')
                                        ->get(); 
         // Fetch all records
        //  $userData['data'] = $fetal_movement;

 
        return View::make("liff_fetal_movement")->with('fetal_movement', $fetal_movement);
        // return redirect()->back()->with('message', 'query data that day!' );    

    }    

    public function savebabykick(Request $request){
        $this->validate(request(), [
            'date' => 'required'
        ]);
        $user_id = $request->input('user_id'); 
        $date = $request->input('date'); 

        $preg_week = users_register::select('preg_week')
                    ->whereNull('deleted_at')
                    ->where('user_id',$user_id)
                    ->first();
        // $num_morning = $request->input('num_morning');
        // $num_noon = $request->input('num_noon');
        // $num_evening = $request->input('num_evening'); 

        $kickdate = fetal_movement::where('date', $date)
                                   ->where('user_id',$user_id)
                                   ->whereNull('deleted_at')
                                   ->get(); 

        $data = array(
                        "user_id" => $user_id,
                        "date" => $date,
                        "preg_week" => $preg_week->preg_week,
                    );
         
        if ( $kickdate->isEmpty()) {
            $birth_date = fetal_movement::create( $data );
         
        }


        if ( !empty($request->input('num_morning'))) {
            $num_morning = $request->input('num_morning');
            $birth_date = fetal_movement::where('user_id',$user_id )
                                        ->where('date',$date)
                                        ->whereNull('deleted_at')
                                        ->update(['num_morning'=>$num_morning]);
        }

        if ( !empty($request->input('num_noon'))) {
            $num_noon = $request->input('num_noon');
            $birth_date = fetal_movement::where('user_id',$user_id )
                                        ->where('date',$date)
                                        ->whereNull('deleted_at')
                                        ->update(['num_noon'=>$num_noon]);
        }
        if ( !empty($request->input('num_evening'))) {
            $num_evening = $request->input('num_evening');
            $birth_date = fetal_movement::where('user_id',$user_id )
                                        ->where('date',$date)
                                        ->whereNull('deleted_at')
                                        ->update(['num_evening'=>$num_evening]);
        }

        return redirect()->back()->with('message', 'บันทึกเรียบร้อยค่ะ' );    

    }
    public function birth_noti()
    {

        // $doctor =  doctor::where('doctor_id',$id)->first();
        //dd($doctor);
   
        return View::make('liff_birth');
    }

    public function insert_birth_noti(Request $request){

        $this->validate(request(), [
            'birthdate' => 'required',
            'week' => 'required',
        ]);
        $user_id = $request->input('user_id'); 
        $datetime = $request->input('birthdate'); 
        $week = $request->input('week');
        // $datetime = date('Y-m-d H:i:s', strtotime($request->input('datetime')));
        
        $birth_date = birth_date::create(request(['user_id','birthdate', 'week']));

        $users_register = users_register::where('user_id', $user_id)
                                       ->update(['status'=>'0']);

        $httpClient = new CurlHTTPClient(config('line.access_token'));
        $bot = new LINEBot($httpClient, [
            'channelSecret' => config('line.channel_secret')
        ]);
            
        $textMessageBuilder = new TextMessageBuilder('👩‍⚕ : ในอีก 3 เดือน คุณแม่ต้องเข้ารับการตรวจอีกครั้ง โดยจะมีการแจ้งเตือนอีกครั้งค่ะ');
        $response = $bot->pushMessage( $user_id ,$textMessageBuilder);
        $response->getHTTPStatus() . ' ' . $response->getRawBody();          

        return redirect()->back()->with('message', 'แจ้งคลอดเรียบร้อยค่ะ:)');
    }

    #การแจ้งลูกดิ้น โดยในหน้าบันทึกลูกดิ้น
    public function noti_fetalmove(Request $request, $id){
        $users_register = (new SqlController)->users_register_select($id);
        $name = $users_register->user_name;
        $tel = $users_register->phone_number;
        
        $httpClient = new CurlHTTPClient(config('line.access_token'));
        $bot = new LINEBot($httpClient, [
            'channelSecret' => config('line.channel_secret')
        ]);
    
        // $textMessageBuilder = new TextMessageBuilder('ข้อความจากคุณ: '.$name.' แจ้งลูกดิ้นผิดปกติ เบอร์โทรผู้ป่วย: '. $tel);
        $textMessageBuilder = new TextMessageBuilder('แจ้งลูกดิ้นผิดปกติ คุณแม่ควรพบแพทย์อย่างเร่งด่วนค่ะ');
        $response = $bot->pushMessage( $id ,$textMessageBuilder);
        $response->getHTTPStatus() . ' ' . $response->getRawBody();   
        return redirect()->back()->with('message', 'แจ้งแพทย์เรียบร้อยค่ะ');       
    
    }

    public function record_weight($id){
        $record_weight = (new SqlController)->get_weight_mom($id);
        return View::make("liff_weight",compact('id'))->with('record_weight', $record_weight);
    }

    public function saveweight(Request $request){

        // $this->validate(request(), [
        //     'date' => 'required'
        // ]);
        $user_id = $request->input('user_id'); 
        $preg_week = $request->input('preg_week'); 
        $preg_weight = $request->input('preg_weight');

        $RecordOfPregnancy = RecordOfPregnancy::where('user_id', $user_id)
                                   ->where('preg_week', $preg_week)
                                   ->whereNull('deleted_at')
                                   ->get(); 
         
        if ( $RecordOfPregnancy->isEmpty()) {
            $RecordOfPregnancy = RecordOfPregnancy::create(request(['user_id','preg_week','preg_weight']));
         
        }else{
                $RecordOfPregnancy = RecordOfPregnancy::where('user_id', $user_id)
                                            ->where('preg_week', $preg_week)
                                            ->update(['preg_weight' =>$preg_weight]);
        }
        return redirect()->back()->with('message', 'บันทึกเรียบร้อยค่ะ' );    

    }
    public function record_diary($id)
    {
        $today = date("Y-m-d");

        // $tracker = tracker_activity::join('tracker', 'tracker.user_id', '=', 'tracker_activity.user_id')
        //        ->where('tracker.date', '=', 'tracker_activity.date')
        //        ->whereNull(['tracker.deleted_at','tracker_activity.deleted_at'])
        //        ->get();
        // $tracker_today = tracker_activity::join('tracker', 'tracker.user_id', '=', 'tracker_activity.user_id')
        //         ->where('tracker_activity.date', $today)
        //         ->where('tracker.date', '=', 'tracker_activity.date')
        //         ->whereNull(['tracker.deleted_at','tracker_activity.deleted_at'])
        //         ->get(['tracker.*']);
    

        // dd($tracker);
       
        // $id = 'U2fc167382b22b0c70c0a2a16081a475e';
        $tracker_act = tracker_activity::where('user_id',  $id)
                                        // ->where('date', $today)
                                        ->whereNull('deleted_at')
                                        ->orderBy('date', 'DESC')
                                        ->get(); 

        $tracker_act_today = tracker_activity::where('user_id',  $id)
                                        ->where('date', $today)
                                        ->whereNull('deleted_at')
                                        ->get(); 

        $tracker = tracker::where('user_id',  $id)
                                        // ->where('date', $today)
                                        ->whereNull('deleted_at')
                                        ->orderBy('date', 'DESC')
                                        ->get(); 
        $tracker_today = tracker::where('user_id',  $id)
                                        ->where('date', $today)
                                        ->whereNull('deleted_at')
                                        ->get();                          


        // $posts = tracker_activity::all()->pluck('meal')->toArray();
        // dd($posts);
///////////////////////////////////////////////////
        $strunit = Array(" ","ทัพพี","ช้อน","ช้อนโต๊ะ","ลูก","ฟอง","ตัว","มล.","ชิ้น");
        $strmeal = Array(" ","เช้า","กลางวัน","เย็น","ว่างเช้า","ว่างบ่าย");
        $strmeal = Array(" ","breakfast","lunch","dinner","dessert_lu","dessert_din");
        // $unit = $strunit[$tkact->unit];
        // $meal = $strmeal[$tkact->meal];
        $breakfast = tracker::join('tracker_activity','tracker.id','=','tracker_activity.food_id')
                            ->where('tracker.user_id',  $id)
                            ->where('meal',1)
                            ->whereNull('tracker_activity.deleted_at')
                            ->select('tracker.date','tracker.time_breakfast as time','tracker.breakfast as food_name','tracker_activity.meal','tracker_activity.food_name as ingredient_name','tracker_activity.portion','tracker_activity.unit','tracker_activity.id')
                            // ->groupBy(['breakfast','food_name'])
                            ->orderBy('tracker.date', 'DESC')
                            ->get();
                            // ->groupBy(['date','meal','time','food_name'])
                            // ->toArray();    

        $lunch = tracker::join('tracker_activity','tracker.id','=','tracker_activity.food_id')
                            ->where('tracker.user_id',  $id)
                            ->where('meal',2)
                            ->whereNull('tracker_activity.deleted_at')
                            ->select('tracker.date','tracker.time_lunch as time','tracker.lunch as food_name','tracker_activity.meal','tracker_activity.food_name as ingredient_name','tracker_activity.portion','tracker_activity.unit','tracker_activity.id')
                            // ->groupBy(['lunch','food_name'])
                            ->orderBy('tracker.date', 'DESC')
                            ->get();
                            // ->groupBy(['date','meal','time','food_name'])
                            // ->toArray();   

        $dinner = tracker::join('tracker_activity','tracker.id','=','tracker_activity.food_id')
                            ->where('tracker.user_id',  $id)
                            ->where('meal',3)
                            ->whereNull('tracker_activity.deleted_at')
                            ->select('tracker.date','tracker.time_dinner as time','tracker.dinner as food_name','tracker_activity.meal','tracker_activity.food_name as ingredient_name','tracker_activity.portion','tracker_activity.unit','tracker_activity.id')
                            // ->groupBy(['dinner','food_name'])
                            ->orderBy('tracker.date', 'DESC')
                            ->get();
                            // ->groupBy(['date','meal','time','food_name'])
                            // ->toArray();          
        $dessert_lu = tracker::join('tracker_activity','tracker.id','=','tracker_activity.food_id')
                            ->where('tracker.user_id',  $id)
                            ->where('meal',4)
                            ->whereNull('tracker_activity.deleted_at')
                            ->select('tracker.date','tracker_activity.time as time','tracker.dessert_lu as food_name','tracker_activity.meal','tracker_activity.food_name as ingredient_name','tracker_activity.portion','tracker_activity.unit','tracker_activity.id')
                            // ->groupBy(['dessert_lu','food_name'])
                            ->orderBy('tracker.date', 'DESC')
                            ->get();
                            // ->groupBy(['date','meal','time','food_name'])
                            // ->toArray();     
                            
        $dessert_din = tracker::join('tracker_activity','tracker.id','=','tracker_activity.food_id')
                            ->where('tracker.user_id',  $id)
                            ->where('meal',5)
                            ->whereNull('tracker_activity.deleted_at')
                            ->select('tracker.date','tracker_activity.time as time','tracker.dessert_din as food_name','tracker_activity.meal','tracker_activity.food_name as ingredient_name','tracker_activity.portion','tracker_activity.unit','tracker_activity.id')
                            // ->groupBy(['dessert_din','food_name'])
                            ->orderBy('tracker.date', 'DESC')
                            ->get();
                            // ->groupBy(['date','meal','time','food_name'])
                            // ->toArray();       

            // $array = array_merge_recursive($breakfast, $lunch);
            // $array1 = array_merge_recursive($array, $dinner);
            // $array2 = array_merge_recursive($array1, $dessert_lu);
            // $array3 = array_merge_recursive($array2, $dessert_din);
  
            // $array  = $breakfast->merge($lunch);
            // $array1 = $array->merge($dinner);
            // $array2 = $array1->merge($dessert_lu);
            // $array3 = $array2->merge($dessert_din);
            // $array3 = $array3->sortByDesc(['date','time'],true);
            $array =  $breakfast->merge($dessert_lu);
            $array1 =  $array->merge($lunch);
            $array2 =  $array1->merge($dessert_din);
            $array3 =  $array2->merge($dinner);
            
            $array3 = $array3->sortByDesc(function($post) {
                return sprintf($post->date, $post->time);
            });
            
            // function sortFunction( $a, $b ) {
            //     return strtotime($a["date"]) - strtotime($b["date"]);
            // }
            // $array3 = usort($array3, "sortFunction");
            // $array3 =  var_dump($array3);

            // // Comparison function 
            // function date_compare($element1, $element2) { 
            //     $datetime1 = strtotime($element1['date'],); 
            //     $datetime2 = strtotime($element2['date']); 
            //     return $datetime1 - $datetime2; 
            // }  
            
            // // Sort the array  
            // $a = usort($array3, 'date_compare'); 
    
            // dd($a);
        

//================================================
 
     
//////////////////////////////////////////////////


        // $record_weight = (new SqlController)->get_weight_mom($id);
        return View::make("liff_diary",compact('id'))->with('tracker', $tracker)->with('tracker_today', $tracker_today)->with('tracker_act', $tracker_act)->with('array3', $array3);

    }
    public function savediary(Request $request)
    {
       
        $user_id = $request->input('user_id'); 
        $date = $request->input('date'); 
        $food = $request->input('mainfood_name');
        $meal = $request->input('meal'); 
        $time = $request->input('time');
       // dd($request->all());

        // $lunch = $request->input('lunch');
        // $dinner = $request->input('dinner');
        // $dessert_lu = $request->input('dessert_lu');
        // $dessert_din = $request->input('dessert_din');
        // $exercise = $request->input('exercise');
        // $vitamin = $request->input('vitamin');
        // $time_breakfast = $request->input('time_breakfast');
        // $time_lunch = $request->input('time_lunch');
        // $time_dinner = $request->input('time_dinner');

        $tracker_activity = tracker_activity::where('user_id', $user_id)
                            ->where('date', $date)
                            ->whereNull('deleted_at')
                            ->get(); 

        $tracker = tracker::where('user_id', $user_id)
                            ->where('date', $date)
                            ->whereNull('deleted_at')
                            ->get(); 

        // if ( $tracker_activity->isEmpty()) {
        //         $RecordOfPregnancy = tracker_activity::create(request(['user_id','date']));
        // }
        if ( $tracker->isEmpty()) {
            $RecordOfPregnancy = tracker::create(request(['user_id','date','food']));
        }else{                           
                            switch ($meal) {
                                case 1:
                                    $vitamin = 0;
                                    $RecordOfPregnancy = tracker::where('user_id', $user_id)
                                    ->where('date', $date)
                                    ->whereNull('deleted_at')
                                    ->update(['breakfast' =>$food, 'time_breakfast'=>$time]);
                                  break;
                                case 2:
                                    $vitamin =  1;
                                    $RecordOfPregnancy = tracker::where('user_id', $user_id)
                                    ->where('date', $date)
                                    ->whereNull('deleted_at')
                                    ->update(['lunch' =>$food, 'time_lunch'=> $time]);
                                  break;
                                case 3:
                                    $vitamin = 0;
                                    $RecordOfPregnancy = tracker::where('user_id', $user_id)
                                    ->where('date', $date)
                                    ->whereNull('deleted_at')
                                    ->update(['dinner' =>$food, 'time_dinner'=> $time]);
                                  break;
                                case 4:
                                    $vitamin =  1;
                                    $RecordOfPregnancy = tracker::where('user_id', $user_id)
                                    ->where('date', $date)
                                    ->whereNull('deleted_at')
                                    ->update(['dessert_lu' =>$food]);
                                default:
                                    $vitamin = 0;
                                    $RecordOfPregnancy = tracker::where('user_id', $user_id)
                                    ->where('date', $date)
                                    ->whereNull('deleted_at')
                                    ->update(['dessert_din' =>$food]);
                              }
        }

        $tracker_id = tracker::select('id')
                    ->where('user_id', $user_id)
                    ->where('date', $date)
                    ->whereNull('deleted_at')
                    ->first(); 

        // dd($tracker_id->id);

                    
        

// ==================================================================================

        // $tracker_activity = tracker_activity::create([
        //     'user_id' => $request->user_id,
        //     'date' => $request->date,
        //     'time' => $request->time,
        //     'meal' => $request->meal,
        //     //add if you want you add any more column
        // ]);
     
     




        // foreach ($request->title as $key => $value) {
        //     BookingDetails::create([
        //         'booking_id' => $booking_id,
        //         'title' => $request->title[$key],
        //         'fname' => $request->fname[$key],
        //         'lname' => $request->lname[$key],
        //         //other columns
        //     ]);
        // }







        // if ( !empty($request->input('breakfast'))) {
        //     $breakfast = $request->input('breakfast');
        //     $RecordOfPregnancy = tracker_activity::where('user_id', $user_id)
        //     ->where('date', $date)
        //     ->whereNull('deleted_at')
        //     ->update(['breakfast' =>$breakfast]);
        // }

        // if ( !empty($request->input('lunch'))){
        //     $lunch = $request->input('lunch');
        //     $RecordOfPregnancy = tracker_activity::where('user_id', $user_id)
        //     ->where('date', $date)
        //     ->whereNull('deleted_at')
        //     ->update(['lunch' =>$lunch]);
        // }


        // if ( !empty($request->input('dinner'))) {
        //     $dinner = $request->input('dinner');
        //     $RecordOfPregnancy = tracker_activity::where('user_id', $user_id)
        //     ->where('date', $date)
        //     ->whereNull('deleted_at')
        //     ->update(['dinner' =>$dinner]);
        // }
        // if ( !empty($request->input('dessert_lu'))) {
        //     $dessert_lu = $request->input('dessert_lu');
        //     $RecordOfPregnancy = tracker_activity::where('user_id', $user_id)
        //     ->where('date', $date)
        //     ->whereNull('deleted_at')
        //     ->update(['dessert_lu' =>$dessert_lu]);
        // }
        // if ( !empty($request->input('dessert_din'))) {
        //     $dessert_din = $request->input('dessert_din');
        //     $RecordOfPregnancy = tracker_activity::where('user_id', $user_id)
        //     ->where('date', $date)
        //     ->whereNull('deleted_at')
        //     ->update(['dessert_din' =>$dessert_din]);
        // }
        if ( !empty($request->input('exercise'))) {
            $exercise = $request->input('exercise');
            $RecordOfPregnancy = tracker::where('user_id', $user_id)
            ->where('date', $date)
            ->whereNull('deleted_at')
            ->update(['exercise' =>$exercise]);
        }

        if ( !empty($request->input('vitamin'))) {
            $vitamin = $request->input('vitamin');
            $RecordOfPregnancy = tracker::where('user_id', $user_id)
            ->where('date', $date)
            ->whereNull('deleted_at')
            ->update(['vitamin' =>$vitamin]);
        }else{
            $vitamin = $request->input('vitamin');
            $vit= tracker::where('user_id', $user_id)
            ->where('date', $date)
            ->whereNull('deleted_at')
            ->first();
            $vitamin =  $vit->vitamin;
            switch ($vitamin) {
                case 0:
                    $vitamin = 0;
                    $RecordOfPregnancy = tracker::where('user_id', $user_id)
                    ->where('date', $date)
                    ->whereNull('deleted_at')
                    ->update(['vitamin' =>$vitamin]);
                  break;
                case 1:
                    $vitamin =  1;
                    $RecordOfPregnancy = tracker::where('user_id', $user_id)
                    ->where('date', $date)
                    ->whereNull('deleted_at')
                    ->update(['vitamin' =>$vitamin]);
                  break;
                default:
                    $vitamin = 0;
                    $RecordOfPregnancy = tracker::where('user_id', $user_id)
                    ->where('date', $date)
                    ->whereNull('deleted_at')
                    ->update(['vitamin' =>$vitamin]);
              }

        }

     

        // if ( !empty($request->input('time_breakfast'))) {
        //     $time_breakfast = $request->input('time_breakfast');
        //     $RecordOfPregnancy = tracker_activity::where('user_id', $user_id)
        //     ->where('date', $date)
        //     ->whereNull('deleted_at')
        //     ->update(['time_breakfast' =>$time_breakfast]);
        // }

        // if ( !empty($request->input('time_lunch'))) {
        //     $time_lunch = $request->input('time_lunch');
        //     $RecordOfPregnancy = tracker_activity::where('user_id', $user_id)
        //     ->where('date', $date)
        //     ->whereNull('deleted_at')
        //     ->update(['time_lunch' =>$time_lunch]);
        // }

        // if ( !empty($request->input('time_dinner'))) {
        //     $time_dinner = $request->input('time_dinner');
        //     $RecordOfPregnancy = tracker_activity::where('user_id', $user_id)
        //     ->where('date', $date)
        //     ->whereNull('deleted_at')
        //     ->update(['time_dinner' =>$time_dinner]);
        // }

        // if ( !empty($request->input('time'))) {
        //     $time = $request->input('time');
        //     $Result = tracker_activity::where('user_id', $user_id)
        //     ->where('date', $date)
        //     ->whereNull('deleted_at')
        //     ->update(['time' =>$time]);
        // }
   
        
        // if ( !empty($request->input('meal'))) {
        //     $meal = $request->input('meal');
        //     $Result = tracker_activity::where('user_id', $user_id)
        //     ->where('date', $date)
        //     ->whereNull('deleted_at')
        //     ->update(['meal' =>$meal]);
        // }

        // if ( !empty($request->input('food_name'))) {
        //     $food_name = $request->input('food_name');
        //     $Result = tracker_activity::where('user_id', $user_id)
        //     ->where('date', $date)
        //     ->where('time', $time)
        //     ->whereNull('deleted_at')
        //     ->update(['food_name' =>$food_name]);
        // }
        
        // if ( !empty($request->input('portion'))) {
        //     $portion = $request->input('portion');
        //     $Result = tracker_activity::where('user_id', $user_id)
        //     ->where('date', $date)
        //     ->whereNull('deleted_at')
        //     ->update(['portion' =>$portion]);
        // }

        // if ( !empty($request->input('unit'))) {
        //     $unit = $request->input('unit');
        //     $Result = tracker_activity::where('user_id', $user_id)
        //     ->where('date', $date)
        //     ->whereNull('deleted_at')
        //     ->update(['unit' =>$unit]);
        // }

        // if ( !empty($request->input('calorie'))) {
        //     $calorie = $request->input('calorie');
        //     $Result = tracker_activity::where('user_id', $user_id)
        //     ->where('date', $date)
        //     ->whereNull('deleted_at')
        //     ->update(['calorie' =>$calorie]);
        // }
    
        $request->validate([
            'moreFields.*.food_name' => 'required',
            'moreFields.*.portion' => 'required'
        ]);
     
        foreach ($request->moreFields as $key => $value) {
            // 
            tracker_activity::create(
                [
                'user_id' => $request->user_id,
                'date' => $request->date,
                'time' => $request->time,
                'meal' => $request->meal,
                'food_name' => $value['food_name'],
                'portion' => $value['portion'],
                'unit' => $value['unit'],
                'food_id' => $tracker_id->id
                // $value
                ]);
        
               
        }


          return redirect()->back()->with('message', 'บันทึกเรียบร้อยค่ะ' );   

    }
    public function remove_diary($id){
        $users_register = tracker_activity::where('id', $id)->delete();
        $users_register = tracker::where('id', $id)->delete();
                     
        return redirect()->back();

    }

    public function submitcal(Request $request){

        // $this->validate(request(), [
        //     'date' => 'required'
        // ]);
        $id = $request->input('id'); 
        $calorie = $request->input('calorie'); 
    


        $birth_date = tracker_activity::where('id',$id )
                        ->whereNull('deleted_at')
                        ->update(['calorie'=>$calorie]);
        return redirect()->back();
    }

      public function generateQRCode( $id )
    {
        // สร้าง URL ให้ผู้ป่วยใช้เชื่อมโยง
       $liffBase = "https://liff.line.me/1656991660-K8bDpjZ9"; // เปลี่ยนเป็น LIFF ของคุณ
       $liffUrl = $liffBase . "?user_id=" . $id;
      
        // สร้าง QR Code เป็น base64 image
        $qrBinary = QrCode::format('png')->size(300)->generate($liffUrl);
        $qrBase64 = 'data:image/png;base64,' . base64_encode($qrBinary);
        return   $qrBase64;
    }
    // public function savediary_vitexc(Request $request)
    // {

    //     $user_id = $request->input('user_id'); 
    //     $date = $request->input('date'); 

    //     $tracker = tracker_activity::where('user_id', $user_id)
    //                         ->where('date', $date)
    //                         ->whereNull('deleted_at')
    //                         ->get(); 

    //     if ( $tracker->isEmpty()) {
    //             $RecordOfPregnancy = tracker::create(request(['user_id','date']));
    //             $RecordOfPregnancy = tracker::create(request(['user_id','date']));
    //     }
    //     if ( !empty($request->input('exercise'))) {
    //         $exercise = $request->input('exercise');
    //         $RecordOfPregnancy = tracker::where('user_id', $user_id)
    //         ->where('date', $date)
    //         ->whereNull('deleted_at')
    //         ->update(['exercise' =>$exercise]);
    //     }

    //     if ( !empty($request->input('vitamin'))) {
    //         $vitamin = 0;
    //         $vitamin = $request->input('vitamin');
    //         $RecordOfPregnancy = tracker::where('user_id', $user_id)
    //         ->where('date', $date)
    //         ->whereNull('deleted_at')
    //         ->update(['vitamin' =>$vitamin]);
    //     }else{
    //         $vitamin = $request->input('vitamin');
    //         $vit= tracker::where('user_id', $user_id)
    //         ->where('date', $date)
    //         ->whereNull('deleted_at')
    //         ->first();
    //         $vitamin =  $vit->vitamin;
    //         switch ($vitamin) {
    //             case 0:
    //                 $vitamin = 0;
    //                 $RecordOfPregnancy = tracker::where('user_id', $user_id)
    //                 ->where('date', $date)
    //                 ->whereNull('deleted_at')
    //                 ->update(['vitamin' =>$vitamin]);
    //               break;
    //             case 1:
    //                 $vitamin =  1;
    //                 $RecordOfPregnancy = tracker::where('user_id', $user_id)
    //                 ->where('date', $date)
    //                 ->whereNull('deleted_at')
    //                 ->update(['vitamin' =>$vitamin]);
    //               break;
    //             default:
    //                 $vitamin = 0;
    //                 $RecordOfPregnancy = tracker::where('user_id', $user_id)
    //                 ->where('date', $date)
    //                 ->whereNull('deleted_at')
    //                 ->update(['vitamin' =>$vitamin]);
    //           }
    //     }

    //       return redirect()->back()->with('message', 'บันทึกเรียบร้อยค่ะ' );   

    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
}
