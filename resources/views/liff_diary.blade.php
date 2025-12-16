<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>บันทึกการทานอาหาร วิตามินและการออกกำลังกาย</title>
</head>
<link href="https://fonts.googleapis.com/css?family=Kanit" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<!-- edit -->
<link rel="stylesheet" href="<?php echo asset('css/redesign.css')?>" type="text/css"> 
<script src="<?php echo asset('js/redesign.js')?>"></script>
<link href="https://fonts.googleapis.com/css2?family=Pridi:wght@300;400;500;700&display=swap" rel="stylesheet">

<body>           

<form method="POST" action="{{route('savediary')}}">
{{ csrf_field() }}  
<div class="container">
  <h2>บันทึกอาหาร/ออกกำลังกาย</h2>
  <h4>วันที่บันทึก</h4>
    <div class="datepicker1 w-100" id="datepicker">
      <input id = "getDate" name= "date" type="text" class="created_on required datepicker" value={{date("Y-m-d")}} required="">
    </div>
  <div class="btn-group"> 
    <div class="btn btn-primary " data-value="food">อาหาร</div>
    <div class="btn btn-primary inactive" data-value="exercise">วิตามิน/ออกกำลังกาย</div>
  </div>
  <div class="section section-food">
    <input type="text" id="meal" name= "meal" value = "1" hidden>
    <h4>มื้ออาหาร</h4>
    <div class="wrap-group-selected meal">
      <div class="group-selected active" value="1">
        <img src="<?php echo asset('image/sunrise.png')?>"/>
        <p>เช้า</p>
      </div>
      <div class="group-selected" value="4">
        <img src="<?php echo asset('image/coffee-break.png')?>"/>
        <p>ว่างเช้า</p>
      </div>
      <div class="group-selected" value="2">
        <img src="<?php echo asset('image/sun.png')?>"/>
        <p>กลางวัน</p>
      </div>
      <div class="group-selected" value="5">
        <img src="<?php echo asset('image/afternoon-tea.png')?>"/>
        <p>ว่างบ่าย</p>
      </div>
      <div class="group-selected" value="3">
        <img src="<?php echo asset('image/cloudy-night.png')?>"/>
        <p>เย็น</p>
      </div>
    </div>
    <h4>บันทึกเวลา</h4>
    <div class="wrap-group-selected">
      <input type="time" id="time" name="time" />
    </div>
    <h4>อาหาร</h4>
    <input type="text" id="food_namemain"  name="mainfood_name" placeholder="อาหารที่ทาน" class="w-100">
    <h4>ส่วนประกอบ</h4>
    <div id="dynamic_field">
      <div class="wrap-group-selected">
        <input type="text" name="moreFields[0][food_name]" placeholder="ส่วนประกอบ">
        <input type="number" step="any" name="moreFields[0][portion]" placeholder="ปริมาณ">
        <select name="moreFields[0][unit]">
          <option value="1" selected>ทัพพี</option>
          <option value="2">ช้อน</option>
          <option value="3">ช้อนโต๊ะ</option>
          <option value="4">ลูก</option>
          <option value="5">ฟอง</option>
          <option value="6">ตัว</option>
          <option value="7">มล.</option>
          <option value="8">ชิ้น</option>
          <option value="9">อื่นๆ</option>
        </select>
        <button type="button" name="add" id="add2" class="btn btn-success btn-txt">+</button>
      </div>
    </div>
  </div>
  <div class="section section-exercise hidden">
    <!-- <h4>วันที่บันทึก</h4>
    <div class="datepicker1 w-100" id="datepicker"> -->
      <!-- <input id = "getDate" name= "date" type="text" class="created_on required datepicker" value={{date("Y-m-d")}} required=""> -->
    <!-- </div> -->
    <input type="text" id = "vitamin" name="vitamin" value = "0" hidden>
    <h4>วิตามิน</h4>
    <div class="wrap-group-selected vitamin">
      <div class="group-selected " value="1">
        <img src="<?php echo asset('image/medicine.png')?>"/>
        <p>ทานวิตามิน</p>
      </div>
      <div class="group-selected" value="0">
        <img src="<?php echo asset('image/traffic-signal.png')?>"/>
        <p>ไม่ทานวิตามิน</p>
      </div>
    </div>
    <h4>การออกกำลังกาย</h4>
    <input type="text" id = "exercise" name="exercise" placeholder="กิจกรรมที่ทำ" class="w-100">
  </div>
    
  <div>
     <input type="text" name = "user_id"  id = "user_id" value="{{$id}}" hidden/>
     <button type="submit"  class ="send-btn btn-primary w-100" id = "confirm-sugar">บันทึก</button>
  </div>  
</div>
</form>  


@if(session()->has('message')) 
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif   
<hr/>
<div class="history container">
  <div class="content card">
    <h4>ประวัติการทานอาหาร</h4>
    <table class="" cellpadding="1" cellspacing="1" id="">
      <thead>
        <tr class="table-header">
          <th >วันที่</th>
          <th>เวลา</th>
          <th>มื้อ</th>
          <th>อาหาร</th>
          <th>ส่วนประกอบ</th>
          <th>ปริมาณ</th>
          <th>หน่วย</th>
          <th>ลบ</th>
        </tr>
      </thead>
      <tbody>
      @foreach ($array3 as $tkact )
      <?php 

      $Date = date("d", strtotime($tkact->date));
      $year = date("Y", strtotime($tkact->date));  
      $strMonth= date("n",strtotime( $tkact->date));
      $strMonthCut = Array(" ","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
      $strMonthThai=$strMonthCut[$strMonth];
      $DateThai = $strMonthThai;

      $time = date("H:i",strtotime($tkact->time));

      
      $strunit = Array(" ","ทัพพี","ช้อน","ช้อนโต๊ะ","ลูก","ฟอง","ตัว","มล.","ชิ้น","อื่นๆ");
      $strmeal = Array(" ","เช้า","กลางวัน","เย็น","ว่างเช้า","ว่างบ่าย");
      $unit = $strunit[$tkact->unit];
      // $meal = $strmeal[$tkact->meal];
      $meal = "";
      // echo($tkact->meal);
      switch ($tkact->meal) {
        case 1:
          $meal = "image/sunrise.png";
          break;
        case 2:
          $meal = "image/sun.png";
          break;
        case 3:
          $meal = "image/cloudy-night.png";
          break;
        case 4:
          $meal = "image/coffee-break.png";
          break;
        case 5:
          $meal = "image/afternoon-tea.png";
          break;
      }

      ?>
        <tr class="" >
        <td style="display:none;"><label>{{$tkact->date}}</label></td>
        <td>{{ $Date  }} {{$strMonthThai}} {{$year}}</td>
        <td>{{$time}}</td>
        <td><img class="icon" src="<?php echo asset("{$meal}")?>"/></td>
        <!-- <td class="txt-left">food_name</td> -->
        <td class="txt-left">{{$tkact->food_name}}</td>
        <td class="txt-left">{{$tkact->ingredient_name}}</td>
        <td>{{$tkact->portion}}</td>
        <td>{{$unit}}</td>
        <td><label>
              <form action="{{ route('delete_diary',[$tkact->id]) }}" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
              <button  class="button btn-danger" type="submit" onclick="return confirm('คุณต้องการลบ ใช่หรือไม่?')" ><i class="fa fa-trash"></i></button>
          </form></label></td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>


  <div class="content card">
    <h4>ประวัติการบันทึกวิตามินและออกกำลังกาย</h4>
    <!-- <div class="datepicker1" id="datepicker" style="text-align:center;">
      <label for="datepicker">Date</label>
      <input id = "getDate" type="text" style="width:120px;" class="created_on required datepicker" placeholder="DD-MM-YYYY" required="">
    </div> -->
    <table class="" cellpadding="1" cellspacing="1" id="">
      <thead>
        <tr class="table-header">
          <th >วันที่</th>
          <th >วิตามิน</th>
          <th >ออกกำลังกาย</th>
          <th >ลบ</th>
        </tr>
      </thead>
      <tbody id="">
      @foreach ($tracker as $tk )
      <?php 
      $Date = date("d", strtotime($tk->date));
      $year = date("Y", strtotime($tk->date));  
      $strMonth= date("n",strtotime( $tk->date));
      $strMonthCut = Array(" ","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
      $strMonthThai=$strMonthCut[$strMonth];
      $DateThai = $strMonthThai;

      $time_breakfast = date("h:i",strtotime($tk->time_breakfast));
      $time_lunch = date("h:i",strtotime($tk->time_lunch));
      $time_dinner = date("h:i",strtotime($tk->time_dinner));

      $vitamin_a = Array("ไม่ได้ทาน","ทาน");
      // $vitamin =$vitamin_a[$tk->vitamin];

      switch ($tk->vitamin) {
        case 0:
          $vitamin = "image/traffic-signal.png";
          break;
        case 1:
          $vitamin = "image/medicine.png";
          break;
      }
      
      ?>
        <tr class="">
        <td style="display:none;"><label>{{$tk->date}}</label></td>
          <td >{{ $Date  }}  {{$strMonthThai}} {{$year}}</td>  
          <td><img class="icon" src="<?php echo asset("{$vitamin}")?>"/></td>
          <td class="txt-left">					             
            {{ $tk->exercise }}</p>
          </td>
          <td><label>
              <form action="{{ route('delete_diary',[$tk->id]) }}" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
              <button  class="button btn-danger" type="submit" onclick="return confirm('คุณต้องการลบ ใช่หรือไม่?')" ><i class="fa fa-trash"></i></button>
          </form></label></td>
        </tr>
        @endforeach
      </tbody>
    </table> 
  </div>
</div>
    <!-- LIFF SDK  -->
    <script src="https://static.line-scdn.net/liff/edge/versions/2.9.0/sdk.js"></script>
    <!-- <script >
           $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

      window.onload = function (e) {

    function runApp() {
      liff.getProfile().then(profile => {
        document.getElementById("user_id").value = profile.userId;
        user_id_line = profile.userId;
      }).catch(err => console.error(err));
    }
    liff.init({ liffId: "1656991660-kq47bAMD" }, () => {
      if (liff.isLoggedIn()) {
        runApp()
      } else {
        liff.login();
      }
    }, err => console.error(err.code, error.message));      
    
};
</script> -->

<script>
$(document).on("change", "#datepicker .created_on", function() {
  var dataVal = $(this).datepicker('getDate');//get date from datepicker
  dataVal= $.datepicker.formatDate("yy-mm-dd", dataVal);//set format date like in the rows
  //console.log(dataVal, typeof dataVal);
  if (dataVal != '') {
    $("tr:not('#dynamic_field')").hide();//hide all rows
  
 
    //show rows with the same date selected
   
    $("label:contains('" +  dataVal + "')").each(function(){
      $(this).closest('tr').show();
    });  
  }   
});

(function($) {
  $('.datepicker').each(function() {
    $(this).datepicker({
      setDate : new Date(),
      changeMonth: true,
      changeYear: true,
      dateFormat: 'yy-mm-dd',
      onClose: function() {
        //triggerFocus();
      }
    });
  });
}(jQuery));

var date = new Date();
var currentDate = date.toISOString().slice(0,10);
var currentTime = date.getHours() + ':' + date.getMinutes();

document.getElementById('time').value = currentTime;

// $('#vitamin').on('change', function(){
//    this.value = this.checked ? 1 : 0;
//    // alert(this.value);
// }).change();
</script>


</script>

</body>
</html>