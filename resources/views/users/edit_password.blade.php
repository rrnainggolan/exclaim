@extends('layouts.app')

@section('content')
  @if(Session::has('flash_message'))
  <div class="alert alert-{!! session('class') !!}" role="alert">
    <div class="flash-message">{!! session('flash_message') !!}</div>
  </div>
  @endif

  <div class="card mb-4">
    <div class="card-header">Change Password</div>
    <div class="card-body">

      @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif

      {{ Form::open(['route' => ['update.password', Auth::user()->id]]) }}

      <div class="form-group row">
        <div class="col-12">{{ Form::label('current_password', 'Current Password') }}</div>
        <div class="col-6">
          {{ Form::password('current_password', [
              'class' => 'form-control required', 
              'placeholder' => 'Current Password'
              ]) }}
        </div>
      </div>

      <div class="form-group row">
        <div class="col-12">{{ Form::label('password', 'New Password') }}</div>
        <div class="col-6">
          {{ Form::password('password', [
              'class' => 'form-control required', 
              'placeholder' => 'New Password'
              ]) }}
        </div>
      </div>

      <div class="form-group row">
        <div class="col-12">{{ Form::label('password_confirmation', 'Confirm Password') }}</div>
        <div class="col-6">
          {{ Form::password('password_confirmation', [
              'class' => 'form-control required', 
              'placeholder' => 'Confirm Password'
              ]) }}
        </div>
      </div>

      {{ Form::submit('Submit!', ['class' => 'btn btn-primary', 'id' => 'form-submit']) }}
      {{ Form::close() }}
    </div>
  </div>
@endsection