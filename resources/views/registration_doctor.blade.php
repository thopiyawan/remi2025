@extends('layouts.app')
@section('content')
 

   
<div class="container">
    <form method="POST" action="/doctor_register">
        {{ csrf_field() }}

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                 <h2>Register</h2>
         <div class="card-body">
          <div class="form-group row">
                            <label for="doctor_id" class="col-md-4 col-form-label text-md-right">{{ __('Doctor ID') }}</label>

                            <div class="col-md-6">
                                <input id="doctor_id" type="text" class="form-control{{ $errors->has('doctor_id') ? ' is-invalid' : '' }}" name="doctor_id" value="{{ old('doctor_id') }}" required autofocus>

                            </div>
                        </div>
          <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

                            </div>
                        </div>

          <div class="form-group row">
                            <label for="lastname" class="col-md-4 col-form-label text-md-right">{{ __('Lastname') }}</label>

                            <div class="col-md-6">
                                <input id="lastname" type="text" class="form-control{{ $errors->has('lastname') ? ' is-invalid' : '' }}" name="lastname" value="{{ old('lastname') }}" required autofocus>

                            </div>
                        </div>

          <div class="form-group row">
                            <label for="hospital" class="col-md-4 col-form-label text-md-right">{{ __('Hospital') }}</label>

                            <div class="col-md-6">
                                <input id="hospital" type="text" class="form-control{{ $errors->has('hospital') ? ' is-invalid' : '' }}" name="hospital" value="{{ old('hospital') }}" required autofocus>

                            </div>
                        </div>

           <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="text" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" value="{{ old('password') }}" required autofocus>

                            </div>
                        </div>

                <div class="form-group">
                    <button style="cursor:pointer" type="submit" class="btn btn-primary">Submit</button>
                </div>
         </div>

      
       
    </form>
</div >
@endsection

