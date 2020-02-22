@extends('layouts.app')

@section('content')
  @if(Session::has('flash_message'))
  <div class="alert alert-{!! session('class') !!}" role="alert">
    <div class="flash-message">{!! session('flash_message') !!}</div>
  </div>
  @endif

  <div class="card mb-4">
    <div class="card-header">Edit User: {{ $user->name }}</div>
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

      {{ Form::model($user, [
        'route' => [
            'users.update',
            $user->id
        ],
        'method' => 'PUT',
        'id' => 'user-form'
        ]) }}

      <div class="form-group row">
        <div class="col-12">{{ Form::label('name', 'Name') }}</div>
        <div class="col-6">
          {{ Form::text('name', null, [
              'class' => 'form-control required', 
              'placeholder' => 'Enter Name'
              ]) }}
        </div>
      </div>

      <div class="form-group row">
        <div class="col-12">{{ Form::label('email', 'Email') }}</div>
        <div class="col-6">
          {{ Form::text('email', null, [
              'class' => 'form-control required', 
              'placeholder' => 'Enter Email'
              ]) }}
        </div>
      </div>

      <div class="form-group row">
        <div class="col-12">{{ Form::label('role', 'Role') }}</div>
        <div class="col-6">
          {{ Form::select('role',
          [
              'user' => 'User',
              'admin' => 'Admin'
          ],
          $user->roles[0]->name, ['class' => 'custom-select form-control required']) }}
        </div>
      </div>

      {{ Form::submit('Submit!', ['class' => 'btn btn-primary', 'id' => 'form-submit']) }}
      {{ Form::close() }}
    </div>
  </div>
@endsection

@push('scripts')
  <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
  <script>
    $("#user-form").validate({
      errorPlacement: function errorPlacement(error, element) {
        element.before(error);
      },
      errorClass: 'text-danger',
      rules: {
        email: {
          email: true
        }
      }
    });
  </script>
@endpush