<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
  
    <title>บันทึกลูกดิ้น</title>
</head>
<link href="https://fonts.googleapis.com/css?family=Kanit" rel="stylesheet">
<!-- <link rel="stylesheet" href="css/stylecss_pploy.css" /> -->
<!-- <link rel="stylesheet" href="{{URL::asset('css/stylecss_pploy.css')}}"> -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />


<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">



<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
<!--   <script src="https://res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/vconsole/3.0.0/vconsole.min.js"></script>
  <script>
    var vConsole = new VConsole();
  </script> -->

<!-- edit -->
<link rel="stylesheet" href="<?php echo asset('css/jqueryrange_calendar.css')?>" type="text/css">
<link rel="stylesheet" href="<?php echo asset('css/redesign.css')?>" type="text/css">  
<script src="<?php echo asset('js/redesign.js')?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/locale/th.min.js"></script>
<script src="<?php echo asset('js/jqueryrange_calendar.js')?>"></script>

<body>           
<form method="POST" action="{{route('babykicks')}}">
  {{ csrf_field() }}   
  <div class="container section-move">
    <h2>บันทึกลูกดิ้น</h2> 
    <div class="section section-movement">
      <div id="selectedDateRange">
        <h3 class="txt-primary"></h3>
        <h1 id="dateop"></h1>
      </div>
      <div class="dateRangeCalendarWrapper">
        <div id="dateRange"></div>
        <div id="buttonDatePreviousWrap">&lsaquo;</div>
        <div id="buttonDateNextWrap">&rsaquo;</div>
      
      </div>
    </div>
    <div class="card">
      <div class="wrap-group-selected">
        <h1>การดิ้น</h1>
        <div class="txt-primary d-flex txt-baseline">
          <h1 id="total">0</h1>
          <p>ครั้ง</p>
        </div>  
      </div> 
      <p>นับครั้งละ 1 ชั่วโมง หลังทานอาหาร</p> 
      <form class="range">
        <!-- input field รับค่าวันที่ เพื่อส่งไปเก็บใน DB -->
        <input id = "inputdate" name= "date" type="text" value={{date("Y-m-d")}} required="" hidden/>
        
        <div class="white-card card group-movement d-flex">
          <img src="<?php echo asset('image/sunrise.png')?>"/>
          <div class="pl-16 w-100">
            <div class="wrap-group-selected">
              <h3>เช้า</h3>
              <div class="txt-primary d-flex txt-baseline">
                <h3 id="rangeValue">0</h3>
                <p>ครั้ง</p>
              </div>
            </div>
            <div class="slider movement">
              <input  onchange="calculate()" type="range" min="0" max="20" value="0"  id="rangeValue-op" name = "num_morning" >
            </div>
          </div> 
        </div>
        <div class="white-card card group-movement d-flex">
          <img src="<?php echo asset('image/sun.png')?>"/>
          <div class="pl-16 w-100">
            <div class="wrap-group-selected">
              <h3>กลางวัน</h3>
              <div class="txt-primary d-flex txt-baseline">
                <h3 id="rangeValue1">0</h3>
                <p>ครั้ง</p>
              </div>
            </div>
            <div class="slider movement">
              <input  onchange="calculate()" type="range" min="0" max="20" value="0" id="rangeValue1-op" name = "num_noon">
            </div>
          </div> 
        </div>
        <div class="white-card card group-movement d-flex">
          <img src="<?php echo asset('image/cloudy-night.png')?>"/>
          <div class="pl-16 w-100">
            <div class="wrap-group-selected">
              <h3>เย็น</h3>
              <div class="txt-primary d-flex txt-baseline">
                <h3 id="rangeValue2">0</h3>
                <p>ครั้ง</p>
              </div>
            </div>
            <div class="slider movement">
              <input  onchange="calculate()" type="range" min="0" max="20" value="0"   id="rangeValue2-op" name = "num_evening">
            </div>
          </div> 
        </div>
        <div style="text-align:center;">
          <input type="text" name = "user_id"  id = "user_id" value="{{$id}}" hidden/>
          <button type="submit"  class ="send-btn btn-primary w-100" id = "confirm-sugar">บันทึก</button>
        </div> 
        @if(session()->has('message')) 
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif   
        <div id="responsecontainer" align="center"></div>
      </form>
    </div>
</div>
</form>

<hr/>

<div class="history container">
  <div class="content card">
    <h3>ประวัติการดิ้น</h3>
    <table class="" cellpadding="1" cellspacing="1">
      <thead>
        <tr class="table-header">
          <th>วัน</th>
          <th><img class="icon" src="<?php echo asset("image/sunrise.png")?>"/></th>
          <th><img class="icon" src="<?php echo asset("image/sun.png")?>"/></th>
          <th><img class="icon" src="<?php echo asset("image/cloudy-night.png")?>"/></th>
          <th>รวม</th>
          <th>ผิดปกติ</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($fetal_movement as $fm )
    <?php 

        $Date = date("d", strtotime($fm->date));
        $year = date("Y", strtotime($fm->date));  
        $strMonth= date("n",strtotime($fm->date));
        $strMonthCut = Array(" ","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
        $strMonthThai=$strMonthCut[$strMonth];
        $DateThai = $strMonthThai;
        if(($fm->num_noon+ $fm->num_morning + $fm->num_evening)<10){
          $color = "#FA8072";
        }else{
          $color = "#17202A";
        }
      ?>
    <tr class="" style="color:{{{ $color }}}">
      <td >{{ $Date  }}  {{$strMonthThai}} {{$year}}</td>  
      <td >{{ $fm->num_morning }}</td>
      <td>{{ $fm->num_noon }}</td>
      <td>{{ $fm->num_evening}}</td>
      <td>{{ $fm->num_evening + $fm->num_noon +$fm->num_morning }}</td>
      <td>
      @if ($color ==  "#FA8072")
  
      <form action="{{ route('noti-fetalmove',[$fm->user_id]) }}" method="POST">
      {{ csrf_field() }}
        <!-- <form action="{{ route('noti-fetalmove',['id'=> $fm->user_id]) }}" method="POST"> -->
          <button type="submit" class="button btn-warning">แจ้งแพทย์</button>
        </form> 
      @endif</td>
    </tr>
    @endforeach
  </tbody>
</table>
</div>
<script src="https://static.line-scdn.net/liff/edge/versions/2.9.0/sdk.js"></script>
<script>
    function calculate () {
        // Display KM Driven Sliderid="rangeValue-op"
        var rangeValueop = document.getElementById("rangeValue-op");
        var rangeValue = document.getElementById("rangeValue")
        rangeValue.innerHTML = rangeValueop.value;

        // Display Avg Mileage
        var rangeValueop1 = document.getElementById("rangeValue1-op")
        var rangeValue1 = document.getElementById("rangeValue1")
        rangeValue1.innerHTML = rangeValueop1.value;


        //Display Avg Price
        var rangeValueop2 = document.getElementById("rangeValue2-op")
        var rangeValue2 = document.getElementById("rangeValue2")
        rangeValue2.innerHTML = rangeValueop2.value;

        // The Math!
        var total = (+rangeValueop.value + +rangeValueop1.value + +rangeValueop2.value);
        
        document.getElementById("total").innerHTML = ` ${total}`;
  
    }

    var slider = document.getElementById("rangeValue-op");
    var output = document.getElementById("rangeValue");
    output.innerHTML = slider.value;
    var slider1 = document.getElementById("rangeValue1-op");
    var output1 = document.getElementById("rangeValue1");
    output1.innerHTML = slider1.value;
    var slider2 = document.getElementById("rangeValue2-op");
    var output2 = document.getElementById("rangeValue2");
    output2.innerHTML = slider2.value;

    slider.oninput = function() {
      output.innerHTML = this.value;
    }
    slider1.oninput = function() {
      output1.innerHTML = this.value;
    }
    slider2.oninput = function() {
      output2.innerHTML = this.value;
    }
</script>
</body>
</html>