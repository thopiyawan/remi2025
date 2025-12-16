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

use App\Http\Controllers\checkmessageController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\SqlController;
use App\Http\Controllers\CalController;

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

use LINE\LINEBot\Constant\Flex\ComponentIconSize;
use LINE\LINEBot\Constant\Flex\ComponentImageSize;
use LINE\LINEBot\Constant\Flex\ComponentImageAspectRatio;
use LINE\LINEBot\Constant\Flex\ComponentImageAspectMode;
use LINE\LINEBot\Constant\Flex\ComponentFontSize;
use LINE\LINEBot\Constant\Flex\ComponentFontWeight;
use LINE\LINEBot\Constant\Flex\ComponentMargin;
use LINE\LINEBot\Constant\Flex\ComponentSpacing;
use LINE\LINEBot\Constant\Flex\ComponentButtonStyle;
use LINE\LINEBot\Constant\Flex\ComponentButtonHeight;
use LINE\LINEBot\Constant\Flex\ComponentSpaceSize;
use LINE\LINEBot\Constant\Flex\ComponentGravity;
use LINE\LINEBot\Constant\Flex\BubleContainerSize;
use LINE\LINEBot\MessageBuilder\FlexMessageBuilder;
use LINE\LINEBot\MessageBuilder\Flex\BubbleStylesBuilder;
use LINE\LINEBot\MessageBuilder\Flex\BlockStyleBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder\BubbleContainerBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder\CarouselContainerBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\BoxComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\ButtonComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\IconComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\ImageComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\SpacerComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\FillerComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\SeparatorComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\TextComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\SpanComponentBuilder;

use LINE\LINEBot\QuickReplyBuilder;
use LINE\LINEBot\QuickReplyBuilder\QuickReplyMessageBuilder;
use LINE\LINEBot\QuickReplyBuilder\ButtonBuilder\QuickReplyButtonBuilder;
use LINE\LINEBot\TemplateActionBuilder\CameraRollTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\CameraTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\LocationTemplateActionBuilder;

// define('LINE_MESSAGE_CHANNEL_SECRET','f571a88a60d19bb28d06383cdd7af631');
// define('LINE_MESSAGE_ACCESS_TOKEN','omL/jl2l8TFJaYFsOI2FaZipCYhBl6fnCf3da/PEvFG1e5ADvMJaILasgLY7jhcwrR2qOr2ClpTLmveDOrTBuHNPAIz2fzbNMGr7Wwrvkz08+ZQKyQ3lUfI5RK/NVozfMhLLAgcUPY7m4UtwVwqQKwdB04t89/1O/w1cDnyilFU=');
// define('LINE_MESSAGE_CHANNEL_SECRET','a06f8f521aabe202f1ce7427b4e52d1b');
// define('LINE_MESSAGE_ACCESS_TOKEN','UWrfpYzUUCCy44R4SFvqITsdWn/PeqFuvzLwey51hlRA1+AX/jSyCVUY7V2bPTkuoaDzmp1AY5CfsgFTIinxzxIYViz+chHSXWsxZdQb5AyZu7U67A9f18NQKE/HfGNrZZrwNxWNUwVJf2AszEsCvgdB04t89/1O/w1cDnyilFU=');

class ReplyMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function replymessage2($replyToken,$userMessage1,$userMessage2)
    {
          $httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
          $bot = new LINEBot($httpClient, array('channelSecret' => LINE_MESSAGE_CHANNEL_SECRET));


                      $textMessage1 = new TextMessageBuilder($userMessage1);
                      $textMessage2 = new TextMessageBuilder($userMessage2);

                      $multiMessage = new MultiMessageBuilder;
                      $multiMessage->add($textMessage1);
                      $multiMessage->add($textMessage2);
                      $textMessageBuilder = $multiMessage; 
     
          
             
                $response = $bot->replyMessage($replyToken,$textMessageBuilder); 


    }




 public function replymessage7($replyToken,$user)
    {

      // $httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
      //     $bot = new LINEBot($httpClient, array('channelSecret' => LINE_MESSAGE_CHANNEL_SECRET));
      // $httpClient = new CurlHTTPClient('UWrfpYzUUCCy44R4SFvqITsdWn/PeqFuvzLwey51hlRA1+AX/jSyCVUY7V2bPTkuoaDzmp1AY5CfsgFTIinxzxIYViz+chHSXWsxZdQb5AyZu7U67A9f18NQKE/HfGNrZZrwNxWNUwVJf2AszEsCvgdB04t89/1O/w1cDnyilFU=');
      // $bot = new LINEBot($httpClient, array('channelSecret' => 'a06f8f521aabe202f1ce7427b4e52d1b'));

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
                  $cal  = (new CalController)->cal_calculator($user_age,$active_lifestyle,$user_Pre_weight,$preg_week); 
                  $meal_planing = (new SqlController)->meal_planing($cal);
                  $cal = json_encode($cal); 
                  $protein =  $user_Pre_weight+25;  
                  $protein = json_encode($protein); 
                  // $meal_planingf = json_encode($meal_planing->fats);
                  
                

                //   $kcal = $cal." กิโลแคลอรี่ต่อวัน";
                                
                //   $textReplyMessage = new BubbleContainerBuilder(
                //     "ltr",  // กำหนด NULL หรือ "ltr" หรือ "rtl"
                //     new BoxComponentBuilder(
                //         "vertical",
                //         array(
                //             new TextComponentBuilder("พลังงานและปริมาณโปรตีนที่ต้องการ",NULL,NULL,"md")
                //         )
                //     ),
                //     new ImageComponentBuilder(
                //         "https://service.foodieat.in.th/remi/food/food.png",NULL,NULL,NULL,NULL,"full","20:13","cover"),
                //     new BoxComponentBuilder(
                //         "vertical",
                //         array(
                //             new TextComponentBuilder("สามารถจัดเป็นจานอาหารสุขภาพ",NULL,NULL,"md"),
                //             new TextComponentBuilder("ง่ายๆ แบบรูปภาพนี้",NULL,NULL,"md")
                //         )
                //     ),
                //     new BoxComponentBuilder(
                //         "vertical",
                //         array(
                //             new TextComponentBuilder($user),
                //             new TextComponentBuilder("This is Footer")
                //         )
                //     ),
                //     new BubbleStylesBuilder( // style ทั้งหมดของ bubble
                //         new BlockStyleBuilder("#FFC90E"),  // style สำหรับ header block
                //         new BlockStyleBuilder("#EFE4B0"), // style สำหรับ hero block
                //         new BlockStyleBuilder("#B5E61D"), // style สำหรับ body block
                //         new BlockStyleBuilder("#FFF200") // style สำหรับ footer block
                //     )
                // );

                //   $textReplyMessage = new CarouselContainerBuilder(
                //     array(
                //         new BubbleContainerBuilder(
                //             "ltr",  // กำหนด NULL หรือ "ltr" หรือ "rtl"
                //             NULL,NULL,
                //             new BoxComponentBuilder(
                //                 "vertical",
                //                 array(
                //                     new TextComponentBuilder("พลังงานและปริมาณโปรตีนที่ต้องการ",NULL,NULL,"xxl"),
                //                     new TextComponentBuilder("พลังงานที่คุณแม่ต้องการในแต่ละวัน คือ",NULL,NULL,"xl")
                //                 )
                //             ),
                //             new BoxComponentBuilder(
                //               "vertical",
                //               array(
                //                   new TextComponentBuilder($cal,NULL,NULL,"xl"),
                //                   new TextComponentBuilder("กิโลแคลอรี่ต่อวัน",NULL,NULL,"lg")
                //               )
                //           )
                //         ), // end bubble 1
                //         new BubbleContainerBuilder(
                //             "ltr",  // กำหนด NULL หรือ "ltr" หรือ "rtl"
                //             NULL,NULL,
                //             new BoxComponentBuilder(
                //                 "horizontal",
                //                 array(
                //                     new TextComponentBuilder("ข้อมูลโภชนาการ",NULL,NULL,NULL,NULL,NULL,true)
                //                 )
                //             ),
                //             new BoxComponentBuilder(
                //                 "horizontal",
                //                 array(
                //                     new ButtonComponentBuilder(
                //                         new UriTemplateActionBuilder("GO","http://niik.in"),
                //                         NULL,NULL,NULL,"primary"
                //                     )
                //                 )
                //             )
                //         ), // end bubble 2  
                //         new BubbleContainerBuilder(
                //           "ltr",  // กำหนด NULL หรือ "ltr" หรือ "rtl"
                //           NULL,NULL,
                //           new BoxComponentBuilder(
                //               "horizontal",
                //               array(
                //                   new TextComponentBuilder("สามารถจัดเป็นจานอาหารสุขภาพง่ายๆแบบรูปภาพนี้",NULL,NULL,NULL,NULL,NULL,true)
                //               )
                //           ),
                //           new BoxComponentBuilder(
                //               "horizontal",
                //               array(
                //                   new ButtonComponentBuilder(
                //                       new UriTemplateActionBuilder("GO","http://niik.in"),
                //                       NULL,NULL,NULL,"primary"
                //                   )
                //               )
                //           )
                //       ) // end bubble 3         
                //     )
                // );
                // $textMessageBuilder = new FlexMessageBuilder("This is a Flex Message",$textReplyMessage);
                // $response = $bot->replyMessage($replyToken,$textMessageBuilder); 
                  $textMessageBuilder = 
                  [ 
                    "type" => "flex",
                    "altText" => "this is a flex message",
                    "contents" =>array (
                    'type' => 'carousel',
                    'contents' => 
                   array (
                      0 => 
                      array (
                        'type' => 'bubble',
                        'styles' => 
                        array (
                          'footer' => 
                          array (
                            'separator' => true,
                          ),
                        ),
                        'body' => 
                        array (
                          'type' => 'box',
                          'layout' => 'vertical',
                          'contents' => 
                          array (
                            0 => 
                            array (
                              'type' => 'text',
                              'text' => 'พลังงานและปริมาณโปรตีนที่ต้องการ',
                              'weight' => 'bold',
                              'color' => '#1DB446',
                              'size' => 'md',
                              'wrap' => true,
                            ),
                            1 => 
                            array (
                              'type' => 'separator',
                              'margin' => 'xxl',
                            ),
                            2 => 
                            array (
                              'type' => 'box',
                              'layout' => 'vertical',
                              'margin' => 'xxl',
                              'spacing' => 'sm',
                              'contents' => 
                              array (
                                0 => 
                                array (
                                  'type' => 'box',
                                  'layout' => 'horizontal',
                                  'contents' => 
                                  array (
                                    0 => 
                                    array (
                                      'type' => 'text',
                                      'text' => 'พลังงานที่ต้องการในแต่ละวันคือ',
                                      'size' => 'sm',
                                      'color' => '#555555',
                                      'weight' => 'bold',
                                      'flex' => 0,
                                    ),
                                  ),
                                ),
                                1 => 
                                array (
                                  'type' => 'box',
                                  'layout' => 'horizontal',
                                  'contents' => 
                                  array (
                                    0 => 
                                    array (
                                      'type' => 'text',
                                      'text' => $cal,
                                      'size' => 'md',
                                      'color' => '#1DB446',
                                      'flex' => 0,
                                    ),
                                    1 => 
                                    array (
                                      'type' => 'text',
                                      'text' => 'กิโลแคลอรี่ต่อวัน',
                                      'size' => 'sm',
                                      'color' => '#555555',
                                      'align' => 'end',
                                    ),
                                  ),
                                ),
                                2 => 
                                array (
                                  'type' => 'separator',
                                  'margin' => 'xxl',
                                ),
                                3 => 
                                array (
                                  'type' => 'box',
                                  'layout' => 'horizontal',
                                  'margin' => 'xl',
                                  'contents' => 
                                  array (
                                    0 => 
                                    array (
                                      'type' => 'text',
                                      'text' => 'ปริมาณโปรตีนที่ต้องการในแต่ละวันคือ',
                                      'size' => 'sm',
                                      'color' => '#555555',
                                      'flex' => 0,
                                      'weight' => 'bold',
                                      'wrap' => true,
                                    ),
                                  ),
                                ),
                                4 => 
                                array (
                                  'type' => 'box',
                                  'layout' => 'horizontal',
                                  'contents' => 
                                  array (
                                    0 => 
                                    array (
                                      'type' => 'text',
                                      'text' =>  $protein,
                                      'size' => 'md',
                                      'color' => '#1DB446',
                                      'flex' => 0,
                                    ),
                                    1 => 
                                    array (
                                      'type' => 'text',
                                      'text' => 'กรัมต่อวัน',
                                      'size' => 'sm',
                                      'color' => '#555555',
                                      'align' => 'end',
                                    ),
                                  ),
                                ),
                                5 => 
                                array (
                                  'type' => 'separator',
                                  'margin' => 'xxl',
                                ),
                              ),
                            ),
                            // 3 => 
                            // array (
                            //   'type' => 'box',
                            //   'layout' => 'vertical',
                            //   'margin' => 'xxl',
                            //   'contents' => 
                            //   array (
                            //     0 => 
                            //     array (
                            //       'type' => 'spacer',
                            //     ),
                            //     1 => 
                            //     array (
                            //       'type' => 'image',
                            //       'url' => 'https://remi.softbot.ai/food/food.png',
                            //       'aspectMode' => 'cover',
                            //       'action' => 
                            //       array (
                            //         'type' => 'uri',
                            //         'uri' => 'https://remi.softbot.ai/food/food.png',
                            //       ),
                            //       'size' => 'xxl',
                            //     ),
                            //     2 => 
                            //     array (
                            //       'type' => 'text',
                            //       'text' => 'สามารถจัดเป็นจานอาหารสุขภาพง่ายๆ แบบรูปภาพนี้',
                            //       'color' => '#aaaaaa',
                            //       'wrap' => true,
                            //       'margin' => 'xxl',
                            //       'size' => 'xs',
                            //       'align' => 'center',
                            //     ),
                            //   ),
                            // ),
                          ),
                        ),
                      ),
                      1 => 
                      array (
                        'type' => 'bubble',
                        'body' => 
                        array (
                          'type' => 'box',
                          'layout' => 'vertical',
                          'contents' => 
                          array (
                            0 => 
                            array (
                              'type' => 'box',
                              'layout' => 'vertical',
                              'margin' => 'xxl',
                              'spacing' => 'sm',
                              'contents' => 
                              array (
                                     0 => 
                                array (
                                  'type' => 'box',
                                  'layout' => 'horizontal',
                                  'contents' => 
                                  array (
                                    0 => 
                                    array (
                                      'type' => 'text',
                                      'text' => 'ข้อมูลโภชนาการ',
                                      'weight' => 'bold',
                                      'color' => '#1DB446',
                                      'size' => 'md',
                                    ),
                                  ),
                                ),
                                1 => 
                                array (
                                  'type' => 'separator',
                                  'margin' => 'xxl',
                                ),
                                2 => 
                                array (
                                  'type' => 'box',
                                  'layout' => 'horizontal',
                                  'margin' => 'xl',
                                  'contents' => 
                                  array (
                                    0 => 
                                    array (
                                      'type' => 'text',
                                      'text' => 'มื้ออาหารหลัก 3 มื้อต่อวัน',
                                      'size' => 'sm',
                                      'color' => '#555555',
                                      'align' => 'center',
                                      'weight' => 'bold',
                                      'flex' => 0,
                                    ),
                                  ),
                                ),
                                3 => 
                                array (
                                  'type' => 'box',
                                  'layout' => 'horizontal',
                                  'contents' => 
                                  array (
                                    0 => 
                                    array (
                                      'type' => 'text',
                                      'text' => 'ข้าว',
                                      'size' => 'sm',
                                      'color' => '#555555',
                                    ),
                                    1 => 
                                    array (
                                      'type' => 'text',
                                      'text' => json_encode($meal_planing->starches),
                                      'size' => 'sm',
                                      'color' => '#1DB446',
                                      'align' => 'center',
                                    ),
                                    2 => 
                                    array (
                                      'type' => 'text',
                                      'text' => 'ทัพพีต่อวัน',
                                      'size' => 'sm',
                                      'color' => '#555555',
                                      'align' => 'end',
                                    ),
                                  ),
                                ),
                                4 => 
                                array (
                                  'type' => 'box',
                                  'layout' => 'horizontal',
                                  'contents' => 
                                  array (
                                    0 => 
                                    array (
                                      'type' => 'text',
                                      'text' => 'ผัก',
                                      'size' => 'sm',
                                      'color' => '#555555',
                                    ),
                                    1 => 
                                    array (
                                      'type' => 'text',
                                      'text' =>  json_encode($meal_planing->vegetables),
                                      'size' => 'sm',
                                      'color' => '#1DB446',
                                      'align' => 'center',
                                    ),
                                    2 => 
                                    array (
                                      'type' => 'text',
                                      'text' => 'ทัพพีต่อวัน',
                                      'size' => 'sm',
                                      'color' => '#555555',
                                      'align' => 'end',
                                    ),
                                  ),
                                ),
                                5 => 
                                array (
                                  'type' => 'box',
                                  'layout' => 'horizontal',
                                  'contents' => 
                                  array (
                                    0 => 
                                    array (
                                      'type' => 'text',
                                      'text' => 'เนื้อสัตว์',
                                      'size' => 'sm',
                                      'color' => '#555555',
                                    ),
                                    1 => 
                                    array (
                                      'type' => 'text',
                                      'text' =>  json_encode($meal_planing->meats),
                                      'size' => 'sm',
                                      'color' => '#1DB446',
                                      'align' => 'center',
                                    ),
                                    2 => 
                                    array (
                                      'type' => 'text',
                                      'text' => 'ช้อนโต๊ะต่อวัน',
                                      'size' => 'sm',
                                      'color' => '#555555',
                                      'align' => 'end',
                                    ),
                                  ),
                                ),
                                6 => 
                                array (
                                  'type' => 'box',
                                  'layout' => 'horizontal',
                                  'contents' => 
                                  array (
                                    0 => 
                                    array (
                                      'type' => 'text',
                                      'text' => 'ไขมัน',
                                      'size' => 'sm',
                                      'color' => '#555555',
                                    ),
                                    1 => 
                                    array (
                                      'type' => 'text',
                                      'text' => json_encode($meal_planing->fats),
                                      'size' => 'sm',
                                      'color' => '#1DB446',
                                      'align' => 'center',
                                    ),
                                    2 => 
                                    array (
                                      'type' => 'text',
                                      'text' => 'ช้อนโต๊ะต่อวัน',
                                      'size' => 'sm',
                                      'color' => '#555555',
                                      'align' => 'end',
                                    ),
                                  ),
                                ),
                                7 => 
                                array (
                                  'type' => 'separator',
                                  'margin' => 'xxl',
                                ),
                                8 => 
                                array (
                                  'type' => 'box',
                                  'layout' => 'horizontal',
                                  'margin' => 'xxl',
                                  'contents' => 
                                  array (
                                    0 => 
                                    array (
                                      'type' => 'text',
                                      'text' => 'มื้อว่าง 2 มื้อต่อวัน',
                                      'size' => 'sm',
                                      'color' => '#555555',
                                      'weight' => 'bold',
                                      'flex' => 0,
                                    ),
                                  ),
                                ),
                                9 => 
                                array (
                                  'type' => 'box',
                                  'layout' => 'horizontal',
                                  'contents' => 
                                  array (
                                    0 => 
                                    array (
                                      'type' => 'text',
                                      'text' => 'ผลไม้',
                                      'size' => 'sm',
                                      'color' => '#555555',
                                    ),
                                    1 => 
                                    array (
                                      'type' => 'text',
                                      'text' => json_encode($meal_planing->fruits),
                                      'size' => 'sm',
                                      'color' => '#1DB446',
                                      'align' => 'center',
                                    ),
                                    2 => 
                                    array (
                                      'type' => 'text',
                                      'text' => 'ส่วนต่อวัน',
                                      'size' => 'sm',
                                      'color' => '#555555',
                                      'align' => 'end',
                                    ),
                                  ),
                                ),
                                10 => 
                                array (
                                  'type' => 'box',
                                  'layout' => 'horizontal',
                                  'contents' => 
                                  array (
                                    0 => 
                                    array (
                                      'type' => 'text',
                                      'text' => 'นมไขมันต่ำ',
                                      'size' => 'sm',
                                      'color' => '#555555',
                                    ),
                                    1 => 
                                    array (
                                      'type' => 'text',
                                      'text' => json_encode($meal_planing->lf_milk),
                                      'size' => 'sm',
                                      'color' => '#1DB446',
                                      'align' => 'center',
                                    ),
                                    2 => 
                                    array (
                                      'type' => 'text',
                                      'text' => 'แก้วต่อวัน',
                                      'size' => 'sm',
                                      'color' => '#555555',
                                      'align' => 'end',
                                    ),
                                  ),
                                ),
                                11 => 
                                array (
                                  'type' => 'box',
                                  'layout' => 'horizontal',
                                  'contents' => 
                                  array (
                                    0 => 
                                    array (
                                      'type' => 'text',
                                      'text' => 'อาหารว่าง',
                                      'size' => 'sm',
                                      'color' => '#555555',
                                    ),
                                    1 => 
                                    array (
                                      'type' => 'text',
                                      'text' => json_encode($meal_planing->snack),
                                      'size' => 'sm',
                                      'color' => '#1DB446',
                                      'align' => 'center',
                                    ),
                                    2 => 
                                    array (
                                      'type' => 'text',
                                      'text' => 'มื้อต่อวัน',
                                      'size' => 'sm',
                                      'color' => '#555555',
                                      'align' => 'end',
                                    ),
                                  ),
                                ),
                              ),
                            ),
                            1 => 
                            array (
                              'type' => 'box',
                              'layout' => 'vertical',
                              'margin' => 'md',
                              'contents' => 
                              array (
                                0 => 
                                array (
                                  'type' => 'spacer',
                                ),
                              ),
                            ),
                          ),
                        ),
                      ),
                      2 => 
                      array (
                        'type' => 'bubble',
                        'styles' => 
                        array (
                          'footer' => 
                          array (
                            'separator' => true,
                          ),
                        ),
                        'body' => 
                        array (
                          'type' => 'box',
                          'layout' => 'vertical',
                          'contents' => 
                          array (
                            0 => 
                            array (
                              'type' => 'text',
                              'text' => 'สามารถจัดเป็นจานอาหารสุขภาพง่ายๆ แบบรูปภาพนี้',
                              'weight' => 'bold',
                              'color' => '#1DB446',
                              'size' => 'md',
                              'wrap' => true,
                            ),
                            1 => 
                            array (
                              'type' => 'separator',
                              'margin' => 'xxl',
                            ),
                            2 => 
                            array (
                              'type' => 'box',
                              'layout' => 'vertical',
                              'margin' => 'xxl',
                              'contents' => 
                              array (
                                0 => 
                                array (
                                  'type' => 'spacer',
                                ),
                                1 => 
                                array (
                                  'type' => 'image',
                                  'url' => 'https://service.foodieat.in.th/remi/food/food.png',
                                  'aspectMode' => 'cover',
                                  'action' => 
                                  array (
                                    'type' => 'uri',
                                    'uri' => 'https://service.foodieat.in.th/remi/food/food.png',
                                  ),
                                  'size' => 'xxl',
                                ),
                                // 2 => 
                                // array (
                                //   'type' => 'text',
                                //   'text' => 'สามารถจัดเป็นจานอาหารสุขภาพง่ายๆ แบบรูปภาพนี้',
                                //   'color' => '#aaaaaa',
                                //   'wrap' => true,
                                //   'margin' => 'xxl',
                                //   'size' => 'xs',
                                //   'align' => 'center',
                                // ),
                              ),
                            ),
                          ),
                        ),
                      ),
                    ),
                  )];


  //  $url = 'https://api.line.me/v2/bot/message/reply';
  $url = 'https://api.line.me/v2/bot/message/push';
   $data = [
    'to' => $user,
    'messages' => [$textMessageBuilder],
   ];
   $access_token = 'UWrfpYzUUCCy44R4SFvqITsdWn/PeqFuvzLwey51hlRA1+AX/jSyCVUY7V2bPTkuoaDzmp1AY5CfsgFTIinxzxIYViz+chHSXWsxZdQb5AyZu7U67A9f18NQKE/HfGNrZZrwNxWNUwVJf2AszEsCvgdB04t89/1O/w1cDnyilFU=';
   
   $post = json_encode($data);
   $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
   $ch = curl_init($url);
   curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  //  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
   $result = curl_exec($ch);
   curl_close($ch);
   echo $result . "\r\n";
}

    public function replymessage_result($replyToken,$preg_week,$bmi,$cal,$weight_criteria,$text,$user){

          //  $httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
          //  $bot = new LINEBot($httpClient, array('channelSecret' => LINE_MESSAGE_CHANNEL_SECRET));
          $httpClient = new CurlHTTPClient('UWrfpYzUUCCy44R4SFvqITsdWn/PeqFuvzLwey51hlRA1+AX/jSyCVUY7V2bPTkuoaDzmp1AY5CfsgFTIinxzxIYViz+chHSXWsxZdQb5AyZu7U67A9f18NQKE/HfGNrZZrwNxWNUwVJf2AszEsCvgdB04t89/1O/w1cDnyilFU=');
          $bot = new LINEBot($httpClient, array('channelSecret' => 'a06f8f521aabe202f1ce7427b4e52d1b'));

           $ws = (new SqlController)->weight_status_line($user);  
           $ws = $ws->weight_status;
                    if ($weight_criteria =='น้ำหนักน้อย') {
                      $result='1';
                    } elseif ($weight_criteria =='น้ำหนักปกติ') {
                      $result='2';
                    } elseif ($weight_criteria == 'น้ำหนักเกิน') {
                      $result='3';
                    } elseif ($weight_criteria =='อ้วน') {
                      $result='4';
                    }
          
                if($ws == '1'){
                  $w = '(BMI:'.$weight_criteria.')'."\n".' คุณแม่มีอัตราการเพิ่มน้ำหนัก ปกติตามเกณฑ์';
                }elseif($ws == '2'){
                  $w = '(BMI:'.$weight_criteria.')'."\n".' คุณแม่มีอัตราการเพิ่มน้ำหนัก น้อยกว่าเกณฑ์';
                }elseif($ws == '3'){  
                  $w = '(BMI:'.$weight_criteria.')'."\n".' คุณแม่มีอัตราการเพิ่มน้ำหนัก เกินกว่าเกณฑ์';
                }elseif($ws == '4'){  
                  $w = '(BMI:'.$weight_criteria.')'."\n".'คุณแม่มีภาวะแทรกซ้อน';
                }else{
                   $w ='คุณแม่มีค่า BMI อยู่ในเกณฑ์ '.$weight_criteria ;
                }
           
                   $actionBuilder1 = array(
                            // new MessageTemplateActionBuilder(
                            //     'กราฟน้ำหนัก', // ข้อความแสดงในปุ่ม
                            //     'กราฟน้ำหนัก'
                            // ),
                            new UriTemplateActionBuilder(
                                          'กราฟน้ำหนัก', // ข้อความแสดงในปุ่ม
                                          'https://service.foodieat.in.th/remi/graph/'.$user
                                          ),
                            new MessageTemplateActionBuilder(
                                'ทารกในครรภ์',// ข้อความแสดงในปุ่ม
                                'ทารกในครรภ์' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),
                        );
                   $actionBuilder2 = array(
                            new MessageTemplateActionBuilder(
                                'น้ำหนักตัวที่เหมาะสม',// ข้อความแสดงในปุ่ม
                                'น้ำหนักตัวที่เหมาะสม' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),
                            new MessageTemplateActionBuilder(
                                'ข้อมูลโภชนาการ',// ข้อความแสดงในปุ่ม
                                'ข้อมูลโภชนาการ' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),
                        );
                        $textMessageBuilder = new TemplateMessageBuilder('Carousel',
                            new CarouselTemplateBuilder(
                                array(
                                    new CarouselColumnTemplateBuilder(
                                        'ขณะนี้คุณมีอายุครรภ์'.$preg_week.'สัปดาห์',
                                         $w ,
                                        'https://service.foodieat.in.th/remi/week/'.$preg_week.'.jpg',
                                        $actionBuilder1
                                    ),
                                    new CarouselColumnTemplateBuilder(
                                        'จำนวนแคลอรี่ที่คุณต้องการต่อวันคือ '.$cal,
                                        'รายละเอียดการรับประทานอาหารสามารถกดปุ่มด้านล่างได้เลยค่ะ',
                                        'https://service.foodieat.in.th/remi/food/1_'.$result.'.jpg',
                                        $actionBuilder2
                                    ),                                        
                                )
                            )
                        );
              $response = $bot->replyMessage($replyToken,$textMessageBuilder);

    }
     public function replymessage($replyToken,$userMessage,$case,$user)
    {
            // $httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
            // $bot = new LINEBot($httpClient, array('channelSecret' => LINE_MESSAGE_CHANNEL_SECRET));
            $httpClient = new CurlHTTPClient('UWrfpYzUUCCy44R4SFvqITsdWn/PeqFuvzLwey51hlRA1+AX/jSyCVUY7V2bPTkuoaDzmp1AY5CfsgFTIinxzxIYViz+chHSXWsxZdQb5AyZu7U67A9f18NQKE/HfGNrZZrwNxWNUwVJf2AszEsCvgdB04t89/1O/w1cDnyilFU=');
            $bot = new LINEBot($httpClient, array('channelSecret' => 'a06f8f521aabe202f1ce7427b4e52d1b'));

            switch($case) {
     
                 case 1 : 
                        $textMessageBuilder = new TextMessageBuilder($userMessage);
                    break;
                 case 2 : 
                        $actionBuilder = array(
                                          new MessageTemplateActionBuilder(
                                          'ครั้งสุดท้ายที่มีประจำเดือน',
                                          'ครั้งสุดท้ายที่มีประจำเดือน' 
                                          ),
                                           new MessageTemplateActionBuilder(
                                          'กำหนดการคลอด',
                                          'กำหนดการคลอด' 
                                          ) 
                                         );

                        $imageUrl = NULL;
                        $textMessageBuilder = new TemplateMessageBuilder('ขอทราบอายุครรภ์ของคุณแม่หน่อยค่ะ',
                        new ButtonTemplateBuilder(
                              $userMessage, 
                              'กรุณาเลือกตอบข้อใดข้อหนึ่งเพื่อให้ทางเราคำนวณอายุครรภ์ค่ะ', 
                               $imageUrl, 
                               $actionBuilder  
                           )
                        );              
                    break;
                 case 3 : 
                         $textMessageBuilder = new TemplateMessageBuilder('อายุครรภ์ของคุณแม่', new ConfirmTemplateBuilder( $userMessage ,
                                array(
                                    new MessageTemplateActionBuilder(
                                        'ใช่',
                                        'อายุครรภ์ถูกต้อง'
                                    ),
                                    new MessageTemplateActionBuilder(
                                        'ไม่ใช่',
                                        'ไม่ถูกต้อง'
                                    )
                                )
                        )
                    ); 
                    break;

                 case 4 : 

                  $textReplyMessage = $userMessage;
                  $textMessage1 = new TextMessageBuilder($textReplyMessage);
                  $textReplyMessage =   "รายละเอียดของระดับ". "\n".
                                        "เบา -  วิถีชีวิตทั่วไป ไม่มีการออกกำลังกาย หรือมีการออกกำลังกายน้อย". "\n".
                                        "ปานกลาง - วิถีชีวิตกระฉับกระเฉง หรือ มีการออกกำลังกายสม่ำเสมอ". "\n".
                                        "หนัก - วิถีชีวิตมีการใช้แรงงานหนัก ออกกำลังกายหนักเป็นประจำ". "\n";
                  $textMessage2 = new TextMessageBuilder($textReplyMessage);
                  $actionBuilder = array(
                                          new MessageTemplateActionBuilder(
                                          'เบา',// ข้อความแสดงในปุ่ม
                                          'เบา' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                                          ),
                                           new MessageTemplateActionBuilder(
                                          'ปานกลาง',// ข้อความแสดงในปุ่ม
                                          'ปานกลาง' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                                          ),
                                           new MessageTemplateActionBuilder(
                                          'หนัก',// ข้อความแสดงในปุ่ม
                                          'หนัก' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                                          ) 
                                         );

                     $imageUrl = NULL;
                    $textMessage3 = new TemplateMessageBuilder('ระดับของการออกกำลังกาย',
                     new ButtonTemplateBuilder(
                              'ระดับของการออกกำลังกาย', // กำหนดหัวเรื่อง
                              'เลือกระดับด้านล่างได้เลยค่ะ', // กำหนดรายละเอียด
                               $imageUrl, // กำหนด url รุปภาพ
                               $actionBuilder  // กำหนด action object
                         )
                      );                            

                  $multiMessage = new MultiMessageBuilder;
                  $multiMessage->add($textMessage1);
                  $multiMessage->add($textMessage2);
                  $multiMessage->add($textMessage3);
                  $textMessageBuilder = $multiMessage; 

                    break;
                 case 5 : 
                  // $text1 = 'การแก้ไขข้อมูลโดยเลือก';
                  // $textMessage1 = new TextMessageBuilder($text1);
                  $textMessage2 = new TemplateMessageBuilder('คุณแม่ต้องการแก้ไขข้อมูลไหม?', new ConfirmTemplateBuilder( $text1 ,
                                array(
                                    new MessageTemplateActionBuilder(
                                        'แก้ไข',
                                        'แก้ไขข้อมูล'
                                    ),
                                    new MessageTemplateActionBuilder(
                                        'ยืนยันข้อมูล',
                                        'ยืนยันข้อมูล'
                                    )
                                )
                        )
                    ); 
                  $multiMessage =     new MultiMessageBuilder;
                  // $multiMessage->add($textMessage1);
                  $multiMessage->add($textMessage2);
                  // $multiMessage->add($textMessage3);
                  $textMessageBuilder = $multiMessage; 
                    break;
                  case 6 : 
                  $textMessage1 = new TextMessageBuilder('สวัสดีค่ะ ดิฉันเป็นหุ่นยนต์อัตโนมัติที่ถูกสร้างเพื่อว่าที่คุณแม่นะคะ ☺');
                  $textMessage2 = new TextMessageBuilder('ดิฉันสามารถให้ข้อมูลโภชนาการและติดตามไลฟ์สไตล์ของคุณได้ตลอดช่วงการตั้งครรภ์ค่ะ');
                  $textMessage3 = new TextMessageBuilder('เนื่องจากดิฉันยังเรียนรู้ภาษาอยู่ จึงอาจไม่เข้าใจภาษาดีพอนะคะ ต้องขออภัยล่วงหน้าด้วยค่ะ');
    

                  $textMessage4 = new TemplateMessageBuilder('คุณสนใจให้ดิฉันเป็นผู้ช่วยอัตโนมัติของคุณไหม', new ConfirmTemplateBuilder( 'คุณสนใจให้ดิฉันเป็นผู้ช่วยอัตโนมัติของคุณไหม' ,
                                array(
                                    // new MessageTemplateActionBuilder(
                                    //     'สนใจ',
                                    //     'สนใจ'
                                    // ),
                                    new UriTemplateActionBuilder(
                                        'สนใจ', // ข้อความแสดงในปุ่ม
                                        'line://app/1539139857-lEpd7MrZ'
                                  ),
                                    new MessageTemplateActionBuilder(
                                        'ไม่สนใจ',
                                        'ไม่สนใจ'
                                    )
                                )
                        )
                    ); 
                  $multiMessage =     new MultiMessageBuilder;
                  $multiMessage->add($textMessage1);
                  $multiMessage->add($textMessage2);
                  $multiMessage->add($textMessage3);
                  $multiMessage->add($textMessage4);
 
                  // $multiMessage->add($textMessage3);
                  $textMessageBuilder = $multiMessage; 
                    break;

                 case 7:
                    
                    $users_register = (new SqlController)->users_register_select($userMessage);
                    $preg_week = $users_register->preg_week;

// new UriTemplateActionBuilder('กราฟ','https://remi.softbot.ai/graph/'.$userMessage),
                    $actionBuilder = array(
                                          // new UriTemplateActionBuilder(
                                          // 'กราฟน้ำหนัก', // ข้อความแสดงในปุ่ม
                                          // 'https://remi.softbot.aihttps://remi.softbot.ai/graph/'.$userMessage
                                          // ),
                                           new MessageTemplateActionBuilder(
                                         'ทารกในครรภ์',// ข้อความแสดงในปุ่ม
                                         'ทารกในครรภ์' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                                          )
                                         );

                    $imageUrl = 'https://service.foodieat.in.th/remi/week/'.$preg_week.'.jpg';
                    $textMessageBuilder = new TemplateMessageBuilder('สรุปข้อมูล',
                     new ButtonTemplateBuilder(
                               'ขณะนี้คุณแม่มีอายุครรภ์'.$preg_week.'สัปดาห์', // กำหนดหัวเรื่อง
                               'กราฟน้ำหนักระหว่างการตั้งครรภ์', // กำหนดรายละเอียด
                               $imageUrl, // กำหนด url รุปภาพ
                               $actionBuilder  // กำหนด action object
                         )
                      );  

                    break;

                 case 8 : 
                         $textMessageBuilder = new TemplateMessageBuilder('สัปดาห์นี้คุณแม่มีน้ำหนัก', new ConfirmTemplateBuilder( 'สัปดาห์นี้คุณแม่มีน้ำหนัก'.$userMessage.'กิโลกรัมใช่ไหมคะ?' ,
                                array(
                                    new MessageTemplateActionBuilder(
                                        'ถูกต้อง',
                                        'น้ำหนักถูกต้อง'
                                    ),
                                    new MessageTemplateActionBuilder(
                                        'ไม่ถูกต้อง',
                                        'ไม่ถูกต้อง'
                                    )
                                )
                        )
                    ); 
                    break;
                     case 9 : 
                  $textMessageBuilder = new TemplateMessageBuilder('คุณแม่มีประวัติการแพ้ยาไหมคะ', new ConfirmTemplateBuilder( $userMessage ,
                                array(
                                    new MessageTemplateActionBuilder(
                                        'แพ้',
                                        'แพ้ยา'
                                    ),
                                    new MessageTemplateActionBuilder(
                                        'ไม่แพ้',
                                        'ไม่แพ้ยา'
                                    )
                                )
                        )
                    ); 

                    break;
                     case 10 : 
                  $textMessageBuilder = new TemplateMessageBuilder('คุณแม่มีประวัติการแพ้อาหารไหมคะ', new ConfirmTemplateBuilder( $userMessage ,
                                array(
                                    new MessageTemplateActionBuilder(
                                        'แพ้',
                                        'แพ้อาหาร'
                                    ),
                                    new MessageTemplateActionBuilder(
                                        'ไม่แพ้',
                                        'ไม่แพ้อาหาร'
                                    )
                                )
                        )
                    ); 

                    break;
                    case 11 : 
                         $textMessageBuilder = new TemplateMessageBuilder('วันนี้คุณแม่ทานอะไรไปบ้างคะ?', new ConfirmTemplateBuilder( $userMessage ,
                                array(
                                    new MessageTemplateActionBuilder(
                                        'ทานแล้ว',
                                        'ทานแล้ว'
                                    ),
                                    new MessageTemplateActionBuilder(
                                        'ยังไม่ได้ทาน',
                                        'ยังไม่ได้ทาน'
                                    )
                                )
                        )
                    ); 
                    break;
                      case 12 : 
                         $textMessageBuilder = new TemplateMessageBuilder($userMessage, new ConfirmTemplateBuilder( $userMessage ,
                                array(
                                    new MessageTemplateActionBuilder(
                                        'ออกแล้ว',
                                        'ออกแล้ว'
                                    ),
                                    new MessageTemplateActionBuilder(
                                        'ยังไม่ได้ออก',
                                        'ยัง'
                                    )
                                )
                        )
                    ); 
                    break;
         
                      case 13 : 
                         $textMessageBuilder = new TemplateMessageBuilder($userMessage, new ConfirmTemplateBuilder( $userMessage ,
                                array(
                                    new MessageTemplateActionBuilder(
                                        'ต้องการ',
                                        'ต้องการเชื่อมข้อมูล'
                                    ),
                                    new MessageTemplateActionBuilder(
                                        'ไม่ต้องการ',
                                        'ไม่ต้องการเชื่อมข้อมูล'
                                    )
                                )
                        )
                    ); 
                    break;

                    case 14 :  
                 $textMessageBuilder = new TemplateMessageBuilder('คุณแม่เคยลงทะเบียนกับ ulife.info ไหม?', new ConfirmTemplateBuilder('คุณเคยลงทะเบียนกับ ulife.info ไหม?' ,
                                array(
                                    new MessageTemplateActionBuilder(
                                        'เคย',
                                        'เคยลงทะเบียน'
                                    ),
                                    new MessageTemplateActionBuilder(
                                        'ไม่เคย',
                                        'ไม่เคยลงทะเบียน'
                                    )
                                )
                        )
                    ); 


                   break;
                   case 15:
                      $textMessageBuilder = new TemplateMessageBuilder('แนะนำอาหาร',
                       new ImageCarouselTemplateBuilder(
                         array(
                              new ImageCarouselColumnTemplateBuilder(
                                'https://service.foodieat.in.th/remi/food/f_1.jpg',
                              new UriTemplateActionBuilder(
                                'Uri Template', // ข้อความแสดงในปุ่ม
                                'https://service.foodieat.in.th/remi/food/f_1.jpg'
                               )
                              ),
                              new ImageCarouselColumnTemplateBuilder(
                                'https://service.foodieat.in.th/remi/food/f_2.jpg',
                              new UriTemplateActionBuilder(
                                'Uri Template', // ข้อความแสดงในปุ่ม
                                'https://service.foodieat.in.th/remi/food/f_2.jpg'
                                )
                              ),
                              new ImageCarouselColumnTemplateBuilder(
                                'httpshttps://service.foodieat.in.th/remi/food/f_3.jpg',
                              new UriTemplateActionBuilder(
                                'Uri Template', // ข้อความแสดงในปุ่ม
                                'https://service.foodieat.in.th/remi/food/f_3.jpg'
                                )
                              ),
                                 new ImageCarouselColumnTemplateBuilder(
                                'https://service.foodieat.in.th/remi/food/f_4.jpg',
                              new UriTemplateActionBuilder(
                                'Uri Template', // ข้อความแสดงในปุ่ม
                                'https:https://service.foodieat.in.th/remi/food/f_4.jpg'
                               )
                              ),
                              new ImageCarouselColumnTemplateBuilder(
                                'https://service.foodieat.in.th/remi/food/f_5.jpg',
                              new UriTemplateActionBuilder(
                                'Uri Template', // ข้อความแสดงในปุ่ม
                                'htthttps://service.foodieat.in.th/remi/food/f_5.jpg'
                                )
                              ),
                              new ImageCarouselColumnTemplateBuilder(
                                'https://service.foodieat.in.th/remi/food/f_6.jpg',
                              new UriTemplateActionBuilder(
                                'Uri Template', // ข้อความแสดงในปุ่ม
                                'https://service.foodieat.in.th/remi/food/f_6.jpg'
                                )
                              ),    
                               new ImageCarouselColumnTemplateBuilder(
                                'https://service.foodieat.in.th/remi/food/n_1.jpg',
                              new UriTemplateActionBuilder(
                                'Uri Template', // ข้อความแสดงในปุ่ม
                                'https://service.foodieat.in.th/remi/food/n_1.jpg'
                               )
                              ),
                              new ImageCarouselColumnTemplateBuilder(
                                'https://service.foodieat.in.th/remi/food/n_2.jpg',
                              new UriTemplateActionBuilder(
                                'Uri Template', // ข้อความแสดงในปุ่ม
                                'https://service.foodieat.in.th/remi/food/n_2.jpg'
                                )
                              ),
                              new ImageCarouselColumnTemplateBuilder(
                                'https://service.foodieat.in.th/remi/food/n_3.jpg',
                              new UriTemplateActionBuilder(
                                'Uri Template', // ข้อความแสดงในปุ่ม
                                'https://service.foodieat.in.th/remi/food/n_3.jpg'
                                )
                              ),                                       
                        )
                      )
                    );
                   break;  
                     case 16:
                      $textMessageBuilder = new TemplateMessageBuilder('แนะนำการออกกำลังกาย',
                       new ImageCarouselTemplateBuilder(
                         array(
                              new ImageCarouselColumnTemplateBuilder(
                                'https://service.foodieat.in.th/remi/manual/exercise.jpg',
                              new UriTemplateActionBuilder(
                                'Uri Template', // ข้อความแสดงในปุ่ม
                                'http://www.raipoong.com/content/detail.php?section=12&category=26&id=467'
                               )
                              ),
                              new ImageCarouselColumnTemplateBuilder(
                                'https://service.foodieat.in.th/remi/manual/exercise2.jpg',
                              new UriTemplateActionBuilder(
                                'Uri Template', // ข้อความแสดงในปุ่ม
                                'http://www.raipoong.com/content/detail.php?section=12&category=26&id=467'
                                )
                              ),
                              new ImageCarouselColumnTemplateBuilder(
                                'https://service.foodieat.in.th/remi/manual/exercise3.jpg',
                              new UriTemplateActionBuilder(
                                'Uri Template', // ข้อความแสดงในปุ่ม
                                'http://www.raipoong.com/content/detail.php?section=12&category=26&id=467'
                                )
                              )                                       
                        )
                      )
                    );
                   break;  

                      case 17 : 
                        $actionBuilder = array(
                                          new MessageTemplateActionBuilder(
                                          'โรงพยาบาลธรรมศาสตร์',
                                          'โรงพยาบาลธรรมศาสตร์' 
                                          ),
                                           new MessageTemplateActionBuilder(
                                          'โรงพยาบาลศิริราช',
                                          'โรงพยาบาลศิริราช' 
                                          ) 
                                         );

                        $imageUrl = NULL;
                        $textMessageBuilder = new TemplateMessageBuilder('โรงพยาบาลที่ฝากครรภ์',
                        new ButtonTemplateBuilder(
                              $userMessage, 
                              'กดเลือกด้านล่างเลยนะคะ', 
                               $imageUrl, 
                               $actionBuilder  
                           )
                        );  
                         break;
                      case 18 : 


                    $picFullSize = $userMessage;
                    $picThumbnail = $userMessage;
                    $textMessageBuilder = new ImageMessageBuilder($picFullSize,$picThumbnail);
                   

                    break;

                   case 19 : 


                  $text1 = 'อยากรู้อะไรกดเลยค่ะ';
                  $textMessage1 = new TextMessageBuilder($text1);
                    // $imageMapUrl = 'https://remi.softbot.ai/food/new_nutri2.jpg?_ignored=';
                    //  $imageMapUrl = 'https://service.foodieat.in.th/remi/image/mapmess.jpg?_ignored=';
                    $imageMapUrl = 'https://service.foodieat.in.th/remi/Line_menu/knowlaged_menu.png?_ignored=';
                    $textMessage2 = new ImagemapMessageBuilder(
                        $imageMapUrl,
                        'แนะนำอาหาร',
                        new BaseSizeBuilder(1040,1040),
                        array(
                            new ImagemapMessageActionBuilder(
                                'คำนวณค่าดัชนีมวลกายอย่างไร?',
                                new AreaBuilder(2,0,206,333)
                                ),
                            new ImagemapMessageActionBuilder(
                                'คุณแม่รูปร่างต่างกันน้ำหนักควรเพิ่มเท่าไร?',
                                new AreaBuilder(214,0,195,335)
                                ),
                            new ImagemapMessageActionBuilder(
                                'อาหารที่คุณแม่ควรทาน',
                                new AreaBuilder(419,0,200,335)
                                ),
                            new ImagemapMessageActionBuilder(
                                'อาหารอะไรที่ควรหลีกเลี่ยง?',
                                new AreaBuilder(625,0,201,335)
                                ),
                            new ImagemapMessageActionBuilder(
                                'สัดส่วนอาหาร',
                                new AreaBuilder(832,2,204,329)
                                ),


                            new ImagemapMessageActionBuilder(
                                'ซื้ออาหารกินข้างนอก จะกะปริมาณอย่างไร?',
                                new AreaBuilder(4,337,206,353)
                                ),
                            new ImagemapMessageActionBuilder(
                                'ไม่อิ่ม ทำอย่างไร?',
                                new AreaBuilder(216,339,193,299)
                                ),
                            new ImagemapMessageActionBuilder(
                                'แพ้ท้อง กินอย่างไร?',
                                new AreaBuilder(417,339,204,232)
                                ),
                            new ImagemapMessageActionBuilder(
                                'ท้องผูก ท้องอืด ทำอย่างไร?',
                                new AreaBuilder(625,341,197,291)
                                ),
                            new ImagemapMessageActionBuilder(
                                'กินไม่ถึง หรือกินเกิน ทำอย่างไร?',
                                new AreaBuilder(828,337,208,347)
                                ),

                            
                            new ImagemapMessageActionBuilder(
                                'ไม่กิน [อาหารบางชนิด] กินอะไรแทนดี?',
                                new AreaBuilder(4,693,204,344)
                                ),
                            new ImagemapMessageActionBuilder(
                                'แนะนำเมนูอาหาร',
                                new AreaBuilder(218,639,165,398)
                                ),
                            
                            new ImagemapUriActionBuilder(
                                'https://youtu.be/bkYq9oNHIPA',
                                new AreaBuilder(397,582,250,455)
                                ),
                            new ImagemapMessageActionBuilder(
                                'แนะนำการออกกำลังกาย',
                                new AreaBuilder(645,641,183,393)
                                ),
                             new ImagemapMessageActionBuilder(
                                'อื่น ๆ (ฝากคำถามไว้ได้)',
                                new AreaBuilder(832,683,208,354)
                                ),

                        )); 

                  $multiMessage =     new MultiMessageBuilder;
                  $multiMessage->add($textMessage1);
                  $multiMessage->add($textMessage2);
                  // $multiMessage->add($textMessage3);
                  $textMessageBuilder = $multiMessage; 
                    break;     
                      case 20 : 
                    $text1 = 'อยากรู้อะไรกดเลยค่ะ';
                    $textMessage1 = new TextMessageBuilder($text1);
                   // $imageMapUrl = 'https://service.foodieat.in.th/remi/food/exer1.jpg?_ignored=';
                    $imageMapUrl = 'https://service.foodieat.in.th/remi/Line_menu/exercise_menu.png?_ignored=';
                    $textMessage2 = new ImagemapMessageBuilder(
                        $imageMapUrl,
                        'แนะนำการออกกำลังกาย',
                        new BaseSizeBuilder(1040,1040),
                        array(

                            new ImagemapMessageActionBuilder(
                                'กระดกข้อเท้า',
                                new AreaBuilder(4,161,365,173)
                                ),
                            new ImagemapMessageActionBuilder(
                                'ยกก้น',
                                new AreaBuilder(373,161,329,173)
                                ),
                            new ImagemapMessageActionBuilder(
                                'นอนเตะขา',
                                new AreaBuilder(704,157,336,175)
                                ),


                            new ImagemapMessageActionBuilder(
                                'นอนตะแคงยกขา',
                                new AreaBuilder(4,336,369,172)
                                ),
                            new ImagemapMessageActionBuilder(
                                'คลานสี่ขา',
                                new AreaBuilder(371,336,333,1730)
                                ),
                            new ImagemapMessageActionBuilder(
                                'แมวขู่',
                                new AreaBuilder(702,332,334,174)
                                ),


                            new ImagemapMessageActionBuilder(
                                'นั่งโยกตัว',
                                new AreaBuilder(4,510,367,173)
                                ),
                            new ImagemapMessageActionBuilder(
                                'นั่งเตะขา',
                                new AreaBuilder(373,506,326,177)
                                ),
                            new ImagemapMessageActionBuilder(
                                'ยืนงอเข่า',
                                new AreaBuilder(699,504,337,177)
                                ),


                            new ImagemapMessageActionBuilder(
                                'ยืนเตะขาไปข้างหลัง',
                                new AreaBuilder(0,679,367,177)
                                ),
                            new ImagemapMessageActionBuilder(
                                'ยืนเตะขาไปด้านข้าง',
                                new AreaBuilder(365,685,334,173)
                                ),
                            new ImagemapMessageActionBuilder(
                                'ยืนเขย่งเท้า',
                                new AreaBuilder(699,683,341,174)
                                ),


                            new ImagemapMessageActionBuilder(
                                'ยืนกางแขน',
                                new AreaBuilder(0,858,373,180)
                                ),
                            new ImagemapMessageActionBuilder(
                                'ยืนแกว่งแขนสลับขึ้นลง',
                                new AreaBuilder(375,856,327,182)
                                ),
                            new ImagemapMessageActionBuilder(
                                'ยืนย่ำอยู่กับที่',
                                new AreaBuilder(704,856,334,182)
                                ),

                        )); 
                        $multiMessage =     new MultiMessageBuilder;
                        $multiMessage->add($textMessage1);
                        $multiMessage->add($textMessage2);
                       // $multiMessage->add($textMessage3);
                        $textMessageBuilder = $multiMessage; 
                    break;   

                    case 21 :  
                    $picFullSize = 'https://service.foodieat.in.th/remi/food/ex'.$userMessage.'.jpg';
                    $picThumbnail = 'https://service.foodieat.in.th/remi/food/ex'.$userMessage.'.jpg';
                    $textMessage1 = new ImageMessageBuilder($picFullSize,$picThumbnail);
                    // $picThumbnail = 'https://www.youtube.com/watch?v=eUvG5U8g6SY&list=PLWa93dkeDtZ_CidjnWp-EECxCA5IDjOa7&index=1'.$userMessage.'.mp4';
                    // $videoUrl = 'https://remi.softbot.ai/video/'.$userMessage.'.mp4';             
                    // $textMessage2 = new VideoMessageBuilder($videoUrl,$picThumbnail);

                  if($userMessage=='1'){
                    $url ='https://youtu.be/EBrJjY8rcKU' ;
                  }elseif ($userMessage=='2') {
                    $url ='https://youtu.be/igYQOEF4zLQ' ;

                  }elseif ($userMessage=='3') {
                    $url ='https://youtu.be/_Q4OEhIyhKs' ;
                  }elseif ($userMessage=='4') {
                    $url ='https://youtu.be/pvcLmuDoxrk' ;
                  }elseif ($userMessage=='5') {
                    $url ='https://youtu.be/yf9zYjWwivg' ;
                  }elseif ($userMessage=='6') {
                    $url ='https://youtu.be/zNsjAGqXq8M' ;
                  }elseif ($userMessage=='7') {
                    $url ='https://youtu.be/Z8Kdk810y10' ;
                  }elseif ($userMessage=='8') {
                    $url ='https://youtu.be/2lSc54RR2wI' ;

                  ///
                  }elseif ($userMessage=='9') {
                    $url ='https://youtu.be/5UaaDI_zsjQ' ;
                  }elseif ($userMessage=='10') {
                    $url ='https://youtu.be/h336s5sM-Lk' ;
                  }elseif ($userMessage=='11') {
                    $url ='https://youtu.be/jBKMyY2zmBQ' ;
                  }elseif ($userMessage=='12') {
                    $url ='https://youtu.be/Y5ZJyN-N1Eo' ;


                  }elseif ($userMessage=='13') {
                    $url ='https://youtu.be/sxQrAIJ1xHk' ;

                  //
                  }elseif ($userMessage=='14') {
                    $url ='https://youtu.be/VikImI_S1ds' ;


                  }elseif ($userMessage=='15') {
                    $url ='https://youtu.be/6n9xoSTCyKk' ;
                  }
         
                  $textMessage2 = new TextMessageBuilder($url);
                  $multiMessage =     new MultiMessageBuilder;
                  $multiMessage->add($textMessage1);
                  $multiMessage->add($textMessage2);
                  $textMessageBuilder = $multiMessage;  
                  break;
                  case 22: 
                        $actionBuilder = array(
                                          new MessageTemplateActionBuilder(
                                          'บันทึกอาหาร',
                                          'บันทึกอาหาร' 
                                          ),
                                           new MessageTemplateActionBuilder(
                                          'บันทึกวิตามิน',
                                          'บันทึกวิตามิน' 
                                          ),
                                           new MessageTemplateActionBuilder(
                                          'บันทึกการออกกำลังกาย',
                                          'บันทึกการออกกำลังกาย' 
                                          )  
                                         );

                        $imageUrl = NULL;
                        $textMessageBuilder = new TemplateMessageBuilder('บันทึกย้อนหลัง',
                        new ButtonTemplateBuilder(
                              $userMessage, 
                              'กดเลือกด้านล่างเลยนะคะ', 
                               $imageUrl, 
                               $actionBuilder  
                           )
                        );  
                         break;
                  case 23: 

                      $text1 = $userMessage->id;
                      $text2 = $userMessage->content;

                      $textMessage1 = new TextMessageBuilder($text1);
                      $textMessage2 = new TextMessageBuilder($text2);

                      $multiMessage = new MultiMessageBuilder;
                      $multiMessage->add($textMessage1);
                      $multiMessage->add($textMessage2);
                      $textMessageBuilder = $multiMessage; 
                  break;
                  case 24:
                  $user = $userMessage;
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
                  $cal  = (new CalController)->cal_calculator($user_age,$active_lifestyle,$user_Pre_weight,$preg_week);

                  $ws = (new SqlController)->weight_status_line($user);  
                  $ws = $ws->weight_status;
                      if ($weight_criteria =='น้ำหนักน้อย') {
                        $result='1';
                      } elseif ($weight_criteria =='น้ำหนักปกติ') {
                        $result='2';
                      } elseif ($weight_criteria == 'น้ำหนักเกิน') {
                        $result='3';
                      } elseif ($weight_criteria =='อ้วน') {
                        $result='4';
                      }
            
                  if($ws == '1'){
                    $w = 'อัตราการเพิ่มน้ำหนัก ปกติตามเกณฑ์';
                  }elseif($ws == '2'){
                    $w = 'อัตราการเพิ่มน้ำหนัก น้อยกว่าเกณฑ์';
                  }elseif($ws == '3'){  
                    $w = 'อัตราการเพิ่มน้ำหนัก เกินกว่าเกณฑ์';
                  }elseif($ws == '4'){  
                    $w = 'คุณแม่มีภาวะแทรกซ้อน';
                  }else{
                     $w ='(BMI:'.$weight_criteria.')' ;
                  }

                   // $sq =  (new SqlController)->select_quizstep_user($user);
                   // $code_quiz1 = $sq->code_quiz;
                   // $reward_se =  (new SqlController)->reward_select($user,$code_quiz1);
                   $reward_se =  (new SqlController)->reward_select1($user);
                   $point = $reward_se->point;
                   if($point==null)
                    {
                      $point = 0;
                    }
                          $actionBuilder1 = array(
                            new MessageTemplateActionBuilder(
                                'ดูบันทึกอาหาร', // ข้อความแสดงในปุ่ม
                                'ดูบันทึกอาหาร'
                            ),
                            new MessageTemplateActionBuilder(
                                'ดูบันทึกวิตามิน', // ข้อความแสดงในปุ่ม
                                'ดูบันทึกวิตามิน'
                            ),
                            new MessageTemplateActionBuilder(
                                'ดูบันทึกออกกำลังกาย', // ข้อความแสดงในปุ่ม
                                'ดูบันทึกออกกำลังกาย'
                            ),
                            // new MessageTemplateActionBuilder(
                            //     'ข้อมูลส่วนตัว',// ข้อความแสดงในปุ่ม
                            //     'ข้อมูลส่วนตัว' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            // ),
                           );
                           $actionBuilder2 = array(
                            new MessageTemplateActionBuilder(
                                'บันทึกอาหาร',// ข้อความแสดงในปุ่ม
                                'บันทึกอาหารย้อนหลัง' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),
                            new MessageTemplateActionBuilder(
                                'บันทึกวิตามิน',// ข้อความแสดงในปุ่ม
                                'บันทึกวิตามินย้อนหลัง' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),
                            new MessageTemplateActionBuilder(
                                'บันทึกออกกำลังกาย',// ข้อความแสดงในปุ่ม
                                'บันทึกออกกำลังกายย้อนหลัง' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),
                           );
                            $actionBuilder3 = array(
                            new MessageTemplateActionBuilder(
                                'ข้อมูลส่วนตัว',// ข้อความแสดงในปุ่ม
                                'ดูข้อมูล' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),
                            new MessageTemplateActionBuilder(
                                'ข้อมูลลูกน้อย', // ข้อความแสดงในปุ่ม
                                'ข้อมูลลูกน้อย'
                            ),
                            new MessageTemplateActionBuilder(
                                'บันทึกน้ำหนัก',// ข้อความแสดงในปุ่ม
                                'บันทึกน้ำหนัก' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),
                           );
                            $actionBuilder4 = array(
                            new UriTemplateActionBuilder(
                                'กราฟน้ำหนัก', // ข้อความแสดงในปุ่ม
                                'https://service.foodieat.in.th/remi/graph/'.$userMessage
                            ),
                            new MessageTemplateActionBuilder(
                                'น้ำหนักตัวที่เหมาะสม',// ข้อความแสดงในปุ่ม
                                'น้ำหนักตัวที่เหมาะสม' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),
                            new MessageTemplateActionBuilder(
                                'ข้อมูลโภชนาการ',// ข้อความแสดงในปุ่ม
                                'ข้อมูลโภชนาการ' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),
                           );
                           /////////reward//////////////
                              $actionBuilder5 = array(
                            new MessageTemplateActionBuilder(
                                'เงื่อนไขการรับสิทธิ์', // ข้อความแสดงในปุ่ม
                                'เงื่อนไขการรับสิทธิ์'
                            ),
                            new MessageTemplateActionBuilder(
                                'แลกของรางวัล',// ข้อความแสดงในปุ่ม
                                'แลกของรางวัล' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),
                            new MessageTemplateActionBuilder(
                                'ดูของรางวัล',// ข้อความแสดงในปุ่ม
                                'ดูของรางวัล' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),
                           );
          
                        $textMessageBuilder = new TemplateMessageBuilder('Carousel',
                            new CarouselTemplateBuilder(
                                array(

                                    new CarouselColumnTemplateBuilder(
                                        'ข้อมูลคุณแม่',
                                        'ข้อมูลส่วนตัวของคุณแม่'."\n".'ขณะนี้คุณแม่มีอายุครรภ์'.$preg_week.'สัปดาห์',
                                        'https://service.foodieat.in.th/remi/image/profile_card1.png',
                                        $actionBuilder3
                                    ),    
                                    new CarouselColumnTemplateBuilder(
                                        'ข้อมูลโภชนาการของคุณแม่',
                                        $w."\n". 'จำนวนแคลอรี่ที่คุณแม่ต้องการต่อวันคือ '.$cal,
                                        'https://service.foodieat.in.th/remi/image/food_c2.png',
                                        $actionBuilder4
                                    ),                  
                                    // new CarouselColumnTemplateBuilder(
                                    //     'บันทึกการทานอาหาร ออกกำลังกายและวิตามิน',
                                    //     'ดูและแก้ไขบันทึกอาหาร,การทานวิตามินและการออกกำลังกาย',
                                    //     'https://service.foodieat.in.th/remi/image/note1.png',
                                    //     $actionBuilder1
                                    // ),
                                    // new CarouselColumnTemplateBuilder(
                                    //     'บันทึกข้อมูลย้อนหลัง',
                                    //     'การบันทึกอาหาร,การทานวิตามินและการออกกำลังกายย้อนหลัง',
                                    //     'https://remi.softbot.ai/image/note2.png',
                                    //     $actionBuilder2
                                    // ), 
                                    /////////reward//////////////
                                    // new CarouselColumnTemplateBuilder(
                                    //     'แต้มสะสม',
                                    //     'ตอนนี้คุณแม่มีแต้มสะสม '.$point.' แต้มค่ะ',
                                    //     'https://remi.softbot.ai/image/reward.png',
                                    //     $actionBuilder5
                                    // ), 
                                                               
                                )
                            )
                        );
                    break;
                     case 25: 
                
                        $actionBuilder = array(
                                          new MessageTemplateActionBuilder(
                                          'บันทึกอาหารเช้า',
                                          'บันทึกอาหารเช้าย้อนหลัง' 
                                          ),
                                           new MessageTemplateActionBuilder(
                                          'บันทึกอาหารกลางวัน',
                                          'บันทึกอาหารกลางวันย้อนหลัง' 
                                          ),
                                           new MessageTemplateActionBuilder(
                                          'บันทึกอาหารเย็น',
                                          'บันทึกอาหารเย็นย้อนหลัง' 
                                          )  
                                         );

                        $imageUrl = NULL;
                        $textMessageBuilder = new TemplateMessageBuilder('ช่วงเวลาที่คุณต้องการบันทึก',
                        new ButtonTemplateBuilder(
                              'ช่วงเวลาที่คุณแม่ต้องการบันทึกย้อนหลังค่ะ', 
                              'กดเลือกด้านล่างเลยนะคะ', 
                               $imageUrl, 
                               $actionBuilder  
                           )
                        );  
                         break;
                    case 26: 
                
                        $actionBuilder1 = array(
                                          new MessageTemplateActionBuilder(
                                          'อาหารเช้า',
                                          'แนะนำอาหารเช้า' 
                                          ),
                                           new MessageTemplateActionBuilder(
                                          'อาหารจานเดียว',
                                          'แนะนำอาหารจานเดียว' 
                                          ),
                                           new MessageTemplateActionBuilder(
                                          'กับข้าว',
                                          'แนะนำกับข้าว' 
                                          )
                                         );
                        $actionBuilder2 = array(
                                          new MessageTemplateActionBuilder(
                                          'เครื่องดื่ม',
                                          'แนะนำเครื่องดื่ม' 
                                          ),
                                           new MessageTemplateActionBuilder(
                                          'ผลไม้',
                                          'แนะนำผลไม้' 
                                          ),
                                           new MessageTemplateActionBuilder(
                                          'อาหารว่าง',
                                          'แนะนำอาหารว่าง' 
                                          )
                                         );

                        $imageUrl = NULL;
                        $textMessageBuilder = new TemplateMessageBuilder('แนะนำเมนูอาหาร',

                           new CarouselTemplateBuilder(
                                     array(
                                        new CarouselColumnTemplateBuilder(
                                             'แนะนำเมนูอาหาร', 
                                              'กดเลือกด้านล่างได้เลยค่ะ', 
                                               NULL, 
                                               $actionBuilder1  
                                        ),
                                        new CarouselColumnTemplateBuilder(
                                            'แนะนำเมนูอาหาร', 
                                            'กดเลือกด้านล่างได้เลยค่ะ', 
                                             NULL, 
                                             $actionBuilder2 
                                        ),                                         
                                    )
                             
                           )
                        );  
                         break;
                     case 27: 
                
                        $actionBuilder1 = array(
                                          new MessageTemplateActionBuilder(
                                          'ข้อมูลการใช้งาน',
                                          'ข้อมูลการใช้งาน' 
                                          ),
                                          new MessageTemplateActionBuilder(
                                          'วิดีโอการใช้งาน',
                                          'วิดีโอการใช้งาน' 
                                          ),
                                        
                                         );
                        $actionBuilder2 = array(
                                          new MessageTemplateActionBuilder(
                                          'เชื่อม Ulife.info',
                                          'เชื่อม Ulife.info' 
                                          ),
                                          new MessageTemplateActionBuilder(
                                          'คุณหมอประจำตัว',
                                          'คุณหมอประจำตัว' 
                                          ),
                                        
                                         );

                        $imageUrl = NULL;
                        $textMessageBuilder = new TemplateMessageBuilder('แนะนำการใช้งาน',
                        new CarouselTemplateBuilder(
                                     array(
                                        new CarouselColumnTemplateBuilder(
                                             'แนะนำการใช้งาน', 
                                              'กดเลือกข้างล่างได้เลยค่ะ', 
                                               NULL, 
                                               $actionBuilder1  
                                        ),
                                        new CarouselColumnTemplateBuilder(
                                            'การเชื่อมข้อมูล', 
                                            'กดเลือกข้างล่างได้เลยค่ะ', 
                                             NULL, 
                                             $actionBuilder2 
                                        ),                                         
                                    )
                             
                           )
                        );  
                         break;
                      case 28: 
                
                          // กำหนด action 4 ปุ่ม 4 ประเภท
                          $actionBuilder = array(
                              new DatetimePickerTemplateActionBuilder(
                                  'Datetime Picker', // ข้อความแสดงในปุ่ม
                                  http_build_query(array(
                                      'action'=>'reservation',
                                      'person'=>5
                                  )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                  'datetime', // date | time | datetime รูปแบบข้อมูลที่จะส่ง ในที่นี้ใช้ datatime
                                  substr_replace(date("Y-m-d H:i"),'T',10,1), // วันที่ เวลา ค่าเริ่มต้นที่ถูกเลือก
                                  substr_replace(date("Y-m-d H:i",strtotime("+5 day")),'T',10,1), //วันที่ เวลา มากสุดที่เลือกได้
                                  substr_replace(date("Y-m-d H:i"),'T',10,1) //วันที่ เวลา น้อยสุดที่เลือกได้
                              ),      
      
                          );
                          $imageUrl = 'https://www.mywebsite.com/imgsrc/photos/w/simpleflower';
                          $textMessageBuilder = new TemplateMessageBuilder('Button Template',
                              new ButtonTemplateBuilder(
                                      'button template builder', // กำหนดหัวเรื่อง
                                      'Please select', // กำหนดรายละเอียด
                                      $imageUrl, // กำหนด url รุปภาพ
                                      $actionBuilder  // กำหนด action object
                              )
                          );              
                      break;
                         case 29: 
                            $stickerID = 22;
                            $packageID = 2;
                            $textMessageBuilder = new StickerMessageBuilder($packageID,$stickerID);
                        
                      break;

                       case 30 :  
                 $textMessageBuilder = new TemplateMessageBuilder('ยืนยัน', new ConfirmTemplateBuilder('คุณแม่ยืนยันจะแลกของรางวัล '.$userMessage.' ใช่ไหมคะ?' ,
                                array(
                                    new MessageTemplateActionBuilder(
                                        'ยืนยัน',
                                        'ยืนยัน'
                                    ),
                                    new MessageTemplateActionBuilder(
                                        'ไม่ยืนยัน',
                                        'ไม่ยืนยัน'
                                    )
                                )
                        )
                    ); 


                   break;
                     case 31: 
                
                        $actionBuilder = array(
                                          new MessageTemplateActionBuilder(
                                          'แลกของรางวัล',
                                          'แลกของรางวัล' 
                                          ),
                                          // new MessageTemplateActionBuilder(
                                          // 'รับของรางวัล',
                                          // 'รับของรางวัล' 
                                          // ),
                                          new MessageTemplateActionBuilder(
                                          'exit',
                                          'Q' 
                                          ),
                                         );

                        $imageUrl = NULL;
                        $textMessage2 = new TemplateMessageBuilder('menu',
                        new ButtonTemplateBuilder(
                              'Menu', 
                              'เลือกด้านล่างได้เลยค่ะ', 
                              NULL, 
                              $actionBuilder  
                           )
                        );  
                        $textReplyMessage = $userMessage;
                        $textMessage1 = new TextMessageBuilder($textReplyMessage);
                        $multiMessage =     new MultiMessageBuilder;
                        $multiMessage->add($textMessage1);
                        $multiMessage->add($textMessage2);
                       // $multiMessage->add($textMessage3);
                        $textMessageBuilder = $multiMessage; 
                         break;
                    case 32 :  
                             $textMessageBuilder = new TemplateMessageBuilder('ยืนยัน', new ConfirmTemplateBuilder($userMessage,
                                array(
                                    new MessageTemplateActionBuilder(
                                        'ยืนยัน',
                                        'ยืนยัน'
                                    ),
                                    new MessageTemplateActionBuilder(
                                        'แก้ไขอีเมล',
                                        'แก้ไขข้อมูล'
                                    )
                                  )
                                )
                             ); 


                   break;

                      case 33 :  
                               $actionBuilder = array(
                                          new MessageTemplateActionBuilder(
                                          'กรอกรหัส',
                                          'กรอกรหัสคุณหมอ' 
                                          ),
                                          new UriTemplateActionBuilder(
                                          'scan QRcode',
                                          'line://nv/addFriends' 
                                          ),            
                                         );

                        $imageUrl = NULL;
                        $textMessageBuilder = new TemplateMessageBuilder('คุณหมอประจำตัว',
                        new ButtonTemplateBuilder(
                              'เลือกการใส่รหัสคุณหมอประจำตัว', 
                              'กดเลือกข้างล่างได้เลยค่ะ', 
                               NULL, 
                               $actionBuilder  
                           )
                        );  

                   break;
                      case 34 :  
                                $textMessageBuilder = new TemplateMessageBuilder('ยืนยัน', new ConfirmTemplateBuilder($userMessage ,
                                array(
                                    new MessageTemplateActionBuilder(
                                        'ใช่',
                                        'ใช่'
                                    ),
                                    new MessageTemplateActionBuilder(
                                        'ไม่ใช่',
                                        'ไม่ใช่'
                                    )
                                )
                        )
                    ); 

                   break;
                     case 35 :  
                            $actionBuilder = array(
                                new MessageTemplateActionBuilder(
                                    'Message Template',// ข้อความแสดงในปุ่ม
                                    'This is Text' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                                ),
                                new UriTemplateActionBuilder(
                                    'Uri Template', // ข้อความแสดงในปุ่ม
                                    'https://www.ninenik.com'
                                ),
                                new DatetimePickerTemplateActionBuilder(
                                    'Datetime Picker', // ข้อความแสดงในปุ่ม
                                    http_build_query(array(
                                        'action'=>'reservation',
                                        'person'=>5
                                    )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                    'datetime', // date | time | datetime รูปแบบข้อมูลที่จะส่ง ในที่นี้ใช้ datatime
                                    substr_replace(date("Y-m-d H:i"),'T',10,1), // วันที่ เวลา ค่าเริ่มต้นที่ถูกเลือก
                                    substr_replace(date("Y-m-d H:i",strtotime("+5 day")),'T',10,1), //วันที่ เวลา มากสุดที่เลือกได้
                                    substr_replace(date("Y-m-d H:i"),'T',10,1) //วันที่ เวลา น้อยสุดที่เลือกได้
                                ),      
                                new PostbackTemplateActionBuilder(
                                    'Postback', // ข้อความแสดงในปุ่ม
                                    http_build_query(array(
                                        'action'=>'buy',
                                        'item'=>100
                                    )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                    'Postback Text'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                                ),      
                            );
                            $imageUrl = 'https://www.mywebsite.com/imgsrc/photos/w/simpleflower';
                            $textMessageBuilder = new TemplateMessageBuilder('Button Template',
                                new ButtonTemplateBuilder(
                                        'button template builder', // กำหนดหัวเรื่อง
                                        'Please select', // กำหนดรายละเอียด
                                        $imageUrl, // กำหนด url รุปภาพ
                                        $actionBuilder  // กำหนด action object
                                )
                            );              

                   break;
                       case 36: 
                                     $reward_gift = (new SqlController)->reward_gift();
                                     $columnTemplateBuilders = array();
                              
                                  foreach ($reward_gift as $reward) {
                                  $columnTemplateBuilder = 
                                        new ImageCarouselColumnTemplateBuilder(
                                           'https://service.foodieat.in.th/remi/reward_gift/'.$reward['code_gift'].'.jpg',
                                            new UriTemplateActionBuilder(
                                                'link', // ข้อความแสดงในปุ่ม
                                                'hhttps://service.foodieat.in.th/remi/reward_gift/'.$reward['code_gift'].'.jpg'
                                            )
                                        );

                                  array_push($columnTemplateBuilders, $columnTemplateBuilder);
                                }

                              $textMessageBuilder = new TemplateMessageBuilder('Image Carousel',
                              new ImageCarouselTemplateBuilder(
                                 $columnTemplateBuilders  
                              )
                           );
                      break;
                       case 37: 
                
                $foodmenus = (new SqlController)->foodmenu($userMessage);
                $columnTemplateBuilders = array();
                foreach ($foodmenus as $foodmenu) {

                    $columnTemplateBuilder = new CarouselColumnTemplateBuilder(
                                  $foodmenu['name_food'], 
                                  $foodmenu['cal'],
                                  'https://service.foodieat.in.th/remi/menu/'.$foodmenu['img'].'.jpg',
                                  [
                                            new PostbackTemplateActionBuilder(
                                            'คำแนะนำ', // ข้อความแสดงในปุ่ม
                                            http_build_query(array(
                                                'action'=>'foodmenu',
                                                'item'=> 'MENUfood '.$foodmenu['id']
                                            )) //,// ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                           //'คำแนะนำ'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                                        ),      
                                  ]
                    );
                    array_push($columnTemplateBuilders, $columnTemplateBuilder);
                }

                $carouselTemplateBuilder = new CarouselTemplateBuilder($columnTemplateBuilders);
                $textMessageBuilder = new TemplateMessageBuilder('รายการอาหาร', $carouselTemplateBuilder);



                      break;
                case 38: 

                        $Message = "ยินดีต้อนรับการกลับมาค่ะ เรมี่ขอทราบอายุครรภ์ปัจจุบันของคุณแม่อีกครั้งนะคะ";
                        $textMessage2 =   new TextMessageBuilder($Message);

                        $actionBuilder = array(
                                          new MessageTemplateActionBuilder(
                                          'ครั้งสุดท้ายที่มีประจำเดือน',
                                          'ครั้งสุดท้ายที่มีประจำเดือน' 
                                          ),
                                           new MessageTemplateActionBuilder(
                                          'กำหนดการคลอด',
                                          'กำหนดการคลอด' 
                                          ) 
                                         );

                        $imageUrl = NULL;
                        $textMessage1 = new TemplateMessageBuilder('ขอทราบอายุครรภ์ของคุณแม่หน่อยค่ะ',
                        new ButtonTemplateBuilder(
                              $userMessage, 
                              'กรุณาเลือกตอบข้อใดข้อหนึ่งเพื่อให้ทางเราคำนวณอายุครรภ์ค่ะ', 
                               $imageUrl, 
                               $actionBuilder  
                           )
                        );      
                      
                        $multiMessage =     new MultiMessageBuilder;
                        $multiMessage->add($textMessage2);
                        $multiMessage->add($textMessage1);
                       // $multiMessage->add($textMessage3);
                        $textMessageBuilder = $multiMessage;         
                    break;
          
            
              case 39:
                    
                    $users_register = (new SqlController)->users_register_select($userMessage);
                    $preg_week = $users_register->preg_week;
                    $pregnants_lists = (new SqlController)->pregnants_list($preg_week);

                $columnTemplateBuilders = array();
                foreach ($pregnants_lists as $pregnants_list) {

                    $columnTemplateBuilder = new CarouselColumnTemplateBuilder(
                                  $pregnants_list['title'], 
                                  'รายละเอียดลูกน้อย',
                                  'https://service.foodieat.in.th/remi/week/'.$pregnants_list['week'].'.jpg',
                          [
                            new MessageTemplateActionBuilder('ทารกในครรภ์', 'ลูกน้อยสัปดาห์ที่:'.$pregnants_list['week'])
                          ,]
                    );
                    array_push($columnTemplateBuilders, $columnTemplateBuilder);
                }

                $carouselTemplateBuilder = new CarouselTemplateBuilder($columnTemplateBuilders);
                $textMessageBuilder = new TemplateMessageBuilder('รายการอาหาร', $carouselTemplateBuilder);
              break;

              case 40 : 
                        $actionBuilder = array(
                                          new MessageTemplateActionBuilder(
                                          'มีภาวะแทรกซ้อน',
                                          'มีภาวะแทรกซ้อน' 
                                          ),
                                           new MessageTemplateActionBuilder(
                                          'ไม่มีภาวะแทรกซ้อน',
                                          'ไม่มีภาวะแทรกซ้อน' 
                                          ) 
                                         );

                        $imageUrl = NULL;
                        $textMessageBuilder = new TemplateMessageBuilder('ภาวะแทรกซ้อน',
                        new ButtonTemplateBuilder(
                              $userMessage, 
                              'กดปุ่มเพื่อเลือกการบันทึกภาวะแทรกซ้อนระหว่างตั้งครรภ์', 
                               $imageUrl, 
                               $actionBuilder  
                           )
                        );              
                break;
                case 41 : 

                  $text1 = 'มาบันทึกประจำวันกันค่ะ';
                  $textMessage1 = new TextMessageBuilder($text1);
                    // $imageMapUrl = 'https://remi.softbot.ai/food/new_nutri2.jpg?_ignored=';
                    $imageMapUrl = 'https://service.foodieat.in.th/remi/Line_menu/diary.png?_ignored='; 
                    $textMessage2 = new ImagemapMessageBuilder(
                        $imageMapUrl,
                        'แนะนำอาหาร',
                        new BaseSizeBuilder(1040,1040),
                        array(
                            new ImagemapUriActionBuilder(
                              'https://service.foodieat.in.th/remi/record_diary/'.$user,
                                new AreaBuilder(49,190,461,242)
                                ),
                            new ImagemapUriActionBuilder(
                              'https://service.foodieat.in.th/remi/graph/'.$user,
                                new AreaBuilder(538,183,463,245)
                                ),
  
                            new ImagemapUriActionBuilder(
                              'https://liff.line.me/1656991660-v073Nlgm/',
                                new AreaBuilder(39,450,481,241)
                                ),
                            new ImagemapUriActionBuilder(
                              'https://service.foodieat.in.th/remi/graph_sugar_blood/'.$user,
                                new AreaBuilder(538,442,459,239)
                                ),


                            new ImagemapUriActionBuilder(
                              'https://service.foodieat.in.th/remi/babykicks/'.$user,
                                new AreaBuilder(35,706,477,239)
                                ),
                            new ImagemapUriActionBuilder(
                              'https://liff.line.me/1656991660-4LbgJrjy/',
                                new AreaBuilder(527,698,469,247)
                                )

                        )); 

                  $multiMessage =     new MultiMessageBuilder;
                  $multiMessage->add($textMessage1);
                  $multiMessage->add($textMessage2);
                  // $multiMessage->add($textMessage3);
                  $textMessageBuilder = $multiMessage; 
                    break;     
          }
                
          $response = $bot->replyMessage($replyToken,$textMessageBuilder);

         
    }
      public function replymessage3($replyToken,$question,$choice1,$choice2,$choice3)
    {
            // $httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
            // $bot = new LINEBot($httpClient, array('channelSecret' => LINE_MESSAGE_CHANNEL_SECRET));
            $httpClient = new CurlHTTPClient('UWrfpYzUUCCy44R4SFvqITsdWn/PeqFuvzLwey51hlRA1+AX/jSyCVUY7V2bPTkuoaDzmp1AY5CfsgFTIinxzxIYViz+chHSXWsxZdQb5AyZu7U67A9f18NQKE/HfGNrZZrwNxWNUwVJf2AszEsCvgdB04t89/1O/w1cDnyilFU=');
            $bot = new LINEBot($httpClient, array('channelSecret' => 'a06f8f521aabe202f1ce7427b4e52d1b'));

                          // $textReplyMessage = $userMessage;
                          // $textMessage1 = new TextMessageBuilder($textReplyMessage);
                          // $textReplyMessage =   "คำถาม";
                          // $textMessage2 = new TextMessageBuilder($textReplyMessage);
                          $actionBuilder = array(
                                          new MessageTemplateActionBuilder(
                                          $choice1,// ข้อความแสดงในปุ่ม
                                          $choice1 // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                                          ),
                                           new MessageTemplateActionBuilder(
                                          $choice2,// ข้อความแสดงในปุ่ม
                                          $choice2 // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                                          ),
                                          //  new MessageTemplateActionBuilder(
                                          // $choice3,// ข้อความแสดงในปุ่ม
                                          // $choice3 // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                                          // ) 
                                         );

                     $imageUrl = NULL;
                     $textMessage3 = new TemplateMessageBuilder('คำถาม',
                     new ButtonTemplateBuilder(
                              NULL, // กำหนดหัวเรื่อง
                              $question, // กำหนดรายละเอียด
                               $imageUrl, // กำหนด url รุปภาพ
                               $actionBuilder  // กำหนด action object
                         )
                      );                            

                  $multiMessage = new MultiMessageBuilder;
                  // $multiMessage->add($textMessage1);
                  // $multiMessage->add($textMessage2);
                  $multiMessage->add($textMessage3);
                  $textMessageBuilder = $multiMessage; 

     
          
             
                $response = $bot->replyMessage($replyToken,$textMessageBuilder); 


    }
     public function replymessage4($replyToken)
    {
            // $httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
            // $bot = new LINEBot($httpClient, array('channelSecret' => LINE_MESSAGE_CHANNEL_SECRET));
            $httpClient = new CurlHTTPClient('UWrfpYzUUCCy44R4SFvqITsdWn/PeqFuvzLwey51hlRA1+AX/jSyCVUY7V2bPTkuoaDzmp1AY5CfsgFTIinxzxIYViz+chHSXWsxZdQb5AyZu7U67A9f18NQKE/HfGNrZZrwNxWNUwVJf2AszEsCvgdB04t89/1O/w1cDnyilFU=');
            $bot = new LINEBot($httpClient, array('channelSecret' => 'a06f8f521aabe202f1ce7427b4e52d1b'));

            $user_update = (new SqlController)->reward_gift(); 

              foreach($user_update as $value){  

                $a = array(
                                    new CarouselColumnTemplateBuilder(
                                        $value->name_gift,
                                        'จำนวนแต้มสะสม '.$value->point .' แต้ม',
                                        NULL,
                                        array(
                                            new MessageTemplateActionBuilder(
                                                 'แลก',// ข้อความแสดงในปุ่ม
                                                 $value->code_gift // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                                            ),
                                           )
                                    ),                                  
                        );


              $textMessageBuilder = new TemplateMessageBuilder('Carousel',
                            new CarouselTemplateBuilder(
                                $a
                            )
                        );

             }
          
             
                $response = $bot->replyMessage($replyToken,$textMessageBuilder); 


    }

public function replymessage5($replyToken,$user)
    {
          // $httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
          // $bot = new LINEBot($httpClient, array('channelSecret' => LINE_MESSAGE_CHANNEL_SECRET));

          $httpClient = new CurlHTTPClient('UWrfpYzUUCCy44R4SFvqITsdWn/PeqFuvzLwey51hlRA1+AX/jSyCVUY7V2bPTkuoaDzmp1AY5CfsgFTIinxzxIYViz+chHSXWsxZdQb5AyZu7U67A9f18NQKE/HfGNrZZrwNxWNUwVJf2AszEsCvgdB04t89/1O/w1cDnyilFU=');
          $bot = new LINEBot($httpClient, array('channelSecret' => 'a06f8f521aabe202f1ce7427b4e52d1b'));

                $reward_gift = (new SqlController)->reward_gift();

                if( $reward_gift ==NULL){
                  $message = 'ไม่มีของรางวัล';
                  $textMessageBuilder = new TextMessageBuilder($message);
                  $seqcode = '0000';
                  $nextseqcode = '0000';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);

                }else{
                $columnTemplateBuilders = array();
                foreach ($reward_gift as $reward) {

                    $columnTemplateBuilder = new CarouselColumnTemplateBuilder(
                                  $reward['name_gift'], 
                                  'ใช้ '.$reward['point'].' แต้มในการแลก',
                                  'https://service.foodieat.in.th/remi/reward_gift/'.$reward['code_gift'].'.jpg',
                                  [
                                            new PostbackTemplateActionBuilder(
                                            'แลกของรางวัล', // ข้อความแสดงในปุ่ม
                                            http_build_query(array(
                                                'action'=>'reward',
                                                'item'=> $reward['code_gift']
                                            )) // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                           // 'แลก'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                                        ),      
                                  ]
                    );
                    array_push($columnTemplateBuilders, $columnTemplateBuilder);
                }

                $carouselTemplateBuilder = new CarouselTemplateBuilder($columnTemplateBuilders);
                $textMessageBuilder = new TemplateMessageBuilder('รายการ Sponser', $carouselTemplateBuilder);

                }

              

                $response = $bot->replyMessage($replyToken,$textMessageBuilder);

    }

    public function replymessage6($replyToken,$user)
    {
          // $httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
          // $bot = new LINEBot($httpClient, array('channelSecret' => LINE_MESSAGE_CHANNEL_SECRET));
          $httpClient = new CurlHTTPClient('UWrfpYzUUCCy44R4SFvqITsdWn/PeqFuvzLwey51hlRA1+AX/jSyCVUY7V2bPTkuoaDzmp1AY5CfsgFTIinxzxIYViz+chHSXWsxZdQb5AyZu7U67A9f18NQKE/HfGNrZZrwNxWNUwVJf2AszEsCvgdB04t89/1O/w1cDnyilFU=');
          $bot = new LINEBot($httpClient, array('channelSecret' => 'a06f8f521aabe202f1ce7427b4e52d1b'));

              //   $count = (new SqlController)->presenting_gift_count($user);

                 $rewards = (new SqlController)->presenting_gift_group($user);

                if( $rewards == NULL){
                  $message = 'คุณแม่ไม่มีของรางวัลที่ต้องรับค่ะ';
                  $textMessageBuilder = new TextMessageBuilder($message);
                  $seqcode = '0000';
                  $nextseqcode = '0000';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);

                }else{
                $columnTemplateBuilders = array();

                foreach ($rewards as $reward) {
                        
                    $columnTemplateBuilder = new CarouselColumnTemplateBuilder(
                        $reward['name_gift'], 
                        'จำนวน: X '.$reward['total'],
                        'https://service.foodieat.in.th/remi/card/badge.png',
                        [
                            new MessageTemplateActionBuilder('รับของรางวัล', $reward['code_gift'])
                        ,]
                    ); 
                    array_push($columnTemplateBuilders, $columnTemplateBuilder);
                }
     
                $carouselTemplateBuilder = new CarouselTemplateBuilder($columnTemplateBuilders);
                $textMessageBuilder = new TemplateMessageBuilder('รายการ Sponser', $carouselTemplateBuilder);

                }
                $response = $bot->replyMessage($replyToken,$textMessageBuilder);
          

            


    }
public function replymessage_food($replyToken,$user)
    {
          // $httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
          // $bot = new LINEBot($httpClient, array('channelSecret' => LINE_MESSAGE_CHANNEL_SECRET));
          $httpClient = new CurlHTTPClient('UWrfpYzUUCCy44R4SFvqITsdWn/PeqFuvzLwey51hlRA1+AX/jSyCVUY7V2bPTkuoaDzmp1AY5CfsgFTIinxzxIYViz+chHSXWsxZdQb5AyZu7U67A9f18NQKE/HfGNrZZrwNxWNUwVJf2AszEsCvgdB04t89/1O/w1cDnyilFU=');
          $bot = new LINEBot($httpClient, array('channelSecret' => 'a06f8f521aabe202f1ce7427b4e52d1b'));

          
                    $rewards   = (new SqlController)->foodmenu_img();
                    $columnTemplateBuilders = array();
                 foreach ($rewards as $reward) {
                        
                    $columnTemplateBuilder = new CarouselColumnTemplateBuilder(
                       NULL, 
                        'อาหาร',
                        'https://service.foodieat.in.th/remi/sug_food/'.$reward['name_img'],
                        [
                            new UriTemplateActionBuilder('link','https://service.foodieat.in.th/remi/sug_food/'.$reward['name_img'])
                        ,]
                    ); 
                    array_push($columnTemplateBuilders, $columnTemplateBuilder);
                }
     
                $carouselTemplateBuilder = new CarouselTemplateBuilder($columnTemplateBuilders);
                $textMessageBuilder = new TemplateMessageBuilder('รายการอาหาร', $carouselTemplateBuilder);

     
                $response = $bot->replyMessage($replyToken,$textMessageBuilder);

            


    }


public function replymessage_food1($replyToken,$user)
    {
          // $httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
          // $bot = new LINEBot($httpClient, array('channelSecret' => LINE_MESSAGE_CHANNEL_SECRET));
          $httpClient = new CurlHTTPClient('UWrfpYzUUCCy44R4SFvqITsdWn/PeqFuvzLwey51hlRA1+AX/jSyCVUY7V2bPTkuoaDzmp1AY5CfsgFTIinxzxIYViz+chHSXWsxZdQb5AyZu7U67A9f18NQKE/HfGNrZZrwNxWNUwVJf2AszEsCvgdB04t89/1O/w1cDnyilFU=');
          $bot = new LINEBot($httpClient, array('channelSecret' => 'a06f8f521aabe202f1ce7427b4e52d1b'));



          $rewards   = (new SqlController)->foodmenu_img();
          $columnTemplateBuilders = array();

          foreach ($rewards as $reward) {
          $columnTemplateBuilder = 
                new ImageCarouselColumnTemplateBuilder(
                     'https://service.foodieat.in.th/remi/sug_food/'.$reward['name_img'],
                    new UriTemplateActionBuilder(
                        'link', // ข้อความแสดงในปุ่ม
                        'https://service.foodieat.in.th/remi/sug_food/'.$reward['name_img']
                    )
                );

          array_push($columnTemplateBuilders, $columnTemplateBuilder);
        }



        $textMessageBuilder = new TemplateMessageBuilder('Image Carousel',
        new ImageCarouselTemplateBuilder(
           $columnTemplateBuilders  
        )
    );
                $response = $bot->replyMessage($replyToken,$textMessageBuilder);         
    }
  public function replymessage_menu3($replyToken,$user)
      {
            // $httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
            // $bot = new LINEBot($httpClient, array('channelSecret' => LINE_MESSAGE_CHANNEL_SECRET));
            $httpClient = new CurlHTTPClient('UWrfpYzUUCCy44R4SFvqITsdWn/PeqFuvzLwey51hlRA1+AX/jSyCVUY7V2bPTkuoaDzmp1AY5CfsgFTIinxzxIYViz+chHSXWsxZdQb5AyZu7U67A9f18NQKE/HfGNrZZrwNxWNUwVJf2AszEsCvgdB04t89/1O/w1cDnyilFU=');
            $bot = new LINEBot($httpClient, array('channelSecret' => 'a06f8f521aabe202f1ce7427b4e52d1b'));

            $textMessageBuilder = new TemplateMessageBuilder('Image Carousel',
        new ImageCarouselTemplateBuilder(
            array(
                new ImageCarouselColumnTemplateBuilder(
                    'https://service.foodieat.in.th/remi/knowledge/1-467-pic20160128170301.jpg',
                    new UriTemplateActionBuilder(
                        'Uri', // ข้อความแสดงในปุ่ม
                        'https://service.foodieat.in.th/remi/knowledge/1-467-pic20160128170301.jpg'
                    )
                ),
                new ImageCarouselColumnTemplateBuilder(
                    'https://service.foodieat.in.th/remi/knowledge/2-467-pic20160128170348.jpg',
                    new UriTemplateActionBuilder(
                        'Uri', // ข้อความแสดงในปุ่ม
                        'https://service.foodieat.in.th/remi/knowledge/2-467-pic20160128170348.jpg'
                    )
                ),
                new ImageCarouselColumnTemplateBuilder(
                    'https://service.foodieat.in.th/remi/knowledge/3-467-pic20160128170601.jpg',
                    new UriTemplateActionBuilder(
                        'Uri', // ข้อความแสดงในปุ่ม
                        'https://service.foodieat.in.th/remi/knowledge/3-467-pic20160128170601.jpg'
                    )
                ),
                new ImageCarouselColumnTemplateBuilder(
                    'https://service.foodieat.in.th/remi/knowledge/4-467-pic20160128170609.jpg',
                    new UriTemplateActionBuilder(
                        'Uri', // ข้อความแสดงในปุ่ม
                        'https://service.foodieat.in.th/remi/knowledge/4-467-pic20160128170609.jpg'
                    )
                ),
                new ImageCarouselColumnTemplateBuilder(
                    'https://service.foodieat.in.th/remi/knowledge/5-467-pic20160128170618.jpg',
                    new UriTemplateActionBuilder(
                        'Uri', // ข้อความแสดงในปุ่ม
                        'https://service.foodieat.in.th/remi/knowledge/5-467-pic20160128170618.jpg'
                    )
                )                                       
            )
        )
    );
        
                  $response = $bot->replyMessage($replyToken,$textMessageBuilder);         
      }  
    public function replymessage_menu11($replyToken,$user)
      {
            // $httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
            // $bot = new LINEBot($httpClient, array('channelSecret' => LINE_MESSAGE_CHANNEL_SECRET));
            $httpClient = new CurlHTTPClient('UWrfpYzUUCCy44R4SFvqITsdWn/PeqFuvzLwey51hlRA1+AX/jSyCVUY7V2bPTkuoaDzmp1AY5CfsgFTIinxzxIYViz+chHSXWsxZdQb5AyZu7U67A9f18NQKE/HfGNrZZrwNxWNUwVJf2AszEsCvgdB04t89/1O/w1cDnyilFU=');
            $bot = new LINEBot($httpClient, array('channelSecret' => 'a06f8f521aabe202f1ce7427b4e52d1b'));

            $textMessageBuilder = new TemplateMessageBuilder('Image Carousel',
        new ImageCarouselTemplateBuilder(
            array(
                new ImageCarouselColumnTemplateBuilder(
                    'https://service.foodieat.in.th/remi/knowledge/6-467-pic20160128170626.jpg',
                    new UriTemplateActionBuilder(
                        'Uri', // ข้อความแสดงในปุ่ม
                        'https://service.foodieat.in.th/remi/knowledge/6-467-pic20160128170626.jpg'
                    )
                ),
                new ImageCarouselColumnTemplateBuilder(
                    'https://service.foodieat.in.th/remi/knowledge/7-467-pic20160128170631.jpg',
                    new UriTemplateActionBuilder(
                        'Uri', // ข้อความแสดงในปุ่ม
                        'https://service.foodieat.in.th/remi/knowledge/7-467-pic20160128170631.jpg'
                    )
                ),                                      
            )
        )
    );
        
                  $response = $bot->replyMessage($replyToken,$textMessageBuilder);         
      }     


    public function resultinfo($replyToken,$user)
    {

      // $httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
      //     $bot = new LINEBot($httpClient, array('channelSecret' => LINE_MESSAGE_CHANNEL_SECRET));

                   $users_register = users_register::where('user_id',$user)
                                                   ->whereNull('deleted_at')
                                                   ->first();

                   $user_name = $users_register->user_name;
                   $user_age = $users_register->user_age;
                   $user_height = $users_register->user_height;
                   $user_Pre_weight = $users_register->user_Pre_weight;
                   $user_weight = $users_register->user_weight;
                   $preg_week = $users_register->preg_week;
                   $phone_number = $users_register->phone_number;
                   $email = $users_register->email;
                   $hospital_name = $users_register->hospital_name;

                   $hospital_number = $users_register->hospital_number;
                   $history_medicine = $users_register->history_medicine;
                   $history_food = $users_register->history_food;
                   $bmi  = (new CalController)->bmi_calculator($user_Pre_weight,$user_height);

  
                   $compli_diabete = $users_register->compli_diabete;
                   $compli_hypertension = $users_register->compli_hypertension;
                   $compli_preterm_birth = $users_register->compli_preterm_birth;


                   if($compli_diabete == 1 ){
                        $compli_diabete = 'มีภาวะ';
                   }else{
                        $compli_diabete ='-';
                   }

                    if($compli_hypertension == 1 ){
                        $compli_hypertension = 'มีภาวะ';
                   }else{
                        $compli_hypertension ='-';
                   }

                    if($compli_preterm_birth== 1 ){
                        $compli_preterm_birth = 'มีภาวะ';
                   }else{
                        $compli_preterm_birth ='-';
                   }

                 $textMessageBuilder = 
                  [

                    'type' => 'flex',
                    'altText' => 'this is a flex message',
                    'contents' => 
                    array (
                      'type' => 'carousel',
                      'contents' => 
                      array (
                        0 => 
                        array (
                          'type' => 'bubble',
                          'styles' => 
                          array (
                            'footer' => 
                            array (
                              'separator' => true,
                            ),
                          ),
                          'body' => 
                          array (
                            'type' => 'box',
                            'layout' => 'vertical',
                            'contents' => 
                            array (
                              0 => 
                              array (
                                'type' => 'text',
                                'text' => 'สรุปข้อมูลคุณแม่',
                                'weight' => 'bold',
                                'color' => '#F06292',
                                'size' => 'md',
                                'wrap' => true,
                              ),
                              1 => 
                              array (
                                'type' => 'text',
                                'text' => 'หากต้องการแก้ไขข้อมูลให้กดที่ ✏',
                                'wrap' => true,
                                'color' => '#aaaaaa',
                                'size' => 'xs',
                              ),
                              2 => 
                              array (
                                'type' => 'separator',
                                'margin' => 'xxl',
                              ),
                              3 => 
                              array (
                                'type' => 'box',
                                'layout' => 'horizontal',
                                'margin' => 'xxl',
                                'spacing' => 'sm',
                                'contents' => 
                                array (
                                  0 => 
                                  array (
                                    'type' => 'text',
                                    'text' => 'ชื่อ',
                                    'size' => 'sm',
                                    'color' => '#555555',
                                    'wrap' => true,
                                    'flex' => 2,
                                  ),
                                  1 => 
                                  array (
                                    'type' => 'text',
                                    'text' => $user_name,
                                    'size' => 'sm',
                                    'color' => '#1DB446',
                                    'align' => 'center',
                                  ),
                                  2 => 
                                  array (
                                    'type' => 'button',
                                    'style' => 'primary',
                                    'color' => '#FCE4EC',
                                    'height' => 'sm',
                                    'flex' => 0,
                                    'action' => 
                                    array (
                                      'type' => 'message',
                                      'label' => '✏',
                                      'text' => 'แก้ไข:ชื่อ',
                                    ),
                                  ),
                                ),
                              ),
                              4 => 
                              array (
                                'type' => 'box',
                                'layout' => 'horizontal',
                                'margin' => 'sm',
                                'spacing' => 'sm',
                                'contents' => 
                                array (
                                  0 => 
                                  array (
                                    'type' => 'text',
                                    'text' => 'อายุ',
                                    'size' => 'sm',
                                    'color' => '#555555',
                                    'wrap' => true,
                                    'flex' => 2,
                                  ),
                                  1 => 
                                  array (
                                    'type' => 'text',
                                    'text' => $user_age,
                                    'size' => 'sm',
                                    'color' => '#1DB446',
                                    'align' => 'center',
                                  ),
                                  2 => 
                                  array (
                                    'type' => 'button',
                                    'style' => 'primary',
                                    'color' => '#FCE4EC',
                                    'height' => 'sm',
                                    'flex' => 0,
                                    'action' => 
                                    array (
                                      'type' => 'message',
                                      'label' => '✏',
                                      'text' => 'แก้ไข:อายุ',
                        
                                    ),
                                  ),
                                ),
                              ),
                              5 => 
                              array (
                                'type' => 'box',
                                'layout' => 'horizontal',
                                'margin' => 'sm',
                                'spacing' => 'sm',
                                'contents' => 
                                array (
                                  0 => 
                                  array (
                                    'type' => 'text',
                                    'text' => 'ส่วนสูง',
                                    'size' => 'sm',
                                    'color' => '#555555',
                                    'wrap' => true,
                                    'flex' => 2,
                                  ),
                                  1 => 
                                  array (
                                    'type' => 'text',
                                    'text' => $user_height,
                                    'size' => 'sm',
                                    'color' => '#1DB446',
                                    'align' => 'center',
                                  ),
                                  2 => 
                                  array (
                                    'type' => 'button',
                                    'style' => 'primary',
                                    'color' => '#FCE4EC',
                                    'height' => 'sm',
                                    'flex' => 0,
                                    'action' => 
                                    array (
                                      'type' => 'message',
                                      'label' => '✏',
                                      'text' => 'แก้ไข:ส่วนสูง',
                                    ),
                                  ),
                                ),
                              ),
                              6 => 
                              array (
                                'type' => 'box',
                                'layout' => 'horizontal',
                                'margin' => 'sm',
                                'spacing' => 'sm',
                                'contents' => 
                                array (
                                  0 => 
                                  array (
                                    'type' => 'text',
                                    'text' => 'น้ำหนักก่อนตั้งครรภ์',
                                    'size' => 'sm',
                                    'color' => '#555555',
                                    'wrap' => true,
                                    'flex' => 2,
                                  ),
                                  1 => 
                                  array (
                                    'type' => 'text',
                                    'text' => $user_Pre_weight,
                                    'size' => 'sm',
                                    'color' => '#1DB446',
                                    'align' => 'center',
                                  ),
                                  2 => 
                                  array (
                                    'type' => 'button',
                                    'style' => 'primary',
                                    'color' => '#FCE4EC',
                                    'height' => 'sm',
                                    'flex' => 0,
                                    'action' => 
                                    array (
                                      'type' => 'message',
                                      'label' => '✏',
                                      'text' => 'แก้ไข:น้ำหนักก่อนตั้งครรภ์',
                                      
                                    ),
                                  ),
                                ),
                              ),
                              7 => 
                              array (
                                'type' => 'box',
                                'layout' => 'horizontal',
                                'margin' => 'sm',
                                'spacing' => 'sm',
                                'contents' => 
                                array (
                                  0 => 
                                  array (
                                    'type' => 'text',
                                    'text' => 'น้ำหนักปัจจุบัน (BMI '.$bmi.')',
                                    'size' => 'sm',
                                    'color' => '#555555',
                                    'wrap' => true,
                                    'flex' => 2,
                                  ),
                                  1 => 
                                  array (
                                    'type' => 'text',
                                    'text' => $user_weight,
                                    'size' => 'sm',
                                    'color' => '#1DB446',
                                    'align' => 'center',
                                  ),
                                  2 => 
                                  array (
                                    'type' => 'button',
                                    'style' => 'primary',
                                    'color' => '#FCE4EC',
                                    'height' => 'sm',
                                    'flex' => 0,
                                    'action' => 
                                    array (
                                      'type' => 'message',
                                      'label' => '✏',
                                      'text' => 'แก้ไข:น้ำหนักปัจจุบัน',
                               
                                    ),
                                  ),
                                ),
                              ),
                              8 => 
                              array (
                                'type' => 'box',
                                'layout' => 'horizontal',
                                'margin' => 'md',
                                'spacing' => 'sm',
                                'contents' => 
                                array (
                                  0 => 
                                  array (
                                    'type' => 'text',
                                    'text' => 'อายุครรภ์',
                                    'size' => 'sm',
                                    'color' => '#555555',
                                    'wrap' => true,
                                    'flex' => 2,
                                  ),
                                  1 => 
                                  array (
                                    'type' => 'text',
                                    'text' => $preg_week.' สัปดาห์',
                                    'size' => 'sm',
                                    'color' => '#1DB446',
                                    'align' => 'center',
                                  ),
                                  2 => 
                                  array (
                                    'type' => 'button',
                                    'style' => 'primary',
                                    'color' => '#BFC9CA',
                                    'height' => 'sm',
                                    'flex' => 0,
                                    'action' => 
                                    array (
                                      'type' => 'message',
                                      'label' => '✏',
                                      'text' => 'แก้ไข:อายุครรภ์',
                                
                                    ),
                                  ),
                                ),
                              ),
                              9 => 
                              array (
                                'type' => 'box',
                                'layout' => 'horizontal',
                                'margin' => 'sm',
                                'spacing' => 'sm',
                                'contents' => 
                                array (
                                  0 => 
                                  array (
                                    'type' => 'text',
                                    'text' => 'เบอร์โทรศัพท์',
                                    'size' => 'sm',
                                    'color' => '#555555',
                                    'wrap' => true,
                                    'flex' => 1,
                                  ),
                                  1 => 
                                  array (
                                    'type' => 'text',
                                    'text' => $phone_number,
                                    'size' => 'sm',
                                    'color' => '#1DB446',
                                    'align' => 'center',
                                  ),
                                  2 => 
                                  array (
                                    'type' => 'button',
                                    'style' => 'primary',
                                    'color' => '#FCE4EC',
                                    'height' => 'sm',
                                    'flex' => 0,
                                    'action' => 
                                    array (
                                      'type' => 'message',
                                      'label' => '✏',
                                      'text' => 'แก้ไข:เบอร์โทรศัพท์',
                          
                                    ),
                                  ),
                                ),
                              ),
                            ),
                          ),
                        ),
                        1 => 
                        array (
                          'type' => 'bubble',
                          'body' => 
                          array (
                            'type' => 'box',
                            'layout' => 'vertical',
                            'contents' => 
                            array (
                              0 => 
                              array (
                                'type' => 'box',
                                'layout' => 'vertical',
                                'margin' => 'xxl',
                                'spacing' => 'sm',
                                'contents' => 
                                array (
                                  0 => 
                                  array (
                                    'type' => 'box',
                                    'layout' => 'horizontal',
                                    'contents' => 
                                    array (
                                      0 => 
                                      array (
                                        'type' => 'text',
                                        'text' => 'สรุปข้อมูลคุณแม่',
                                        'weight' => 'bold',
                                        'color' => '#F06292',
                                        'size' => 'md',
                                      ),
                                    ),
                                  ),
                                  1 => 
                                  array (
                                    'type' => 'text',
                                    'text' => 'หากต้องการแก้ไขข้อมูลให้กดที่ ✏',
                                    'wrap' => true,
                                    'color' => '#aaaaaa',
                                    'size' => 'xs',
                                  ),
                                  2 => 
                                  array (
                                    'type' => 'separator',
                                    'margin' => 'xxl',
                                  ),
                                  3 => 
                                  array (
                                    'type' => 'box',
                                    'layout' => 'horizontal',
                                    'margin' => 'xxl',
                                    'spacing' => 'sm',
                                    'contents' => 
                                    array (
                                      0 => 
                                      array (
                                        'type' => 'text',
                                        'text' => 'อีเมล์',
                                        'size' => 'sm',
                                        'color' => '#555555',
                                        'wrap' => true,
                                        'flex' => 1,
                                      ),
                                      1 => 
                                      array (
                                        'type' => 'text',
                                        'text' => $email,
                                        'size' => 'sm',
                                        'color' => '#1DB446',
                                        'align' => 'center',
                                        'wrap' => true,
                                      ),
                                      2 => 
                                      array (
                                        'type' => 'button',
                                        'style' => 'primary',
                                        'color' => '#FCE4EC',
                                        'height' => 'sm',
                                        'flex' => 0,
                                        'action' => 
                                        array (
                                          'type' => 'message',
                                          'label' => '✏',
                                          'text' => 'แก้ไข:อีเมล์',
                      
                                        ),
                                      ),
                                    ),
                                  ),
                                  4 => 
                                  array (
                                    'type' => 'box',
                                    'layout' => 'horizontal',
                                    'margin' => 'sm',
                                    'spacing' => 'sm',
                                    'contents' => 
                                    array (
                                      0 => 
                                      array (
                                        'type' => 'text',
                                        'text' => 'โรงพยาบาลที่ฝากครรภ์',
                                        'size' => 'sm',
                                        'color' => '#555555',
                                        'wrap' => true,
                                        'flex' => 1,
                                      ),
                                      1 => 
                                      array (
                                        'type' => 'text',
                                        'text' => $hospital_name,
                                        'size' => 'sm',
                                        'color' => '#1DB446',
                                        'wrap' => true,
                                        'align' => 'center',
                                      ),
                                      2 => 
                                      array (
                                        'type' => 'button',
                                        'style' => 'primary',
                                        'color' => '#FCE4EC',
                                        'height' => 'sm',
                                        'flex' => 0,
                                        'action' => 
                                        array (
                                          'type' => 'message',
                                          'label' => '✏',
                                          'text' => 'แก้ไข:โรงพยาบาลที่ฝากครรภ์',
                                      
                                        ),
                                      ),
                                    ),
                                  ),
                                  5 => 
                                  array (
                                    'type' => 'box',
                                    'layout' => 'horizontal',
                                    'margin' => 'sm',
                                    'spacing' => 'sm',
                                    'contents' => 
                                    array (
                                      0 => 
                                      array (
                                        'type' => 'text',
                                        'text' => 'แพ้อาหาร',
                                        'size' => 'sm',
                                        'color' => '#555555',
                                        'wrap' => true,
                                        'flex' => 1,
                                      ),
                                      1 => 
                                      array (
                                        'type' => 'text',
                                        'text' => $history_food,
                                        'size' => 'sm',
                                        'color' => '#1DB446',
                                        'wrap' => true,
                                        'align' => 'center',
                                      ),
                                      2 => 
                                      array (
                                        'type' => 'button',
                                        'style' => 'primary',
                                        'color' => '#FCE4EC',
                                        'height' => 'sm',
                                        'flex' => 0,
                                        'action' => 
                                        array (
                                          'type' => 'message',
                                          'label' => '✏',
                                          'text' => 'แก้ไข:แพ้อาหาร',
                                    
                                        ),
                                      ),
                                    ),
                                  ),
                                  6 => 
                                  array (
                                    'type' => 'separator',
                                    'margin' => 'xxl',
                                  ),
                                  7 => 
                                  array (
                                    'type' => 'box',
                                    'layout' => 'horizontal',
                                    'margin' => 'md',
                                    'contents' => 
                                    array (
                                      0 => 
                                      array (
                                        'type' => 'text',
                                        'text' => 'ภาวะแทรกซ้อนระหว่างตั้งครรภ์',
                                        'size' => 'md',
                                        'color' => '#F06292',
                                        'align' => 'center',
                                        'weight' => 'bold',
                                        'flex' => 0,
                                      ),
                                    ),
                                  ),
                                  8 => 
                                  array (
                                    'type' => 'box',
                                    'layout' => 'horizontal',
                                    'margin' => 'xxl',
                                    'spacing' => 'sm',
                                    'contents' => 
                                    array (
                                      0 => 
                                      array (
                                        'type' => 'text',
                                        'text' => 'เบาหวาน',
                                        'size' => 'sm',
                                        'color' => '#555555',
                                        'wrap' => true,
                                        'flex' => 2,
                                      ),
                                      1 => 
                                      array (
                                        'type' => 'text',
                                        'text' => $compli_diabete,
                                        'size' => 'sm',
                                        'color' => '#1DB446',
                                        'align' => 'center',
                                      ),
                                      2 => 
                                      array (
                                        'type' => 'button',
                                        'style' => 'primary',
                                        'color' => '#FCE4EC',
                                        'height' => 'sm',
                                        'flex' => 0,
                                        'action' => 
                                        array (
                                          'type' => 'message',
                                          'label' => '✏',
                                          'text' => 'แก้ไข:ภาวะแทรกซ้อนเบาหวาน',
                                        
                                        ),
                                      ),
                                    ),
                                  ),
                                  9 => 
                                  array (
                                    'type' => 'box',
                                    'layout' => 'horizontal',
                                    'margin' => 'sm',
                                    'spacing' => 'xs',
                                    'contents' => 
                                    array (
                                      0 => 
                                      array (
                                        'type' => 'text',
                                        'text' => 'ความดันสูง',
                                        'size' => 'sm',
                                        'color' => '#555555',
                                        'wrap' => true,
                                        'flex' => 2,
                                      ),
                                      1 => 
                                      array (
                                        'type' => 'text',
                                        'text' => $compli_hypertension,
                                        'size' => 'sm',
                                        'color' => '#1DB446',
                                        'align' => 'center',
                                      ),
                                      2 => 
                                      array (
                                        'type' => 'button',
                                        'style' => 'primary',
                                        'color' => '#FCE4EC',
                                        'height' => 'sm',
                                        'flex' => 0,
                                        'action' => 
                                        array (
                                          'type' => 'message',
                                          'label' => '✏',
                                          'text' => 'แก้ไข:ภาวะแทรกซ้อนความดัน',
                                         
                                        ),
                                      ),
                                    ),
                                  ),
                                  10 => 
                                  array (
                                    'type' => 'box',
                                    'layout' => 'horizontal',
                                    'margin' => 'sm',
                                    'spacing' => 'sm',
                                    'contents' => 
                                    array (
                                      0 => 
                                      array (
                                        'type' => 'text',
                                        'text' => 'เจ็บครรภ์คลอดก่อนกำหนด',
                                        'size' => 'sm',
                                        'color' => '#555555',
                                        'wrap' => true,
                                        'flex' => 2,
                                      ),
                                      1 => 
                                      array (
                                        'type' => 'text',
                                        'text' => $compli_preterm_birth,
                                        'size' => 'sm',
                                        'color' => '#1DB446',
                                        'align' => 'center',
                                      ),
                                      2 => 
                                      array (
                                        'type' => 'button',
                                        'style' => 'primary',
                                        'color' => '#FCE4EC',
                                        'height' => 'sm',
                                        'flex' => 0,
                                        'action' => 
                                        array (
                                          'type' => 'message',
                                          'label' => '✏',
                                          'text' => 'แก้ไข:ภาวะแทรกซ้อนเจ็บครรภ์คลอดก่อนกำหนด',
                    
                                        ),
                                      ),
                                    ),
                                  ),
                                ),
                              ),
                            ),
                          ),
                        ),
                      ),
                    )
                  ];


   $url = 'https://api.line.me/v2/bot/message/push';
   $data = [
    'to' => $user,
    'messages' => [$textMessageBuilder],
   ];
   $access_token = 'UWrfpYzUUCCy44R4SFvqITsdWn/PeqFuvzLwey51hlRA1+AX/jSyCVUY7V2bPTkuoaDzmp1AY5CfsgFTIinxzxIYViz+chHSXWsxZdQb5AyZu7U67A9f18NQKE/HfGNrZZrwNxWNUwVJf2AszEsCvgdB04t89/1O/w1cDnyilFU=';   
   $post = json_encode($data);
   $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
   $ch = curl_init($url);
   curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  //  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
   $result = curl_exec($ch);
   curl_close($ch);
   echo $result . "\r\n";
 }
   public function resultinfo_regis($replyToken,$user)
    {
                   $users_register = users_register::where('user_id',$user)
                                                   ->whereNull('deleted_at')
                                                   ->first();

                   $user_name = $users_register->user_name;
                   $user_age = $users_register->user_age;
                   $user_height = $users_register->user_height;
                   $user_Pre_weight = $users_register->user_Pre_weight;
                   $user_weight = $users_register->user_weight;
                   $preg_week = $users_register->preg_week;
                   $phone_number = $users_register->phone_number;
                   $email = $users_register->email;
                   $hospital_name = $users_register->hospital_name;

                   $hospital_number = $users_register->hospital_number;
                   $history_medicine = $users_register->history_medicine;
                   $history_food = $users_register->history_food;
                   $bmi  = (new CalController)->bmi_calculator($user_Pre_weight,$user_height);

                 $textMessageBuilder = 
                  [ 
                        'type' => 'flex',
                        'altText' => 'this is a flex message',
                        'contents' => 
                        array (
                          'type' => 'bubble',
                          'styles' => 
                          array (
                            'footer' => 
                            array (
                              'separator' => true,
                            ),
                          ),
                          'body' => 
                          array (
                            'type' => 'box',
                            'layout' => 'vertical',
                            'contents' => 
                            array (
                              0 => 
                              array (
                                'type' => 'text',
                                'text' => 'สรุปข้อมูลคุณแม่',
                                'weight' => 'bold',
                                'color' => '#F06292',
                                'size' => 'xl',
                                'wrap' => true,
                              ),
                              1 => 
                              array (
                                'type' => 'text',
                                'text' => 'กดยืนยันเพื่อยืนยันข้อมูลได้เลยค่ะ',
                                'wrap' => true,
                                'color' => '#aaaaaa',
                                'size' => 'xs',
                              ),
                              2 => 
                              array (
                                'type' => 'separator',
                                'margin' => 'xxl',
                              ),
                              3 => 
                              array (
                                'type' => 'box',
                                'layout' => 'horizontal',
                                'margin' => 'xxl',
                                'spacing' => 'sm',
                                'contents' => 
                                array (
                                  0 => 
                                  array (
                                    'type' => 'text',
                                    'text' => 'ชื่อ',
                                    'size' => 'sm',
                                    'color' => '#555555',
                                    'wrap' => true,
                                    'flex' => 2,
                                  ),
                                  1 => 
                                  array (
                                    'type' => 'text',
                                    'text' => $user_name,
                                    'size' => 'sm',
                                    'color' => '#1DB446',
                                    'align' => 'center',
                                    'wrap' => true,
                                    'flex' => 1,
                                  ),
                                ),
                              ),
                              4 => 
                              array (
                                'type' => 'box',
                                'layout' => 'horizontal',
                                'margin' => 'sm',
                                'spacing' => 'sm',
                                'contents' => 
                                array (
                                  0 => 
                                  array (
                                    'type' => 'text',
                                    'text' => 'อายุ',
                                    'size' => 'sm',
                                    'color' => '#555555',
                                    'wrap' => true,
                                    'flex' => 2,
                                  ),
                                  1 => 
                                  array (
                                    'type' => 'text',
                                    'text' => $user_age,
                                    'size' => 'sm',
                                    'color' => '#1DB446',
                                    'align' => 'center',
                                    'wrap' => true,
                                    'flex' => 1,
                                  ),
                                ),
                              ),
                              5 => 
                              array (
                                'type' => 'box',
                                'layout' => 'horizontal',
                                'margin' => 'sm',
                                'spacing' => 'sm',
                                'contents' => 
                                array (
                                  0 => 
                                  array (
                                    'type' => 'text',
                                    'text' => 'ส่วนสูง',
                                    'size' => 'sm',
                                    'color' => '#555555',
                                    'wrap' => true,
                                    'flex' => 2,
                                  ),
                                  1 => 
                                  array (
                                    'type' => 'text',
                                    'text' => $user_height,
                                    'size' => 'sm',
                                    'color' => '#1DB446',
                                    'align' => 'center',
                                    'wrap' => true,
                                    'flex' => 1,
                                  ),
                                ),
                              ),
                              6 => 
                              array (
                                'type' => 'box',
                                'layout' => 'horizontal',
                                'margin' => 'sm',
                                'spacing' => 'sm',
                                'contents' => 
                                array (
                                  0 => 
                                  array (
                                    'type' => 'text',
                                    'text' => 'น้ำหนักก่อนตั้งครรภ์',
                                    'size' => 'sm',
                                    'color' => '#555555',
                                    'wrap' => true,
                                    'flex' => 2,
                                  ),
                                  1 => 
                                  array (
                                    'type' => 'text',
                                    'text' => $user_Pre_weight,
                                    'size' => 'sm',
                                    'color' => '#1DB446',
                                    'align' => 'center',
                                    'wrap' => true,
                                    'flex' => 1,
                                  ),
                                ),
                              ),
                              7 => 
                              array (
                                'type' => 'box',
                                'layout' => 'horizontal',
                                'margin' => 'sm',
                                'spacing' => 'sm',
                                'contents' => 
                                array (
                                  0 => 
                                  array (
                                    'type' => 'text',
                                    'text' => 'น้ำหนักปัจจุบัน (BMI '.$bmi.')',
                                    'size' => 'sm',
                                    'color' => '#555555',
                                    'wrap' => true,
                                    'flex' => 2,
                                  ),
                                  1 => 
                                  array (
                                    'type' => 'text',
                                    'text' => $user_weight,
                                    'size' => 'sm',
                                    'color' => '#1DB446',
                                    'align' => 'center',
                                    'wrap' => true,
                                    'flex' => 1,
                                  ),
                                ),
                              ),
                              8 => 
                              array (
                                'type' => 'box',
                                'layout' => 'horizontal',
                                'margin' => 'md',
                                'spacing' => 'sm',
                                'contents' => 
                                array (
                                  0 => 
                                  array (
                                    'type' => 'text',
                                    'text' => 'อายุครรภ์',
                                    'size' => 'sm',
                                    'color' => '#555555',
                                    'wrap' => true,
                                    'flex' => 2,
                                  ),
                                  1 => 
                                  array (
                                    'type' => 'text',
                                    'text' => $preg_week.' สัปดาห์',
                                    'size' => 'sm',
                                    'color' => '#1DB446',
                                    'align' => 'center',
                                    'wrap' => true,
                                    'flex' => 1,
                                  ),
                                ),
                              ),
                              9 => 
                              array (
                                'type' => 'box',
                                'layout' => 'horizontal',
                                'margin' => 'sm',
                                'spacing' => 'sm',
                                'contents' => 
                                array (
                                  0 => 
                                  array (
                                    'type' => 'text',
                                    'text' => 'เบอร์โทรศัพท์',
                                    'size' => 'sm',
                                    'color' => '#555555',
                                    'wrap' => true,
                                    'flex' => 2,
                                  ),
                                  1 => 
                                  array (
                                    'type' => 'text',
                                    'text' => $phone_number,
                                    'size' => 'sm',
                                    'color' => '#1DB446',
                                    'align' => 'center',
                                    'wrap' => true,
                                    'flex' => 1,
                                  ),
                                ),
                              ),
                              10 => 
                              array (
                                'type' => 'box',
                                'layout' => 'horizontal',
                                'margin' => 'md',
                                'spacing' => 'sm',
                                'contents' => 
                                array (
                                  0 => 
                                  array (
                                    'type' => 'text',
                                    'text' => 'อีเมล์',
                                    'size' => 'sm',
                                    'color' => '#555555',
                                    'wrap' => true,
                                    'flex' => 2,
                                  ),
                                  1 => 
                                  array (
                                    'type' => 'text',
                                    'text' => $email,
                                    'size' => 'sm',
                                    'color' => '#1DB446',
                                    'align' => 'center',
                                    'wrap' => true,
                                    'flex' => 1,
                                  ),
                                ),
                              ),
                              11 => 
                              array (
                                'type' => 'box',
                                'layout' => 'horizontal',
                                'margin' => 'sm',
                                'spacing' => 'sm',
                                'contents' => 
                                array (
                                  0 => 
                                  array (
                                    'type' => 'text',
                                    'text' => 'โรงพยาบาลที่ฝากครรภ์',
                                    'size' => 'sm',
                                    'color' => '#555555',
                                    'wrap' => true,
                                    'flex' => 2,
                                  ),
                                  1 => 
                                  array (
                                    'type' => 'text',
                                    'text' => $hospital_name,
                                    'size' => 'sm',
                                    'color' => '#1DB446',
                                    'align' => 'center',
                                    'wrap' => true,
                                    'flex' => 1,
                                  ),
                                ),
                              ),
                              12 => 
                              array (
                                'type' => 'box',
                                'layout' => 'horizontal',
                                'margin' => 'sm',
                                'spacing' => 'sm',
                                'contents' => 
                                array (
                                  0 => 
                                  array (
                                    'type' => 'text',
                                    'text' => 'แพ้อาหาร',
                                    'size' => 'sm',
                                    'color' => '#555555',
                                    'wrap' => true,
                                    'flex' => 2,
                                  ),
                                  1 => 
                                  array (
                                    'type' => 'text',
                                    'text' => $history_food,
                                    'size' => 'sm',
                                    'color' => '#1DB446',
                                    'align' => 'center',
                                    'wrap' => true,
                                    'flex' => 1,
                                  ),
                                ),
                              ),
                            ),
                          ),
                          'footer' => 
                          array (
                            'type' => 'box',
                            'layout' => 'vertical',
                            'margin' => 'md',
                            'contents' => 
                            array (
                              0 => 
                              array (
                                'type' => 'button',
                                'style' => 'primary',
                                'color' => '#F06292',
                                'action' => 
                                array (
                                   'type' => 'message',
                                   'label' => 'ยืนยัน',
                                   'text' => 'ยืนยันข้อมูล',
                                ),
                              ),
                            ),
                          ),
                        ),
                  ];


   $url = 'https://api.line.me/v2/bot/message/push';
   $data = [
    'to' => $user,
    'messages' => [$textMessageBuilder],
   ];
   $access_token = 'UWrfpYzUUCCy44R4SFvqITsdWn/PeqFuvzLwey51hlRA1+AX/jSyCVUY7V2bPTkuoaDzmp1AY5CfsgFTIinxzxIYViz+chHSXWsxZdQb5AyZu7U67A9f18NQKE/HfGNrZZrwNxWNUwVJf2AszEsCvgdB04t89/1O/w1cDnyilFU=';   
   $post = json_encode($data);
   $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
   $ch = curl_init($url);
   curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  //  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
   $result = curl_exec($ch);
   curl_close($ch);
   echo $result . "\r\n";
 }


  public function info_exercise_diary($replyToken,$user)
    {
               
                $records = tracker::where('user_id',$user)
                               ->whereNull('deleted_at')
                               ->orderBy('created_at', 'DESC')
                               ->take(7)
                               ->get();

                $columnTemplateBuilders = [];

                foreach ($records as $record) {


                    if ($record->exercise == 'ยัง'){
                      $a = 'ไม่ได้ออกกำลังกาย';
                    }elseif($record->exercise == 'NULL'){
                      $a = 'ไม่มีการบันทึก';
                    }else{
                      $a = $record->exercise;
                    }
                    $columnTemplateBuilder =  
                        [
                          // $record->created_at
                              'type' => 'box',
                              'layout' => 'horizontal',
                              'margin' => 'xxl',
                              'spacing' => 'sm',
                              'contents' => 
                              array (
                                0 => 
                                array (
                                  'type' => 'text',
                                  'text' =>  date('d-m-Y', strtotime($record->created_at)),
                                  'size' => 'sm',
                                  'color' => '#555555',
                                  'wrap' => true,
                                ),
                                1 => 
                                array (
                                  'type' => 'text',
                                  'text' => $a,
                                  'size' => 'md',
                                  'color' => '#01579B',
                                  'align' => 'center',
                                  'wrap' => true,
                                  'flex' => 2,
                                ),
                                2 => 
                                array (
                                  'type' => 'button',
                                  'style' => 'primary',
                                  'color' => '#B9F6CA',
                                  'height' => 'sm',
                                  'flex' => 0,
                                  'action' => 
                                  array (
                                   'type' => 'message',
                                   'label' => '✏',
                                   'text' => 'บันทึกออกกำลังกาย:'.date('d-m-Y', strtotime($record->created_at)),
                                  ),
                                ),
                              ),
                            ]
                            ;
                    array_push($columnTemplateBuilders, $columnTemplateBuilder);
                } 

              $c = count($columnTemplateBuilders);
//dd($columnTemplateBuilders);
       for ($i=0; $i < $c ; $i++) { 

        $y[]= $columnTemplateBuilders[$i];

                 $textMessageBuilder = 
                  [
                  'type' => 'flex',
                  'altText' => 'this is a flex message',
                  'contents' => 
                  array (
                    'type' => 'carousel',
                    'contents' => 
                    array (
                      0 => 
                      array (
                        'type' => 'bubble',
                        'styles' => 
                        array (
                          'footer' => 
                          array (
                            'separator' => true,
                          ),
                        ),
                    
                        'header' => 
                        array (
                          'type' => 'box',
                          'layout' => 'vertical',
                          'contents' => 
                          array (
                              0 => 
                          array (
                            'type' => 'text',
                            'text' => 'การออกกำลังกาย',
                            'weight' => 'bold',
                            'color' => '#1B5E20',
                            'size' => 'xl',
                            'wrap' => true,
                          ),
                            1 => 
                            array (
                              'type' => 'text',
                              'text' => 'หากต้องการแก้ไขข้อมูลให้กดที่ ✏',
                              'wrap' => true,
                              'color' => '#aaaaaa',
                              'size' => 'xs',
                            ),
                          ),
                        ),
              
                      'body' => 
                        array (
                          'type' => 'box',
                          'layout' => 'vertical',
                          'contents' => 
                         
                                $y,
                          
                        ),
                      ),
                    ),
                  ),
                  ];


     }
  //dd($y);


   $url = 'https://api.line.me/v2/bot/message/push';
   $data = [
    'to' => $user,
    'messages' => [$textMessageBuilder],
   ];
   $access_token = 'UWrfpYzUUCCy44R4SFvqITsdWn/PeqFuvzLwey51hlRA1+AX/jSyCVUY7V2bPTkuoaDzmp1AY5CfsgFTIinxzxIYViz+chHSXWsxZdQb5AyZu7U67A9f18NQKE/HfGNrZZrwNxWNUwVJf2AszEsCvgdB04t89/1O/w1cDnyilFU=';   
   $post = json_encode($data);
   $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
   $ch = curl_init($url);
   curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  //  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
   $result = curl_exec($ch);
   curl_close($ch);
   echo $result . "\r\n";
 }


 public function info_vitamin_diary($replyToken,$user)
    {
               
                $records = tracker::where('user_id',$user)
                               ->whereNull('deleted_at')
                               ->orderBy('created_at', 'DESC')
                               ->take(7)
                               ->get();

                $columnTemplateBuilders = [];

                foreach ($records as $record) {

                      if ($record->vitamin == '0'){
                        $a = 'ไม่ได้ทาน';
                      }elseif ($record->vitamin  == '1'){
                        $a = 'ทาน';
                      }else{
                        $a = 'ไม่มีการบันทึก';
                      }
                    $columnTemplateBuilder =  
                        [
                          // $record->created_at
                              'type' => 'box',
                              'layout' => 'horizontal',
                              'margin' => 'xxl',
                              'spacing' => 'sm',
                              'contents' => 
                              array (
                                0 => 
                                array (
                                  'type' => 'text',
                                  'text' =>  date('d-m-Y', strtotime($record->created_at)),
                                  'size' => 'sm',
                                  'color' => '#555555',
                                  'wrap' => true,
                                ),
                                1 => 
                                array (
                                  'type' => 'text',
                                  'text' => $a,
                                  'size' => 'md',
                                  'color' => '#FFA000',
                                  'align' => 'center',
                                  'wrap' => true,
                                  'flex' => 2,
                                ),
                                2 => 
                                array (
                                  'type' => 'button',
                                  'style' => 'primary',
                                  'color' => '#FFECB3',
                                  'height' => 'sm',
                                  'flex' => 0,
                                  'action' => 
                                  array (
                                   'type' => 'message',
                                   'label' => '✏',
                                   'text' => 'บันทึกวิตามิน:'.date('d-m-Y', strtotime($record->created_at)),
                                  ),
                                ),
                              ),
                            ]
                            ;
                    array_push($columnTemplateBuilders, $columnTemplateBuilder);
                } 

              $c = count($columnTemplateBuilders);
//dd($columnTemplateBuilders);
       for ($i=0; $i < $c ; $i++) { 

        $y[]= $columnTemplateBuilders[$i];

                 $textMessageBuilder = 
                  [
                  'type' => 'flex',
                  'altText' => 'this is a flex message',
                  'contents' => 
                  array (
                    'type' => 'carousel',
                    'contents' => 
                    array (
                      0 => 
                      array (
                        'type' => 'bubble',
                        'styles' => 
                        array (
                          'footer' => 
                          array (
                            'separator' => true,
                          ),
                        ),
                    
                        'header' => 
                        array (
                          'type' => 'box',
                          'layout' => 'vertical',
                          'contents' => 
                          array (
                              0 => 
                          array (
                            'type' => 'text',
                            'text' => 'การทานวิตามิน',
                            'weight' => 'bold',
                            'color' => '#EF6C00',
                            'size' => 'xl',
                            'wrap' => true,
                          ),
                            1 => 
                            array (
                              'type' => 'text',
                              'text' => 'หากต้องการแก้ไขข้อมูลให้กดที่ ✏',
                              'wrap' => true,
                              'color' => '#aaaaaa',
                              'size' => 'xs',
                            ),
                          ),
                        ),
              
                      'body' => 
                        array (
                          'type' => 'box',
                          'layout' => 'vertical',
                          'contents' => 
                         
                                $y,
                          
                        ),
                      ),
                    ),
                  ),
                  ];


     }
  //dd($y);


   $url = 'https://api.line.me/v2/bot/message/push';
   $data = [
    'to' => $user,
    'messages' => [$textMessageBuilder],
   ];
   $access_token = 'UWrfpYzUUCCy44R4SFvqITsdWn/PeqFuvzLwey51hlRA1+AX/jSyCVUY7V2bPTkuoaDzmp1AY5CfsgFTIinxzxIYViz+chHSXWsxZdQb5AyZu7U67A9f18NQKE/HfGNrZZrwNxWNUwVJf2AszEsCvgdB04t89/1O/w1cDnyilFU=';   
   $post = json_encode($data);
   $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
   $ch = curl_init($url);
   curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  //  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
   $result = curl_exec($ch);
   curl_close($ch);
   echo $result . "\r\n";
 }
  public function info_food_diary($replyToken,$user)
    {
               
                $records = tracker::where('user_id',$user)
                               ->whereNull('deleted_at')
                               ->orderBy('created_at', 'DESC')
                               ->take(7)
                               ->get();

                $columnTemplateBuilders = [];
                $columnTemplateBuilders1 = [];
                $columnTemplateBuilders2 = [];
                $columnTemplateBuilders3 = [];
                $columnTemplateBuilders4 = [];

                foreach ($records as $record) {

                        if ($record->breakfast == 'NULL'){
                          $breakfast = 'ไม่มีการบันทึก';
                        }else{
                          $breakfast = $record->breakfast ;
                        }
                        if ($record->lunch == 'NULL'){
                          $lunch = 'ไม่มีการบันทึก';
                        }else{
                          $lunch = $record->lunch;
                        }
                        if ($record->dinner == 'NULL'){
                          $din = 'ไม่มีการบันทึก';
                        }else{
                          $din = $record->dinner;
                        }
                        if ($record->dessert_lu == 'NULL'){
                          $de_lu = 'ไม่มีการบันทึก';
                        }else{
                          $de_lu = $record->dessert_lu;
                        }
                        if ($record->dessert_din == 'NULL'){
                          $de_din = 'ไม่มีการบันทึก';
                        }else{
                          $de_din= $record->dessert_din;
                        }
                    $columnTemplateBuilder =  
                        [
                          // $record->created_at
                              'type' => 'box',
                              'layout' => 'horizontal',
                              'margin' => 'xxl',
                              'spacing' => 'sm',
                              'contents' => 
                              array (
                                0 => 
                                array (
                                  'type' => 'text',
                                  'text' =>  date('d-m-Y', strtotime($record->created_at)),
                                  'size' => 'sm',
                                  'color' => '#555555',
                                  'wrap' => true,
                                ),
                                1 => 
                                array (
                                  'type' => 'text',
                                  'text' => $breakfast,
                                  'size' => 'md',
                                  'color' => '#5F6A6A',
                                  'align' => 'center',
                                  'wrap' => true,
                                  'flex' => 2,
                                ),
                                2 => 
                                array (
                                  'type' => 'button',
                                  'style' => 'primary',
                                  'color' => '#FFECB3',
                                  'height' => 'sm',
                                  'flex' => 0,
                                  'action' => 
                                  array (
                                   'type' => 'message',
                                   'label' => '✏',
                                   'text' => 'บันทึกมื้อเช้า:'.date('d-m-Y', strtotime($record->created_at)),
                                  ),
                                ),
                              ),
                            ];
                    $columnTemplateBuilder1 =  
                        [
                          // $record->created_at
                              'type' => 'box',
                              'layout' => 'horizontal',
                              'margin' => 'xxl',
                              'spacing' => 'sm',
                              'contents' => 
                              array (
                                0 => 
                                array (
                                  'type' => 'text',
                                  'text' =>  date('d-m-Y', strtotime($record->created_at)),
                                  'size' => 'sm',
                                  'color' => '#555555',
                                  'wrap' => true,
                                ),
                                1 => 
                                array (
                                  'type' => 'text',
                                  'text' => $de_lu,
                                  'size' => 'md',
                                  'color' => '#5F6A6A',
                                  'align' => 'center',
                                  'wrap' => true,
                                  'flex' => 2,
                                ),
                                2 => 
                                array (
                                  'type' => 'button',
                                  'style' => 'primary',
                                  'color' => '#FFECB3',
                                  'height' => 'sm',
                                  'flex' => 0,
                                  'action' => 
                                  array (
                                   'type' => 'message',
                                   'label' => '✏',
                                   'text' => 'บันทึกมื้อว่างกลางวัน:'.date('d-m-Y', strtotime($record->created_at)),
                                  ),
                                ),
                              ),
                            ]
                            ;
                    $columnTemplateBuilder2 =  
                        [
                          // $record->created_at
                              'type' => 'box',
                              'layout' => 'horizontal',
                              'margin' => 'xxl',
                              'spacing' => 'sm',
                              'contents' => 
                              array (
                                0 => 
                                array (
                                  'type' => 'text',
                                  'text' =>  date('d-m-Y', strtotime($record->created_at)),
                                  'size' => 'sm',
                                  'color' => '#555555',
                                  'wrap' => true,
                                ),
                                1 => 
                                array (
                                  'type' => 'text',
                                  'text' => $lunch,
                                  'size' => 'md',
                                  'color' => '#5F6A6A',
                                  'align' => 'center',
                                  'wrap' => true,
                                  'flex' => 2,
                                ),
                                2 => 
                                array (
                                  'type' => 'button',
                                  'style' => 'primary',
                                  'color' => '#FFECB3',
                                  'height' => 'sm',
                                  'flex' => 0,
                                  'action' => 
                                  array (
                                   'type' => 'message',
                                   'label' => '✏',
                                   'text' => 'บันทึกมื้อกลางวัน:'.date('d-m-Y', strtotime($record->created_at)),
                                  ),
                                ),
                              ),
                            ];
                    $columnTemplateBuilder3 =  
                        [
                          // $record->created_at
                              'type' => 'box',
                              'layout' => 'horizontal',
                              'margin' => 'xxl',
                              'spacing' => 'sm',
                              'contents' => 
                              array (
                                0 => 
                                array (
                                  'type' => 'text',
                                  'text' =>  date('d-m-Y', strtotime($record->created_at)),
                                  'size' => 'sm',
                                  'color' => '#555555',
                                  'wrap' => true,
                                ),
                                1 => 
                                array (
                                  'type' => 'text',
                                  'text' => $de_din,
                                  'size' => 'md',
                                  'color' => '#5F6A6A',
                                  'align' => 'center',
                                  'wrap' => true,
                                  'flex' => 2,
                                ),
                                2 => 
                                array (
                                  'type' => 'button',
                                  'style' => 'primary',
                                  'color' => '#FFECB3',
                                  'height' => 'sm',
                                  'flex' => 0,
                                  'action' => 
                                  array (
                                   'type' => 'message',
                                   'label' => '✏',
                                   'text' => 'บันทึกมื้อว่างเย็น:'.date('d-m-Y', strtotime($record->created_at)),
                                  ),
                                ),
                              ),
                            ];
                    $columnTemplateBuilder4 =  
                        [
                          // $record->created_at
                              'type' => 'box',
                              'layout' => 'horizontal',
                              'margin' => 'xxl',
                              'spacing' => 'sm',
                              'contents' => 
                              array (
                                0 => 
                                array (
                                  'type' => 'text',
                                  'text' =>  date('d-m-Y', strtotime($record->created_at)),
                                  'size' => 'sm',
                                  'color' => '#555555',
                                  'wrap' => true,
                                ),
                                1 => 
                                array (
                                  'type' => 'text',
                                  'text' => $din,
                                  'size' => 'md',
                                  'color' => '#5F6A6A',
                                  'align' => 'center',
                                  'wrap' => true,
                                  'flex' => 2,
                                ),
                                2 => 
                                array (
                                  'type' => 'button',
                                  'style' => 'primary',
                                  'color' => '#FFECB3',
                                  'height' => 'sm',
                                  'flex' => 0,
                                  'action' => 
                                  array (
                                   'type' => 'message',
                                   'label' => '✏',
                                   'text' => 'บันทึกมื้อเย็น:'.date('d-m-Y', strtotime($record->created_at)),
                                  ),
                                ),
                              ),
                            ];

                    array_push($columnTemplateBuilders, $columnTemplateBuilder);
                    array_push($columnTemplateBuilders1, $columnTemplateBuilder1);
                    array_push($columnTemplateBuilders2, $columnTemplateBuilder2);
                    array_push($columnTemplateBuilders3, $columnTemplateBuilder3);
                    array_push($columnTemplateBuilders4, $columnTemplateBuilder4);
                } 

              $c = count($columnTemplateBuilders);
//dd($columnTemplateBuilders);
       for ($i=0; $i < $c ; $i++) { 

        $y[]= $columnTemplateBuilders[$i];
        $y1[]= $columnTemplateBuilders1[$i];
        $y2[]= $columnTemplateBuilders2[$i];
        $y3[]= $columnTemplateBuilders3[$i];
        $y4[]= $columnTemplateBuilders4[$i];

                 $textMessageBuilder = 
                  [
                  'type' => 'flex',
                  'altText' => 'this is a flex message',
                  'contents' => 
                  array (
                    'type' => 'carousel',
                    'contents' => 
                    array (
                              // 0 => 
                              //   array (
                              //             'type' => 'bubble',
                              //             'styles' => 
                              //             array (
                              //               'footer' => 
                              //               array (
                              //                 'separator' => true,
                              //               ),
                              //             ),
                                      
                              //               'header' => 
                              //               array (
                              //                 'type' => 'box',
                              //                 'layout' => 'vertical',
                              //                 'contents' => 
                              //                 array (
                              //                     0 => 
                              //                 array (
                              //                   'type' => 'text',
                              //                   'text' => 'บันทึกมื้อเช้า',
                              //                   'weight' => 'bold',
                              //                   'color' => '#F1C40F',
                              //                   'size' => 'xl',
                              //                   'wrap' => true,
                              //                 ),
                              //                 1 => 
                              //                 array (
                              //                   'type' => 'text',
                              //                   'text' => 'หากต้องการแก้ไขข้อมูลให้กดที่ ✏',
                              //                   'wrap' => true,
                              //                   'color' => '#aaaaaa',
                              //                   'size' => 'xs',
                              //                 ),
                              //               ),
                              //             ),
                                
                              //           'body' => 
                              //             array (
                              //               'type' => 'box',
                              //               'layout' => 'vertical',
                              //               'contents' => 
                              //                     $y,
                              //             ),
                              // ),
                              // 1 => 
                              //   array (
                              //             'type' => 'bubble',
                              //             'styles' => 
                              //             array (
                              //               'footer' => 
                              //               array (
                              //                 'separator' => true,
                              //               ),
                              //             ),
                                      
                              //               'header' => 
                              //               array (
                              //                 'type' => 'box',
                              //                 'layout' => 'vertical',
                              //                 'contents' => 
                              //                 array (
                              //                     0 => 
                              //                 array (
                              //                   'type' => 'text',
                              //                   'text' => 'บันทึกมื้อว่าง',
                              //                   'weight' => 'bold',
                              //                   'color' => '#616A6B',
                              //                   'size' => 'xl',
                              //                   'wrap' => true,
                              //                 ),
                              //                 1 => 
                              //                 array (
                              //                   'type' => 'text',
                              //                   'text' => 'หากต้องการแก้ไขข้อมูลให้กดที่ ✏',
                              //                   'wrap' => true,
                              //                   'color' => '#aaaaaa',
                              //                   'size' => 'xs',
                              //                 ),
                              //               ),
                              //             ),
                                
                              //           'body' => 
                              //             array (
                              //               'type' => 'box',
                              //               'layout' => 'vertical',
                              //               'contents' => 
                              //                     $y1,
                              //             ),
                              // ),
                              // 2 => 
                              //   array (
                              //             'type' => 'bubble',
                              //             'styles' => 
                              //             array (
                              //               'footer' => 
                              //               array (
                              //                 'separator' => true,
                              //               ),
                              //             ),
                                      
                              //               'header' => 
                              //               array (
                              //                 'type' => 'box',
                              //                 'layout' => 'vertical',
                              //                 'contents' => 
                              //                 array (
                              //                     0 => 
                              //                 array (
                              //                   'type' => 'text',
                              //                   'text' => 'บันทึกมื้อกลางวัน',
                              //                   'weight' => 'bold',
                              //                   'color' => '#D98880',
                              //                   'size' => 'xl',
                              //                   'wrap' => true,
                              //                 ),
                              //                 1 => 
                              //                 array (
                              //                   'type' => 'text',
                              //                   'text' => 'หากต้องการแก้ไขข้อมูลให้กดที่ ✏',
                              //                   'wrap' => true,
                              //                   'color' => '#aaaaaa',
                              //                   'size' => 'xs',
                              //                 ),
                              //               ),
                              //             ),
                                
                              //           'body' => 
                              //             array (
                              //               'type' => 'box',
                              //               'layout' => 'vertical',
                              //               'contents' => 
                              //                     $y2,
                              //             ),
                              // ),
                              0 => 
                                array (
                                          'type' => 'bubble',
                                          'styles' => 
                                          array (
                                            'footer' => 
                                            array (
                                              'separator' => true,
                                            ),
                                          ),
                                      
                                            'header' => 
                                            array (
                                              'type' => 'box',
                                              'layout' => 'vertical',
                                              'contents' => 
                                              array (
                                                  0 => 
                                              array (
                                                'type' => 'text',
                                                'text' => 'บันทึกมื้อว่าง',
                                                'weight' => 'bold',
                                                'color' => '#616A6B',
                                                'size' => 'xl',
                                                'wrap' => true,
                                              ),
                                              1 => 
                                              array (
                                                'type' => 'text',
                                                'text' => 'หากต้องการแก้ไขข้อมูลให้กดที่ ✏',
                                                'wrap' => true,
                                                'color' => '#aaaaaa',
                                                'size' => 'xs',
                                              ),
                                            ),
                                          ),
                                
                                        'body' => 
                                          array (
                                            'type' => 'box',
                                            'layout' => 'vertical',
                                            'contents' => 
                                                  $y3,
                                          ),
                              ),
                              1 => 
                                array (
                                          'type' => 'bubble',
                                          'styles' => 
                                          array (
                                            'footer' => 
                                            array (
                                              'separator' => true,
                                            ),
                                          ),
                                      
                                            'header' => 
                                            array (
                                              'type' => 'box',
                                              'layout' => 'vertical',
                                              'contents' => 
                                              array (
                                                  0 => 
                                              array (
                                                'type' => 'text',
                                                'text' => 'บันทึกอาหาร',
                                                'weight' => 'bold',
                                                'color' => '#2980B9',
                                                'size' => 'xl',
                                                'wrap' => true,
                                              ),
                                              1 => 
                                              array (
                                                'type' => 'text',
                                                'text' => 'หากต้องการแก้ไขข้อมูลให้กดที่ ✏',
                                                'wrap' => true,
                                                'color' => '#aaaaaa',
                                                'size' => 'xs',
                                              ),
                                            ),
                                          ),
                                
                                        'body' => 
                                          array (
                                            'type' => 'box',
                                            'layout' => 'vertical',
                                            'contents' => 
                                                  $y4,
                                          ),
                              ),
                          ),
                  ),
                  ];


     }
  //dd($y);


   $url = 'https://api.line.me/v2/bot/message/push';
   $data = [
    'to' => $user,
    'messages' => [$textMessageBuilder],
   ];
   $access_token = 'UWrfpYzUUCCy44R4SFvqITsdWn/PeqFuvzLwey51hlRA1+AX/jSyCVUY7V2bPTkuoaDzmp1AY5CfsgFTIinxzxIYViz+chHSXWsxZdQb5AyZu7U67A9f18NQKE/HfGNrZZrwNxWNUwVJf2AszEsCvgdB04t89/1O/w1cDnyilFU=';   
   $post = json_encode($data);
   $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
   $ch = curl_init($url);
   curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  //  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
   $result = curl_exec($ch);
   curl_close($ch);
   echo $result . "\r\n";
 }
 public function info_weight_diary($replyToken,$user)
    {
     
              $records = RecordOfPregnancy::select('preg_week','preg_weight')
                     ->where('user_id', $user)
                     ->whereNull('deleted_at')
                     ->orderBy('preg_week', 'desc')
                     ->distinct()
                     ->take(7)
                     ->get();

            
                $columnTemplateBuilders = [];

                foreach ($records as $record) {

              
                    $columnTemplateBuilder =  
                        [
                          // $record->created_at
                              'type' => 'box',
                              'layout' => 'horizontal',
                              'margin' => 'xxl',
                              'spacing' => 'sm',
                              'contents' => 
                              array (
                                0 => 
                                array (
                                  'type' => 'text',
                                  'text' => $record->preg_week.' สัปดาห์',
                                  'size' => 'sm',
                                  'color' => '#555555',
                                  'wrap' => true,
                                ),
                                1 => 
                                array (
                                  'type' => 'text',
                                  'text' => $record->preg_weight,
                                  'size' => 'md',
                                  'color' => '#2E7D32',
                                  'align' => 'center',
                                  'wrap' => true,
                                  'flex' => 2,
                                ),
                                2 => 
                                array (
                                  'type' => 'button',
                                  'style' => 'primary',
                                  'color' => '#B2EBF2',
                                  'height' => 'sm',
                                  'flex' => 0,
                                  'action' => 
                                  array (
                                   'type' => 'message',
                                   'label' => '✏',
                                   'text' => 'บันทึกน้ำหนัก:'.$record->preg_week,
                                  ),
                                ),
                              ),
                            ]
                            ;
                    array_push($columnTemplateBuilders, $columnTemplateBuilder);
                } 

              $c = count($columnTemplateBuilders);
//dd($columnTemplateBuilders);
       for ($i=0; $i < $c ; $i++) { 

        $y[]= $columnTemplateBuilders[$i];

                 $textMessageBuilder = 
                  [
                  'type' => 'flex',
                  'altText' => 'this is a flex message',
                  'contents' => 
                  array (
                    'type' => 'carousel',
                    'contents' => 
                    array (
                      0 => 
                      array (
                        'type' => 'bubble',
                        'styles' => 
                        array (
                          'footer' => 
                          array (
                            'separator' => true,
                          ),
                        ),
                    
                        'header' => 
                        array (
                          'type' => 'box',
                          'layout' => 'vertical',
                          'contents' => 
                          array (
                              0 => 
                          array (
                            'type' => 'text',
                            'text' => 'น้ำหนักระหว่างตั้งครรภ์',
                            'weight' => 'bold',
                            'color' => '#039BE5',
                            'size' => 'xl',
                            'wrap' => true,
                          ),
                            1 => 
                            array (
                               'type' => 'box',
                                'layout' => 'horizontal',
                                'margin' => 'sm',
                                'spacing' => 'sm',
                                'contents' =>  array (
                                          0 => 
                                          array (
                                            'type' => 'text',
                                            'text' => 'สัปดาห์',
                                            'size' => 'xs',
                                            'color' => '#aaaaaa',
                                            'align' => 'center',
                                            'wrap' => true,
                                            'flex' => 1,
                                          ),
                                          1 => 
                                          array (
                                            'type' => 'separator',
                                          ),
                                          2 => 
                                          array (
                                            'type' => 'text',
                                            'text' => 'น้ำหนัก',
                                            'size' => 'xs',
                                            'color' => '#aaaaaa',
                                            'align' => 'center',
                                            'wrap' => true,
                                            'flex' => 1,
                                          ),
                                          3 => 
                                          array (
                                            'type' => 'separator',
                                          ),
                                          4 => 
                                          array (
                                            'type' => 'text',
                                            'text' => 'แก้ไข',
                                            'size' => 'xs',
                                            'color' => '#aaaaaa',
                                            'align' => 'center',
                                            'wrap' => true,
                                            'flex' => 1,
                                          ),
                                        ),
                            ),
                          ),
                        ),
              
                      'body' => 
                        array (
                          'type' => 'box',
                          'layout' => 'vertical',
                          'contents' => 
                         
                                $y,
                          
                        ),
                      ),
                    ),
                  ),
                  ];
     }
   $url = 'https://api.line.me/v2/bot/message/push';
   $data = [
    'to' => $user,
    'messages' => [$textMessageBuilder],
   ];

  
   $access_token = 'UWrfpYzUUCCy44R4SFvqITsdWn/PeqFuvzLwey51hlRA1+AX/jSyCVUY7V2bPTkuoaDzmp1AY5CfsgFTIinxzxIYViz+chHSXWsxZdQb5AyZu7U67A9f18NQKE/HfGNrZZrwNxWNUwVJf2AszEsCvgdB04t89/1O/w1cDnyilFU=';
   
   $post = json_encode($data);
   $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
   $ch = curl_init($url);
   curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  //  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
   $result = curl_exec($ch);
   curl_close($ch);
   echo $result . "\r\n";
 }






 public function quick_reply($replyToken,$user){
        $httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
        $bot = new LINEBot($httpClient, array('channelSecret' => LINE_MESSAGE_CHANNEL_SECRET));
                
      // การใช้งาน postback action
      $graphbs =  new UriTemplateActionBuilder(
        'กราฟน้ำตาล', // ข้อความแสดงในปุ่ม
        'https://service.foodieat.in.th/remi/graph_sugar_blood/'.$user
      );
      // การใช้งาน message action
      $fetal_movement =  new UriTemplateActionBuilder(
        'นับลูกดิ้น', // ข้อความแสดงในปุ่ม
        // 'https://liff.line.me/1656991660-kq47bAMD'
        'https://service.foodieat.in.th/remi/babykicks/'.$user
        
      );
      // การใช้งาน datetime picker action
      $blood_sugar = new UriTemplateActionBuilder(
        'บันทึกน้ำตาล', // ข้อความแสดงในปุ่ม
        'https://liff.line.me/1656991660-v073Nlgm/'
      );
      $birth_date = new UriTemplateActionBuilder(
        'แจ้งคลอด', // ข้อความแสดงในปุ่ม
        'https://liff.line.me/1656991660-4LbgJrjy/'
      );

      $weight = new UriTemplateActionBuilder(
        'บันทึกอาหาร', // ข้อความแสดงในปุ่ม
        // 'https://service.foodieat.in.th/remi/record_weight/'.$user
        'https://service.foodieat.in.th/remi/record_diary/'.$user
        
      );

      // $birth_date = new UriTemplateActionBuilder(
      //   'แจ้งคลอด', // ข้อความแสดงในปุ่ม
      //   'https://liff.line.me/1656991660-4LbgJrjy/'
      // );
      // การสร้างปุ่ม quick reply
      $quickReply = new QuickReplyMessageBuilder(
          array(
              new QuickReplyButtonBuilder($blood_sugar),
              new QuickReplyButtonBuilder($graphbs),
              new QuickReplyButtonBuilder($fetal_movement),
              new QuickReplyButtonBuilder($weight),
              new QuickReplyButtonBuilder($birth_date),
          )
      );
      $textReplyMessage = "บันทึกประจำวัน";
      $replyData = new TextMessageBuilder($textReplyMessage,$quickReply);                 
      $response = $bot->replyMessage($replyToken,$replyData); 
 

}


}
