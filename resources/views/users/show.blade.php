@extends('layouts.app')

@section('content')
  @if(Session::has('flash_message'))
    <div class="alert alert-{!! session('class') !!}" role="alert">
      <div class="flash-message">{!! session('flash_message') !!}</div>
    </div>
  @endif

  <div class="card mb-4">
    <div class="card-header">
      User Details
    </div>
    <div class="card-body">
      <ul>
        <li>Name: {{ $user->name }}</li>
        <li>Email: {{ $user->email }}</li>
        <li>Roles: 
          @foreach ($user->roles as $role)
            {{ $loop->first ? '' : ', ' }}
            {{ $role->title }}
          @endforeach  
        </li>
      </ul>
    </div>
  </div>
@endsection