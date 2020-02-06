@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-3">
      <ul class="nav flex-column">
        <li class="nav-item">
        <a class="nav-link active" href="{{ route('home') }}">My Claims</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('expense-claims.create') }}">Make Claim</a>
        </li>
      </ul>
    </div>
    <div class="col-md-9">
      <div class="card">
        <div class="card-header">My Claims</div>

        <div class="card-body">
          @if (session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
          @endif

          You are logged in!
        </div>
      </div>
    </div>
  </div>
</div>
@endsection