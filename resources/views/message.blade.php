@extends('layouts.app')

@section('script')
<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/serial.js"></script>
<script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>

<link href="https://fonts.googleapis.com/css?family=Kanit" rel="stylesheet">
<link rel="stylesheet" href="{{URL::asset('css/stylecss_test.css')}}">

<script type="text/javascript">
    



var bmi_under = '[{"week":1,"fromValue":0,"toValue":0 },{"week":2,"fromValue":0.07,"toValue":0.105 },'+
      '{"week":3,"fromValue":0.14,"toValue":0.21 },{"week":4,"fromValue":0.21,"toValue":0.315 },'+
      '{"week":5,"fromValue":0.28,"toValue":0.42 },{"week":6,"fromValue":0.35,"toValue":0.525 },'+
      '{"week":7,"fromValue":0.42,"toValue":0.63 },{"week":8,"fromValue":0.49,"toValue":0.735 },'+
      '{"week":9,"fromValue":0.56,"toValue":0.84 },{"week":10,"fromValue":0.63,"toValue":0.945 },'+
      '{"week":11,"fromValue":0.7,"toValue":1.05 },{"week":12,"fromValue":1.06,"toValue":1.565 },'+
      '{"week":13,"fromValue":1.42,"toValue":2.08 },{"week":14,"fromValue":1.78,"toValue":2.595 },'+
      '{"week":15,"fromValue":2.14,"toValue":3.11 },{"week":16,"fromValue":2.5,"toValue":3.625 },'+
      '{"week":17,"fromValue":2.86,"toValue":4.14 },{"week":18,"fromValue":3.22,"toValue":4.655 },'+
      '{"week":19,"fromValue":3.58,"toValue":5.17 },{"week":20,"fromValue":4.06,"toValue":5.85 },'+
      '{"week":21,"fromValue":4.54,"toValue":6.53 },{"week":22,"fromValue":5.02,"toValue":7.21 },'+
      '{"week":23,"fromValue":5.5,"toValue":7.89 },{"week":24,"fromValue":5.98,"toValue":8.57 },'+
      '{"week":25,"fromValue":6.46,"toValue":9.25 },{"week":26,"fromValue":6.94,"toValue":9.93 },'+
      '{"week":27,"fromValue":7.42,"toValue":10.61 },{"week":28,"fromValue":7.9,"toValue":11.29 },'+
      '{"week":29,"fromValue":8.38,"toValue":11.97 },{"week":30,"fromValue":8.74,"toValue":12.485 },'+
      '{"week":31,"fromValue":9.1,"toValue":13 },{"week":32,"fromValue":9.46,"toValue":13.515 },'+
      '{"week":33,"fromValue":9.82,"toValue":14.03 },{"week":34,"fromValue":10.18,"toValue":14.545 },'+
      '{"week":35,"fromValue":10.54,"toValue":15.06 },{"week":36,"fromValue":10.89,"toValue":15.58 },'+
      '{"week":37,"fromValue":11.24,"toValue":16.1 },{"week":38,"fromValue":11.62,"toValue":16.62 },'+
      '{"week":39,"fromValue":12,"toValue":17.14 },{"week":40 }]';

var bmi_normal = '[{"week":1,"fromValue":0,"toValue":0},{"week":2,"fromValue":0.065,"toValue":0.09 },'+
        '{"week":3,"fromValue":0.13,"toValue":0.18 },{"week":4,"fromValue":0.195,"toValue":0.27 },'+
        '{"week":5,"fromValue":0.26,"toValue":0.36 },{"week":6,"fromValue":0.325,"toValue":0.45},'+
        '{"week":7,"fromValue":0.39,"toValue":0.54 },{"week":8,"fromValue":0.455,"toValue":0.63 },'+
        '{"week":9,"fromValue":0.52,"toValue":0.72 },{"week":10,"fromValue":0.585,"toValue":0.81 },'+
        '{"week":11,"fromValue":0.65,"toValue":0.9 },{"week":12,"fromValue":0.97,"toValue":1.35 },'+
        '{"week":13,"fromValue":1.29,"toValue":1.8 },{"week":14,"fromValue":1.61,"toValue":2.25 },'+
        '{"week":15,"fromValue":1.93,"toValue":2.7 },{"week":16,"fromValue":2.25,"toValue":3.15 },'+
        '{"week":17,"fromValue":2.57,"toValue":3.6 },{"week":18,"fromValue":2.89,"toValue":4.1  },'+
        '{"week":19,"fromValue":3.21,"toValue":4.60 },{"week":20,"fromValue":3.635,"toValue":5.15 },'+
        '{"week":21,"fromValue":4.06,"toValue":5.7 },{"week":22,"fromValue":4.485,"toValue":6.3 },'+
        '{"week":23,"fromValue":4.91,"toValue":6.9 },{"week":24,"fromValue":5.335,"toValue":7.5 },'+
        '{"week":25,"fromValue":5.76,"toValue":8.1 },{"week":26,"fromValue":6.185,"toValue":8.7 },'+
        '{"week":27,"fromValue":6.61,"toValue":9.3 },{"week":28,"fromValue":7.035,"toValue":9.9},'+
        '{"week":29,"fromValue":7.46,"toValue":10.5 },{"week":30,"fromValue":7.785,"toValue":10.95 },'+
        '{"week":31,"fromValue":8.11,"toValue":11.4 },{"week":32,"fromValue":8.435,"toValue":11.85 },'+
        '{"week":33,"fromValue":8.76,"toValue":12.3 },{"week":34,"fromValue":8.76,"toValue":12.75 },'+
        '{"week":35,"fromValue":9.41,"toValue":13.2 },{"week":36,"fromValue":9.735,"toValue":13.65 },'+
        '{"week":37,"fromValue":10.06,"toValue":14.1 },{"week":38,"fromValue":10.385,"toValue":14.55},'+
        '{"week":39,"fromValue":10.71,"toValue":15 },{"week":40,"fromValue":10.71,"toValue":15 }]';
    
var bmi_over = '[{"week":1,"fromValue":0,"toValue":0},{"week":2,"fromValue":0.04,"toValue":0.065},'+
        '{"week":3,"fromValue":0.08,"toValue":0.13 },{"week":4,"fromValue":0.12,"toValue":0.195 },'+
        '{"week":5,"fromValue":0.16,"toValue":0.26 },{"week":6,"fromValue":0.2,"toValue":0.325 },'+
        '{"week":7,"fromValue":0.24,"toValue":0.39 },{"week":8,"fromValue":0.28,"toValue":0.4 },'+
        '{"week":9,"fromValue":0.32,"toValue":0.41 },{"week":10,"fromValue":0.36,"toValue":0.465 },'+
        '{"week":11,"fromValue":0.4,"toValue":0.52 },{"week":12,"fromValue":0.595,"toValue":0.845 },'+
        '{"week":13,"fromValue":0.79,"toValue":1.17 },{"week":14,"fromValue":0.985,"toValue":1.495 },'+
        '{"week":15,"fromValue":1.18,"toValue":1.82 },{"week":16,"fromValue":1.375,"toValue":2.145 },'+
        '{"week":17,"fromValue":1.57,"toValue":2.47 },{"week":18,"fromValue":1.765,"toValue":2.795 },'+
        '{"week":19,"fromValue":1.96,"toValue":3.12 },{"week":20,"fromValue":2.21,"toValue":3.55 },'+
        '{"week":21,"fromValue":2.46,"toValue":3.98 },{"week":22,"fromValue":2.71,"toValue":4.41 },'+
        '{"week":23,"fromValue":2.96,"toValue":4.84 },{"week":24,"fromValue":3.21,"toValue":5.27 },'+
        '{"week":25,"fromValue":3.46,"toValue":5.7 },{"week":26,"fromValue":3.71,"toValue":6.12 },'+
        '{"week":27,"fromValue":3.96,"toValue":6.56 },{"week":28,"fromValue":4.21,"toValue":6.99 },'+
        '{"week":29,"fromValue":4.46,"toValue":7.42 },{"week":30,"fromValue":4.655,"toValue":7.745 },'+
        '{"week":31,"fromValue":4.85,"toValue":8.07 },{"week":32,"fromValue":5.045,"toValue":8.395 },'+
        '{"week":33,"fromValue":5.24,"toValue":8.72 },{"week":34,"fromValue":5.435,"toValue":9.05 },'+
        '{"week":35,"fromValue":5.63,"toValue":9.38 },{"week":36,"fromValue":5.825,"toValue":9.71 },'+
        '{"week":37,"fromValue":6.02,"toValue":10.04 },{"week":38,"fromValue":6.22,"toValue":10.37 },'+
        '{"week":39,"fromValue":6.42,"toValue":10.7 },{"week":40,"fromValue":6.42,"toValue":10.7 }]';
          

      var bmi = {{$bmi}};

      if(this.bmi < 18.5){
          var bmis = JSON.parse(bmi_under);
      }
      if(this.bmi > 18.5 && this.bmi < 24.9){
        var bmis = JSON.parse(bmi_normal);
      }
      if(this.bmi > 25){
        var bmis = JSON.parse(bmi_over);
      }
   @foreach ($record1 as $records1)
       var preg_week = <?php echo $preg_week; ?>;
       var all_weight = <?php echo $preg_weight; ?>;
       var weight_pregnancy = {{ $records1->user_Pre_weight}};
      
  
         
        for(i=0; i<bmis.length ; i++){
          var b = bmis[i];
        
          for(var w in this.all_weight){
            
            if(b.week == this.preg_week[w]  ){
              // b.value = Math.abs(this.all_weight[w]-this.weight_pregnancy);
              b.value = this.all_weight[w]-this.weight_pregnancy;
              b.value = b.value.toFixed(2);
            }
          } 
       }
    
       @endforeach
     // console.log(JSON.stringify(bmis));       
var chart = AmCharts.makeChart("chartdiv", {
    "type": "serial",
    "theme": "light",
    "autoMarginOffset":20,
    "marginRight":80,
    "dataProvider":  bmis,
    // "valueAxes": [{
    //     "axisAlpha": 0,
    //     "position": "left",
    //      "title": "สัดส่วนน้ำหนัก"

    // }],
  
    "graphs": [{
        "id": "fromGraph",
        "lineAlpha": 0,
        "showBalloon": false,
        "valueField": "fromValue",
        "fillAlphas": 0
    }, {
        "fillAlphas": 0.2,
        "fillToGraph": "fromGraph",
        "lineAlpha": 0,
        "showBalloon": false,
        "valueField": "toValue"
    }, {
        "valueField": "value",
        "balloonText":"<div style='margin:5px; text-align:left'><span style='font-size:18px'>Value:[[value]]</span></div>",
        "fillAlphas": 0,
        "bullet": "round",
        "bulletSize": 8,

    }],
    "chartCursor": {
        "fullWidth": true,
        "cursorAlpha": 0.05,
        "valueLineEnabled":true,
        "valueLineAlpha":0.5,
        "valueLineBalloonEnabled":true
    },
    "dataDateFormat": "YYYY-MM-DD",
    "categoryField": "week",
    "chartScrollbar":{


    },
        "allLabels": [{
              "text": "สัดส่วนน้ำหนัก (กิโลกรัม)",
              "bold": false,
              "x": 10,
              "y": "50%",
              "rotation": 270,
              "width": "100%",
              "align": "middle"
            },{
              "text": "อายุครรภ์ (สัปดาห์)",
              "bold": false,
              "x": '50%',
              "y":480,
              "rotation": 0,
              "width": "100%",
              "align": "middle"
            }],

    "export": {
        "enabled": true
    }
});

// chart.addListener("dataUpdated", zoomChart);

// function zoomChart(){
//     chart.zoomToDates(new Date(2009,9,20, 15), new Date(2009,10,3,12));
// }
  
  
</script>
@endsection

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
              <li class="nav-item">
                <a class="nav-link" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="false">น้ำหนัก</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">อาหาร</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">วิตามิน</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="pills-exercise-tab" data-toggle="pill" href="#pills-exercise" role="tab" aria-controls="pills-exercise" aria-selected="false">ออกกำลังกาย</a>
              </li>
              <li class="nav-item active">
                <a class="nav-link" id="pills-message-tab" data-toggle="pill" href="#pills-message" role="tab" aria-controls="pills-message" aria-selected="false">ข้อความ</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="pills-chat-tab" data-toggle="pill" href="#pills-chat" role="tab" aria-controls="pills-chat" aria-selected="false">บทสนทนา</a>
              </li>
              
                
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <!--        //////////  Content Weight   //////////   -->             
                <div class="tab-pane  fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                    <div class="content card">
                        <div class="title m-b-md">
                            <div id="chartdiv"></div>
                        </div>
                    </div>
                    <div class="card blue">
                          <p>น้ำหนักก่อนตั้งครรภ์</p>
                          <div>
                            <img  src="{{URL::asset('css/scale.png')}}" />
                              <h1>{{ $records1->user_Pre_weight}}</h1>
                          </div>
                    </div>
                    
                    <div class="card">
                        <h1>น้ำหนักระหว่างการตั้งครรภ์</h1>
                        <div>
                            <table>
                                <thead>
                                    <tr>
                                        <th>สัปดาห์</th>
                                        <th>น้ำหนัก</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($record as $records)
                                    <tr>
                                        <td>{{ $records->preg_week}}</td>
                                        <?php
                                            if ($records->preg_weight == 'NULL'){
                                                $preg_weight = 'ไม่มีการบันทึก';
                                            }else{
                                                $preg_weight = $records->preg_weight ;
                                            } 
                                        ?>
                                        <td>{{$preg_weight}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>                  
                    </div>
                </div>
                
                <!--        //////////  Content Food   //////////   -->             
                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                    
                    <div class="content card">
                        <h1>บันทึกอาหาร</h1>            
                        <table>
                            <tbody>
                              <tr> 
                                  <td>
                                    <h4>วันที่</h4>
                                  </td>
                                  <td >
                                    <h4>มื้อเช้า</h4>
                                  </td>
                                   <td >
                                    <h4>มื้อว่าง</h4>
                                  </td>
                                   <td >
                                    <h4>มื้อกลางวัน</h4>
                                  </td>
                                   <td >
                                    <h4 >มื้อว่าง</h4>
                                  </td>
                                  <td >
                                    <h4>มื้อเย็น</h4>
                                  </td>
                              </tr>
                                @foreach ($record_food as $records)
                              <tr>
                                  <td>
                                    <h2>{{ date('d', strtotime($records->created_at))}}</h2>
                                    {{ date('m-Y', strtotime($records->created_at))}}
                                  </td>
                                  <?php  
                                  if ($records->breakfast == 'NULL'){
                                    $breakfast = 'ไม่มีการบันทึก';
                                  }else{
                                    $breakfast = $records->breakfast ;
                                  }
                                  if ($records->lunch == 'NULL'){
                                    $lunch = 'ไม่มีการบันทึก';
                                  }else{
                                    $lunch = $records->lunch;
                                  }
                                  if ($records->dinner == 'NULL'){
                                    $din = 'ไม่มีการบันทึก';
                                  }else{
                                    $din = $records->dinner;
                                  }
                                  if ($records->dessert_lu == 'NULL'){
                                    $de_lu = 'ไม่มีการบันทึก';
                                  }else{
                                    $de_lu = $records->dessert_lu;
                                  }
                                  if ($records->dessert_din == 'NULL'){
                                    $de_din = 'ไม่มีการบันทึก';
                                  }else{
                                    $de_din= $records->dessert_din;
                                  }
                                  ?>
                                  <td >
                                      <p>{{$breakfast}}</p>
                                  </td>
                                   <td bgcolor="#F1F1F2">
                                      <p>{{$de_lu}}</p>
                                  </td>
                                   <td>
                                      <p>{{$lunch}}</p>
                                  </td>
                                   <td   bgcolor="#F1F1F2">
                                      <p>{{$de_din}}</p>
                                  </td>
                                   <td >
                                      <p>{{$din}}</p>
                                  </td>
                                 </tr>
                             @endforeach
                            </tbody>
                        </table>
                    </div>
                
                
                </div>
                
                <!--        //////////  Content Vitamin   //////////   -->
                <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">              
                <div class="content card">
                       <h1>บันทึกการทานวิตามิน</h1>
                      <div>
                  <table >
                    <tbody>
                          @foreach ($record_vitamin as $records)
                          <tr>
                              <td><h2>{{ date('d', strtotime($records->created_at))}}</h2>
                                {{ date('m-Y', strtotime($records->created_at))}}
                              </td>
                              <?php 
                              if ($records->vitamin == '0'){
                                $a = 'ไม่ได้ทาน';
                              }elseif ($records->vitamin  == '1'){
                                $a = 'ทาน';
                              }else{
                                $a = 'ไม่มีการบันทึก';
                              }
                              ?>
                            
                             <td>
                                  <p><img class="food" src="{{URL::asset('img_web/lime.png')}}" /> {{ $a }}</p>
                                
                              </td>
                          
                          </tr>
                         @endforeach
                   </tbody>
                </table>
                </div>
                </div>
            </div>
            
            <!--        //////////  Content Exercise   //////////   -->     
            <div class="tab-pane fade" id="pills-exercise" role="tabpanel" aria-labelledby="pills-exercise-tab">                
                <div class="content card">
                    <h1>บันทึกการออกกำลังกาย</h1>
                    <div>
                        <table >                      
                            <tbody>
                              @foreach ($record_exercise as $records)
                              <tr>
                                 <td><h2>{{ date('d', strtotime($records->created_at))}}</h2>
                                    {{ date('m-Y', strtotime($records->created_at))}}
                                  </td>
                    
                                  <?php  
                                  if ($records->exercise == 'ยัง'){
                                    $a = 'ไม่ได้ออกกำลังกาย';
                                  }elseif($records->exercise == 'NULL'){
                                    $a = 'ไม่มีการบันทึก';
                                  }else{
                                    $a = $records->exercise;
                                  }
                                  ?>
                                  <td>                               
                                      <p><img class="food" src="{{URL::asset('img_web/pregnancy.png')}}" /> {{ $a }}</p>
                                  </td>
                              </tr>
                             @endforeach
                            </tbody>
                        </table>
                    </div>              
                </div>
            </div>
            
            <!--        //////////  Content Message   //////////   -->      
            <div class="tab-pane fade in active" id="pills-message" role="tabpanel" aria-labelledby="pills-message-tab">              
                <div class="content card">
                    <h1>ประวัติข้อความที่ส่ง</h1>
                    <div>
                        <ul class="list-group mb-3">
                                @foreach($all_message as $message)
                                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                                   <div>
                                     <small class="text-muted">ข้อความ : {{ $message['message'] }}</small>
                                   </div>
                                     <small class="text-muted">วันที่ : {{ $message['created_at'] }}</small>
                                 </li>
                                 @endforeach
                            </ul>
                            <form method="POST" action="/api/weight_warning" class="card p-2">
                                 <input type="hidden" name="doctor_id" value="{{ $doctor_id }}" />
                                 <input type="hidden" name="user_id_line" value="{{ $user_id }}" />
                                 <div class="form-group">
                                   <label for="exampleFormControlTextarea1">message</label>
                                   <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="text"></textarea>
                                 </div>
                               <div class="form-group row mb-0">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-block">{{ __('ส่ง mssage') }}</button>         
                                </div>
                               </div>
                            </form>
                                @if (session('status'))
                                    <div class="alert alert-success">
                                        {{ session('status') }}
                                    </div>
                                @endif                      
                    </div>              
                </div>
            </div>
            <!--        //////////  Content Chat   //////////   -->     
            <div class="tab-pane fade" id="pills-chat" role="tabpanel" aria-labelledby="pills-chat-tab">                
                <div class="content card">
                    <h1>ประวัติสนทนา</h1>
                    <div>
                                @foreach($chats as $message)
                        <table width="100%" border="1">
                            <tr>
                                @if ($message['message_type'] == '01')
                                <td align="left" width="10%" valign="top">คุณแม่ : </td>
                                <td align="left" width="60%" valign="top">{{ $message['message'] }}</td>
                                <td align="left" width="20%"></td>                              
                                @elseif ($message['message_type'] == '02')
                                <td align="right" width="20%"></td>
                                <td align="right" width="60%" valign="top">{{ $message['message'] }}</td>
                                <td align="right" width="10%" valign="top">: REMI</td>
                                @elseif ($message['message_type'] == '03')
                                <td align="right" width="20%"></td>
                                <td align="right" width="60%" valign="top">{{ $message['message'] }}</td>
                                <td align="right" width="10%" valign="top">: คุณหมอ</td>                                
                                @endif
                            </tr>
                        </table>
                                 @endforeach
<!--                        <ul class="list-group mb-3">
                                @foreach($chats as $message)                            
                                    <li class="list-group-item d-flex justify-content-between lh-condensed">                                        
                                        <div class="pull-right"><small class="text-muted">ข้อความ : {{ $message['message'] }}</small></div>
                                        <small class="text-muted">วันที่ : {{ $message['created_at'] }}</small>
                                    </li>
                                 @endforeach
                            </ul>-->
                    </div>              
                </div>
            </div>
            <!--    //// -->
            
            </div>
        </div>
        
            <div class="col-md-4">

                 <?php if($mom_info->compli_diabete == 1 ){
                        $compli_diabete = 'มีภาวะแทรกซ้อน';
                   }else{
                        $compli_diabete ='-';
                   }

                    if($mom_info->compli_hypertension == 1 ){
                        $compli_hypertension = 'มีภาวะแทรกซ้อน';
                   }else{
                        $compli_hypertension ='-';
                   }

                    if($mom_info->compli_preterm_birth== 1 ){
                        $compli_preterm_birth = 'มีภาวะแทรกซ้อน';
                   }else{
                        $compli_preterm_birth ='-';
                   } ?>


                <h3>ข้อมูลคุณแม่ {{ $mom_info->user_name }}</h3>
<!--                <h5>เลขลำดับ : </h5> -->
                <h4>เลขประจำตัว(HN) : {{ $mom_info->hospital_num }} </h4>
                <h4>อายุ : {{ $mom_info->user_age }} ปี</h4>
                <h4>ส่วนสูง : {{ $mom_info->user_height }} เซ็นติเมตร </h4>
                <h4>กำหนดการคลอด : {{ $mom_info->due_date }}</h4>
                <h4>น้ำหนักก่อนตั้งครรภ์ : {{ $mom_info->user_Pre_weight }} กิโลกรัม</h4>
                <h4>น้ำหนักปัจจุบัน : {{ $mom_info->user_weight }} กิโลกรัม</h4>
                <h4>อายุครรภ์ : {{ $mom_info->preg_week }} สัปดาห์</h4>
                <h4>เบอร์โทรศัพท์ : {{  $mom_info->phone_number}}</h4>
                <h4>อีเมล์ : {{  $mom_info->email}}</h4>
                <h4>โรงพยาบาลที่ฝากครรภ์ : {{  $mom_info->hospital_name}}</h4>
                <h4>แพ้อาหาร : {{ $mom_info->history_food }} </h4>
                <h4>ภาวะแทรกซ้อนระหว่างตั้งครรภ์</h4>
                <h4>เบาหวาน : {{ $compli_diabete }} </h4>
                <h4>ความดันสูง : {{ $compli_hypertension }} </h4>
                <h4>เจ็บครรภ์คลอดก่อนกำหนด : {{ $compli_preterm_birth }} </h4>
                <hr />
                <form method="post" action="{{url('hnnumber_save')}}">
                    {{ csrf_field() }}
                    <h4>เลขประจำตัว (HN)</h4>
                    <input type="text" name="hn_number" value="{{ $mom_info->hospital_num }}" />
                    <input type="hidden" name="user_id" value="{{ $mom_info->user_id }}" />
                    <br><br>
                    <button type="submit" class="btn btn-info">บันทึก HN Number</button>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection
