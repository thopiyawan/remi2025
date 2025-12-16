<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>REMI</title>
	<link rel=icon href="https://service.foodieat.in.th/remi/css/remi.png">
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<style>

		.navbar-brand {
		    color: #ffffff  !important;
		}
		.dot_0 {
		  height: 25px;
		  width: 25px;
		  background-color: #5DADE2;
		  border-radius: 50%;
		  display: inline-block;
		}
		.dot_1 {
		  height: 25px;
		  width: 25px;
		  background-color: #27AE60;
		  border-radius: 50%;
		  display: inline-block;
		}
		.dot_2 {
		  height: 25px;
		  width: 25px;
		  background-color: #F7DC6F;
		  border-radius: 50%;
		  display: inline-block;
		}
		.dot_3 {
		  height: 25px;
		  width: 25px;
		  background-color: #EB984E;
		  border-radius: 50%;
		  display: inline-block;
		}
		.dot_4 {
		  height: 25px;
		  width: 25px;
		  background-color: #CD5C5C;
		  border-radius: 50%;
		  display: inline-block;
		}
		
		.him{
		  background: #eee;
		  float: left;
		}
		
		.me{
		  float: right;
		  background: #0084ff;
		  color: #fff;
		}
		
		.him + .me{
		  border-bottom-right-radius: 5px;
		}
		
		.me + .me{
		  border-top-right-radius: 5px;
		  border-bottom-right-radius: 5px;
		}
		
		.me:last-of-type {
		  border-bottom-right-radius: 30px;
		}
		
		.box_remi {
		  margin: 5px auto;
		  background: #00bfb6;
		  padding: 5px;
		  text-align: right;
		  color: #fff;
		  position:relative;
		  text-overflow: ellipsis;
		    white-space: nowrap;
		    overflow: hidden;
		}
		
		.box_doctor {
		  width: 300px;
		  margin: 50px auto;
		  background: #00bfb6;
		  padding: 20px;
		  text-align: right;
		  font-weight: 900;
		  color: #fff;
		  position:relative;
		}
		
	</style>
	@yield('script')
</head>
<body>
	@if (Session::get('doctor_name'))
	<nav class="navbar navbar-dark bg-primary fixed-top">
	  <a class="navbar-brand" href="#">
		<img src="https://service.foodieat.in.th/remi/css/remi.png" width="100%" height="100%">
	  </a>
	  <a class="navbar-brand" href="#">REMI</a>
	  <a class="navbar-brand" href="/remi/dashboard">Dashboard</a>
	  <a class="navbar-brand navbar-right" href="/remi/logout">Logout &nbsp;</a>	  
	</nav>
	@endif
	
    <div id="app">
        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>

