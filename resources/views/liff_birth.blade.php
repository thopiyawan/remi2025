<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>แจ้งวันคลอด</title>
</head>
<link href="https://fonts.googleapis.com/css?family=Kanit" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
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

    }
    table thead tr {
        height: 60px;
        background: #c7dcf7;
    }
    table tbody tr {
        height: 48px;
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
    display: block;    
    /* font-size: 20px;  //อยู่ในบรรทัดเดียวกัน มีค่าตามที่กำหนด    //ขนาดของตัวอักษร
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

<form method="POST" action="{{ route('birthdate') }}">
    {{ csrf_field() }} 
    <input type="text" name = "user_id"  id = "user_id" value="" hidden/>
    <div class="content card">
       <h3>แจ้งวันคลอด</h3> 
       <div style="text-align:center;">
       <p>คุณแม่คลอดวันไหนคะ</p>
       <h3> <input type="date" id="birthdate" name="birthdate" required autofocus></h3><h1></h1>
       <br>
       <p>อายุครรภ์ขณะคลอดสัปดาห์ที่เท่าไรคะ</p> 
    </div>
    
<div class="container">
  <form class="range">
     <div class="slider">
        <input  onchange="calculate()" type="range" min="30" max="42" value="0"  id="rangeValue-op" name = "week" required autofocus>
        <p id="rangeValue">0</p>
     </div>
     <br>
     <div style="text-align:center;">
        <button class ="send-btn" id = "confirm-sugar" type="submit">บันทึก</button>
     </div>      
   </form> 
   @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
   @endif     
</div>

    <!-- LIFF SDK  -->
    <script src="https://static.line-scdn.net/liff/edge/versions/2.9.0/sdk.js"></script>
    <script >
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
    liff.init({ liffId: "1656991660-4LbgJrjy" }, () => {
      if (liff.isLoggedIn()) {
        runApp()
      } else {
        liff.login();
      }
    }, err => console.error(err.code, error.message));      
    
};
</script>
<script>
        function calculate () {
        // Display KM Driven Sliderid="rangeValue-op"
        var rangeValueop = document.getElementById("rangeValue-op");
        var rangeValue = document.getElementById("rangeValue")
        rangeValue.innerHTML = rangeValueop.value;
        }

        var slider = document.getElementById("rangeValue-op");
        var output = document.getElementById("rangeValue");
        output.innerHTML = slider.value;

        slider.oninput = function() {
        output.innerHTML = this.value;
        }
</script>

</body>
</html>