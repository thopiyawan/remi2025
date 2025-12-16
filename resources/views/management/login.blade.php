@extends('layouts.app')
@section('script')
<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/serial.js"></script>
<script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>

<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>

<link rel="stylesheet" href="{{URL::asset('css/stylecss_test.css')}}">
<style>
body, html {
  height: 100%;
  font-family:'Kanit';
  /* line-height: 1.52857143; */
}

* {
  box-sizing: border-box;
}

.bg-img {
  /* The image used */

  
  background-image: url(https://service.foodieat.in.th/remi/image/PRINT_REMI.jpg);
  /* background-image:linear-gradient(to bottom, rgba(255,255,0,0.5), rgba(0,0,255,0.5)),url('catfront.png'); */
  min-height: 2879px;

  /* Center and scale the image nicely */
  /* background-position: center; */
  background-repeat: no-repeat;
  background-size: cover;
  position: relative;
}

/* Add styles to the form container */

/* Full-width input fields */
input[type=text], input[type=password] {
  width: 100%;
  padding: 15px;
  margin: 5px 0 22px 0;
  border: none;
  background: #f1f1f1;
  font-family:'Kanit';
}

input[type=text]:focus, input[type=password]:focus {
  background-color: #ddd;
  outline: none;
  font-family:'Kanit';
}

.form-control {
    display: block;
    width: 100%;
    padding: 0.375rem 0.75rem;
    font-size: 1.5rem;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    font-family:Kanit;
    border-radius: 0.25rem;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
}

/* Set a style for the submit button */
.btn {
  background-color: #FCA1AE;
  color: white;
  padding: 16px 20px;
  border: none;
  cursor: pointer;
  width: 100%;
  opacity: 0.9;
  font-family:Kanit;
}

.btn:hover {
  opacity: 1;
}

.banner {
    padding-top: 15px;
    display: table;
    width: 100%;
    margin: 10 auto;
    table-layout: fixed;
}

.banner img {
    display: table-cell;
    width: 100% !important;
    text-align: center;
    vertical-align: middle;
    line-height: normal;
    margin: 0 auto;
    max-width: 90%; 
    max-height: 90%;
}
#sponsor_text{
    padding: 16px 20px;

}
#remi-logo img{
    display: table-cell;
    /* width: 100% !important; */
    text-align: center; 
    vertical-align: middle;
    line-height: normal;
    /* margin: 0 auto; */
    max-width: 17%; 
    max-height: 17%;
    margin: 3px 0 15px 0;
}

#info-remi img{
    max-width: 100%; 
    max-height: 100%;
    border-radius: 0px 19px 19px 0px;

}
.bg-primary {
    background-color: #FFBBCC!important;
}

.fixed-top {
    position: fixed;
    top: 0;
    /* right: 0; */
    left: 0;
    /* z-index: 1030; */
}

@import 'https://fonts.googleapis.com/css?family=Kanit|Prompt';
.login-block{
    background:#FFBBCC;  /* fallback for old browsers */
background: -webkit-linear-gradient(to bottom, #FFEECC,#FFBBCC);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to bottom, #FFEECC,#FFBBCC); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
float:left;
width:100%;
padding : 50px 0;
}
.banner-sec{background:url()  no-repeat left bottom; background-size:cover; min-height:400px; border-radius: 0 10px 10px 0; padding:0;}
.container{background:#fff; border-radius: 20px; box-shadow:15px 20px 0px rgba(0,0,0,0.1);}
.carousel-inner{border-radius:0 10px 10px 0;}
.carousel-caption{text-align:left; left:5%;}
.login-sec{padding: 20px 30px; position:relative;}
.login-sec .copy-text{position:absolute; width:80%; bottom:20px; font-size:13px; text-align:center;}
.login-sec .copy-text i{color:#FEB58A;}
.login-sec .copy-text a{color:#E36262;}
.login-sec h2{margin-bottom:20px; font-weight:500; font-size:25px; color: #FCA1AE; font-family:Kanit;}
.login-sec h2:after{content:" "; width:100px; height:3px; background:#FEB58A; display:block; margin-top:20px; border-radius:3px; margin-left:auto;margin-right:auto}
.btn-login{background: #FCA1AE; color:#fff; font-weight:600; font-size:13px; }
.banner-text{width:70%; position:absolute; bottom:40px; padding-left:20px;}
.banner-text h2{color:#fff; font-weight:600;}
.banner-text h2:after{content:" "; width:100px; height:5px; background:#FFF; display:block; margin-top:20px; border-radius:3px;}
.banner-text p{color:#fff;}
.text-uppercase {
  font-size:13px;
  font-family: 'Kanit';


}
</style>
@endsection
@section('content')
<section class="login-block">
    <div class="container">
	<div class="row">
		<div class="col-md-4 login-sec">
        <div id="remi-logo" style="overflow: hidden; display: flex; justify-content:space-around;">
            <img src ="https://service.foodieat.in.th/remi/css/remi.png">
        </div>
		    <h2 class="text-center">ลงชื่อเข้าใช้</h2>
            <form method="POST" action="{{route('login')}}">
            {{ csrf_field() }}   
            <div class="form-group">
                <label for="doctor_id" class="text-uppercase">{{ __('รหัสประจำตัวแพทย์') }}</label>
                <input id="doctor_id" type="text" class="form-control{{ $errors->has('doctor_id') ? ' is-invalid' : '' }}" name="doctor_id" value="{{ old('doctor_id') }}" required autofocus>
                
            </div>
            <div class="form-group">
                <label for="password" class="text-uppercase">{{ __('รหัสผ่าน') }}</label>
                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required value="">

                @if ($errors->has('password'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
            <div>
            <button type="submit" class="btn btn-login float-right">{{ __('เข้าสู่ระบบ') }} </button>

                    @if (Route::has('password.request'))
                        <a class="btn btn-link" href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
                    @endif
                
            </div>
                <br>
                <br>
                <br>
                <br>
                <br>
                <!-- <div class="text-center"> ภายใต้ความร่วมมือ </div>      -->
                <h4  class="text-center">ภายใต้ความร่วมมือ </h4>
                <div id="banner" style="overflow: hidden; display: flex; justify-content:space-around;">

                <div class="banner">
                    <img src ="https://service.foodieat.in.th/remi/sponsor_logo/logo_1.jpg">
                </div>
                <div class="banner">
                    <img src ="https://service.foodieat.in.th/remi/sponsor_logo/logo_2.png">
                </div>
                <div class="banner">
                    <img src ="https://service.foodieat.in.th/remi/sponsor_logo/logo_3.png">
                </div>
                <div class="banner">
                    <img src ="https://service.foodieat.in.th/remi/sponsor_logo/logo_4.png">
                </div>
                <div class="banner">
                    <img src ="https://service.foodieat.in.th/remi/sponsor_logo/logo_5.png">
                </div>
                <div class="banner">
                    <img src ="https://service.foodieat.in.th/remi/sponsor_logo/logo_6.png">
                </div>
                </div>
        </form>
		</div>
		<!-- <div class="col-md-8 banner-sec">
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                 <ol class="carousel-indicators">
                    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                  </ol> -->
    <!-- <div class="carousel-inner" role="listbox">
        <div class="carousel-item active">
        <img class="d-block img-fluid" src="https://service.foodieat.in.th/remi/image/S__14032899.jpg" alt="First slide">
        </div>
        <div class="carousel-item">
        <img class="d-block img-fluid" src="https://service.foodieat.in.th/remi/image/S__14032901.jpg" alt="First slide">
        </div>
        <div class="carousel-item">
        <img class="d-block img-fluid" src="https://service.foodieat.in.th/remi/image/S__14032902.jpg" alt="First slide">
        </div>
    </div>	    -->

        <!-- <div id="remi-logo" style="overflow: hidden; display: flex; justify-content:space-around;"> -->
        <div class="col-md-8 banner-sec">
        <div id="info-remi" class="carousel slide" data-ride="carousel">
            <img src ="https://service.foodieat.in.th/remi/image/PRINT_REMI.jpg">
        </div>
        </div>
		    
            </div>
        </div>
    </div>            
</section>

@endsection
