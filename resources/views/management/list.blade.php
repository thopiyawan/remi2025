@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
			Hi Doctor --> {{ $posts }}
        </div>
    </div>
</div>
@endsection
