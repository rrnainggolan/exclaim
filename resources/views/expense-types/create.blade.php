@extends('layouts.app')

@section('content')
  <div class="card">
    <div class="card-header">New Expense Type</div>
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

      {{ Form::open([
        'route' => 'expense-types.store', 
        'id' => 'expense_type-form'
      ]) }}

      <div class="form-group row">
        <div class="col-12">{{ Form::label('name', 'Name') }}</div>
        <div class="col-6">
          {{ Form::text('name', '', [
              'class' => 'form-control', 
              'placeholder' => 'Expense type name',
              'autocomplete' => 'off'
              ]) }}
        </div>
      </div>

      <div class="form-group row">
        <div class="col-12">{{ Form::label('description', 'Description') }}</div>
        <div class="col-9">
          {{ Form::textarea('description', '', [
            'rows' => 2,
            'placeholder' => 'Type description if any',
            'class' => 'form-control'
          ]) }}
        </div>
      </div>

      {{ Form::submit('Submit!', [
        'class' => 'btn btn-primary', 
        'id' => 'form-submit']) }}
      {{ Form::close() }}
    </div>
  </div>
@endsection