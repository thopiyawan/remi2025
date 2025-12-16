<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>botest</title>
</head>
<link href="https://fonts.googleapis.com/css?family=Kanit" rel="stylesheet">

<link rel="stylesheet" href="{{URL::asset('css/stylecss_pploy.css')}}">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<style>
#sendmessagebutton {
    background-color: #4CAF50; /* Green */
    border: none;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
}


#sendmessagebutton {border-radius: 4px;}
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
    <div class="content card">
    <h3>การยืนยันคุณหมอประจำตัว</h3> 
    <input type=text id="userId" name="userId" hidden/>
    <center> 
    <h4> รหัสคุณหมอ:</h4> <p id="doctor_id"></p> 
    <h4>  ชื่อ: </h4> <p id="name"></p> 
    <h4>  นามสกุล: </h4> <p id="lastname"></p>
    <button type="submit"  class ="send-btn" id = "confirm-sugar">ยืนยัน</button></center>      
    </div>

    <!-- LIFF SDK  -->
  

 <!-- LIFF SDK  -->
 <script src="https://static.line-scdn.net/liff/edge/versions/2.9.0/sdk.js"></script>
 <script>

    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });

    var user_id_line;
    var doctor_id = "<?php echo  $record->doctor_id ?>";
    var lastname= "<?php echo  $record->lastname ?>";
    var name= "<?php echo  $record->name ?>";
    var roomId;

    window.onload = function (e) {

    $('.send-btn').on('click', function(){
        var _token = $('input[name="_token"]').val(); 
        $.ajax({
            url:"{{route('send_code')}}",
            method:"POST",
            data:{ doctor_id:doctor_id, 
                    roomId:roomId, 
                    user_id_line:user_id_line,
                    _token:_token },
        }).then(function () {
            liff.closeWindow()
        })
    });

    function runApp() {
    liff.getProfile().then(profile => {
    document.getElementById("userId").value = profile.userId;
    user_id_line = profile.userId;
    document.getElementById("doctor_id").innerHTML = doctor_id;
    document.getElementById("name").innerHTML = name;
    document.getElementById("lastname").innerHTML = lastname;
    }).catch(err => console.error(err));
    }
    liff.init({ liffId: "1656991660-K8bDpjZ9" }, () => {
    if (liff.isLoggedIn()) {
    runApp()
    } else {
    liff.login();
    }
    }, err => console.error(err.code, error.message));      

    };
</script>
</body>
</html>