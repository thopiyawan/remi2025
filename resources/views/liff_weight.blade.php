<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
  
    <title>บันทึกน้ำหนัก</title>
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
 <style>

body{
    background-color: #f3f6fb;
    padding: 0;
    margin: 0;
    font-family: 'Kanit', sans-serif;
    /* font-size: 15pt; */
}
/* #chartdiv {
    width   : 100%;
    height    : 500px;
    font-size : 11px;
}                             */
h3,h4,p {
    -webkit-margin-before:0px;
    -webkit-margin-after: 0px;
}
h1,h5 {
    -webkit-margin-before:10px;
    -webkit-margin-after: 0px;
}
.foot{
    width: 100%;
    position: fixed;
    bottom: 0;
    z-index: -5;
}

.card{
    margin: 30px;
    padding: 20px 30px;
    background-color: white;
    border-radius: 15px;
    padding: 10px;
    box-shadow: 5px 10px 18px #888888be;
}

.card1{
    margin: 30px;
    padding: 20px 30px;
    background-color: #ffffff;
    border-radius: 15px;
    padding: 10px;
    box-shadow: 5px 10px 18px #888888be;
}
.card h1{
    text-align: center;

}
.blue{
    color: #c7dcf7;
    text-align: center;
    position:relative;
}
.blue>div{
    display: flex;
    justify-content: center;
}
.blue p{
    color: gray;
}
.blue h1{
    font-size: 3em;
    font-weight: bolder;
}
.blue img{
    width: 80px;
    height: 80px;
    padding-right:30px;
}

table {
    border-spacing: 1;
    border-collapse: collapse;
    /* background: #f4f9ff; */
    background: white;
    border-radius: 15px;
    max-width: 800px;
    width: 100%;
    margin: 0 auto;
    margin-top: 30px;
    font-size: 10pt;

}
table thead tr {
    height: 40px;
    background: #c7dcf7;
}
table tbody tr {
    height: 50px;
    border-bottom: 1px solid #c7dcf7;
}
table tbody tr:last-child {
    border: 0;
}
tr,th{
    text-align: center;
}
.food {
    width: 30px;
    height: 30px;
    padding-right: 5px;
}


/* body {
 background: #EFCDA4;
} */
.slider {
 /* position:absolute; */
 /* top:50%;
 left:50%; */
/* margin: 30px; */
padding: 0px 30px;
 /* transform:translate(-50%,-50%); */
 /* width:350px; */
 height:40px;
 /* padding:30px; */
 padding-left: 40px;
 background:#fcfcfc;
 border-radius:20px;
 display:flex;
 align-items:center;
 
 /* box-shadow:0px 15px 40px #7E6D5766; */
}
.slider p {
    text-transform: uppercase;
 font-size:26px;
 font-weight:600;
 /* font-family: Open Sans; */
 padding-left:30px;
 color:#6495ED;
 
}
.slider input[type="range"] {
 -webkit-appearance:none !important;
 width:420px;
 height:2px;
 background:#6495ED;
 border:none;
 outline:none;
}
.slider input[type="range"]::-webkit-slider-thumb {
 -webkit-appearance:none !important;
 width:30px;
 height:30px;
 background:#fcfcfc;
 border:2px solid #6495ED;
 border-radius:50%;
 cursor:pointer;
}
.slider input[type="range"]::-webkit-slider-thumb:hover {
 background:#6495ED;
 display: block 
}

.container {
   display: block;    //อยู่ในบรรทัดเดียวกัน มีค่าตามที่กำหนด 
   /* font-size: 20px;     //ขนาดของตัวอักษร
   padding: 20px;       //ขยายขอบ */
}

form-group label {
  text-transform: uppercase;
  font-size: .7rem;
  color: #222;
  margin-bottom: 5px;
}

.form {
  display: flex;
  flex-direction: column; 
  justify-content: center;
}


/* From cssbuttons.io by @hannahyockel */
button {
 /* display: flex; */
 flex-direction: row;
 justify-content: center;
 align-items: center;
 padding: 7px 20px;
 border-radius: 10px;
 border: 1px solid transparent;
 color: #FFFFFF;
 background-color: #1DC9A0;
 font-size: 16px;
 letter-spacing: 1px;
 transition: all 0.15s linear;
}

button:hover {
 background-color: rgba(29, 201, 160, 0.08);
 border-color: #1DC9A0;
 color: #1DC9A0;
 transform: translateY(-5px) scale(1.05);
}

button:active {
 background-color: transparent;
 border-color: #1DC9A0;
 color: #1DC9A0;
 transform: translateY(5px) scale(0.95);
}

button:disabled {
 background-color: rgba(255, 255, 255, 0.16);
 color: #8E8E93;
 border-color: #8E8E93;
}

</style>


<body>           

<form method="POST" action="{{route('saveweight')}}">
{{ csrf_field() }}      

    <div class="content card">
        <!-- <div id="us_id"></div> -->
       <h3>บันทึกน้ำหนัก</h3> 
    
       <div style="text-align:center;">
       <h3> 
           <!-- <input type="date" id="date" name="date" required autofocus> -->
       <!-- <div class="datepicker1" id="datepicker" style="text-align:center;">
            <label for="datepicker">Date</label>
            <input  id = "getDate" name= "date"type="text" style="width:120px;" class="created_on required datepicker" value={{date("Y-m-d")}} required="">
       </div> -->
       <!-- </h3><h1></h1>
       <p>วันนี้ลูกดิ้นจำนวน</p>
       <h3 id="total">0</h3>
       <p>ครั้ง</p>
       <br> -->
</div>
       <p>บันทึกทุกๆสัปดาห์ สัปดาห์ละ 1 ครั้ง</p> 
       <br>

<div class="container">
  <form class="range">


  <h4 style="text-align:center;" >สัปดาห์ที่คุณแม่ตั้งครรภ์</h4>  
  <div class="slider">
  <input  onchange="calculate()" type="range" min="1" max="41" value="0" id="rangeValue1-op" name = "preg_week">
  <p id="rangeValue1"><label>0</label></p>
  </div>

  <h4 style="text-align:center;" >น้ำหนักสัปดาห์นี้กี่กิโลกรัมคะ</h4> 
  <div class="slider">
  <input  onchange="calculate()" type="range" min="0" max="150" value="0"  id="rangeValue-op" name = "preg_weight" >
  <p id="rangeValue"><label>0</label></p>
  </div>
 
<!--  
  <h4 style="text-align:center;" >กลางวัน</h4>  
  <div class="slider">
  <input  onchange="calculate()" type="range" min="0" max="20" value="0" id="rangeValue1-op" name = "num_noon">
  <p id="rangeValue1"><label>0</label></p>
  </div>

  <h4 style="text-align:center;" >เย็น</h4> 
  <div class="slider">
  <input  onchange="calculate()" type="range" min="0" max="20" value="0"   id="rangeValue2-op" name = "num_evening">
  <p id="rangeValue2"><label>0</label></p>
  </div> -->



  <div style="text-align:center;">

     <input type="text" name = "user_id"  id = "user_id" value="{{$id}}" hidden/>

     <button type="submit"  class ="send-btn" id = "confirm-sugar">บันทึก</button>
</div>

</form>  

            @if(session()->has('message')) 
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif   

            <div id="responsecontainer" align="center">

</div>
</div>
</div>

<div class="content card">
<!-- <div class="datepicker1" id="datepicker" style="text-align:center;">
  <label for="datepicker">Date</label>
  <input id = "getDate" type="text" style="width:120px;" class="created_on required datepicker" placeholder="DD-MM-YYYY" required="">
</div> -->
<table class="" cellpadding="1" cellspacing="1" id="">
  <thead>
    <tr id="table-header">
      <th ><label >สัปดาห์</label></th>
      <th><label >น้ำหนัก</label></th>
    </tr>
  </thead>
  <tbody id="">
    @foreach ($record_weight as $fm )
    <tr class="">
    <td style="display:none;"><label></label></td>
      <td ><label>{{ $fm->preg_week}}</label></td>
      <td ><label>{{ $fm->preg_weight }}</label></td>
    </tr>
    @endforeach
  </tbody>
</table>
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
    function calculate () {
        // Display KM Driven Sliderid="rangeValue-op"
        var rangeValueop = document.getElementById("rangeValue-op");
        var rangeValue = document.getElementById("rangeValue")
        rangeValue.innerHTML = rangeValueop.value;

        var rangeValueop1 = document.getElementById("rangeValue1-op");
        var rangeValue1 = document.getElementById("rangeValue1")
        rangeValue1.innerHTML = rangeValueop1.value;

        // The Math!
     
        
        // document.getElementById("total").innerHTML = ` ${total}`;
  
    }

    var slider = document.getElementById("rangeValue-op");
    var output = document.getElementById("rangeValue");
    output.innerHTML = slider.value;

    var slider1 = document.getElementById("rangeValue1-op");
    var output1 = document.getElementById("rangeValue1");
    output1.innerHTML = slider1.value;
  

    slider.oninput = function() {
      output.innerHTML = this.value;
    }

    slider1.oninput = function() {
      output1.innerHTML = this.value;
    }
  
</script>
<script>
$(document).on("change", "#datepicker .created_on", function() {
  var dataVal = $(this).datepicker('getDate');//get date from datepicker
  dataVal= $.datepicker.formatDate("yy-mm-dd", dataVal);//set format date like in the rows
  //console.log(dataVal, typeof dataVal);
  if (dataVal != '') {
    $("tr:not('#table-header')").hide();//hide all rows
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


</script>


</body>
</html>