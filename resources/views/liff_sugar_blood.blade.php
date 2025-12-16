<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>บันทึกระดับน้ำตาลในเลือด</title>
</head>
<link href="https://fonts.googleapis.com/css?family=Kanit" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<!-- edit -->
<link rel="stylesheet" href="<?php echo asset('css/redesign.css')?>" type="text/css"> 
<script src="<?php echo asset('js/redesign.js')?>"></script>

<body>     
<form method="POST" action="{{ route('sugar_blood') }}">
{{ csrf_field() }}      
	<div class="container">
		<h2>บันทึกระดับน้ำตาลในเลือด</h2> 
		<div class="section section-sugar">
			<h4>มื้ออาหาร</h4>
			<input type="text" id="meal" name = "meal" value = "1" hidden >
			<div class="wrap-group-selected meal" >
				<div class="group-selected active" value="1" >
					<img src="<?php echo asset('image/sunrise.png')?>"/>
					<p>เช้า</p>
				</div>
				<div class="group-selected" value="2">
					<img src="<?php echo asset('image/sun.png')?>"/>
					<p>กลางวัน</p>
				</div>
				<div class="group-selected" value="3">
					<img src="<?php echo asset('image/cloudy-night.png')?>"/>
					<p>เย็น</p>
				</div>
			</div>
		
			<h4>ช่วงการวัด</h4>
			<input type="text" name="time_of_day" value = "1" hidden >
			<div class="wrap-group-selected time">
				<div class="group-selected active" value="1">
					<p>ก่อน</p>
				</div>
				<div class="group-selected" value="3">
					<p>หลัง 1 ชม.</p>
				</div>
				<div class="group-selected" value="4">
					<p>หลัง 2 ชม.</p>
				</div>
			</div>
			<div class="wrap-group-selected">
				<h4>ค่าน้ำตาล</h4>
				<center>
				<label> กรอกค่าน้ำตาล
				<input type="number" id="sugardata" min="0" name = "blood_sugar" max="400" pattern="[0-9]*"  placeholder="200" oninput="bloodsugar.value=sugardata.value" oninvalid="this.setCustomValidity('ค่าไม่เกิน 400 โปรดระบุใหม่อีกครั้ง.....')"></label>
				</center>
			</div>
			
			<div class="wrapper sugar-range">
				<div class="wrap-group-selected">
					<p class="c1" id="data-low">ต่ำ</p>
					<p class="c1" id="data-normal">ปกติ</p>
					<p class="c1" id="data-height">สูง</p>
				</div>
				

				<!-- <input type="range" min="0" max="200" name = "blood_sugar" step="1" onchange="updateTextInput(this.value);"/> -->
				<input  type="range" min="0" max="200" id="bloodsugar" step="1" oninput="sugardata.value=bloodsugar.value" />
			</div>


			<!-- <input type="text"  name = "blood_sugar"  id = "blood_sugar" placeholder="ค่าน้ำตาล" required autofocus /> -->
			<h4>วันที่/เวลา ที่บันทึก</h4>
			<input type="datetime-local" name="datetime" id="datetime" class="w-100" required autofocus >
			<input type="text" name = "user_id"  id = "user_id" value="" hidden/>
			<button type="submit"  class ="send-btn btn-primary w-100" id = "confirm-sugar">บันทึก</button>
        </div>  
		@if(session()->has('message')) 
		<div class="alert alert-success">
			{{ session()->get('message') }}
		</div>
		@endif   
	</div>
	
    </div>
</form>        
   
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
    liff.init({ liffId: "1656991660-v073Nlgm" }, () => {
      if (liff.isLoggedIn()) {
        runApp()
      } else {
        liff.login();
      }
    }, err => console.error(err.code, error.message));      
    
};

// const value = document.querySelector("#sugardata");
// const input = document.querySelector("#bloodsugar");
// value.textContent = input.value;
// input.addEventListener("input", (event) => {
//   value.textContent = event.target.value;
// });


</script>
</body>
</html>