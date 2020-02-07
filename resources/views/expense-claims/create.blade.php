@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-3">
      <ul class="nav flex-column">
        <li class="nav-item">
          <a class="nav-link active" href="{{ route('expense-claims.index') }}">My Claims</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('expense-claims.create') }}">Make Claim</a>
        </li>
      </ul>
    </div>
    <div class="col-md-9">
      <div class="card">
        <div class="card-header">Make Claim</div>
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
            'route' => 'expense-claims.store', 
            'id' => 'expense_claim-form',
            'files' => true
          ]) }}

            <div class="form-group row">
              <div class="col-12">{{ Form::label('period', 'Period of Claim') }}</div>
              <div class="col-6">
                {{ Form::text('period_date', '', [
                    'class' => 'form-control', 
                    'placeholder' => 'Pick date period',
                    'autocomplete' => 'off'
                    ]) }}
              </div>
            </div>

            <div class="form-group row">
              <div class="col-12">{{ Form::label('cash_advance', 'Cash Advance') }}</div>
              <div class="col-6">
                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                      <div class="input-group-text">Rp.</div>
                  </div>
                  {{ Form::text('cash_advance', '', [
                    'class' => 'form-control',
                    'placeholder' => 'Input cash advance if available'
                  ]) }}
                </div>
              </div>
            </div>

            <div class="form-group form-row">
              <div class="col-auto">
                {{ Form::select('expenses[0][type]', $expenseTypes, '', [
                  'placeholder' => 'Choose expense type',
                  'class' => 'custom-select'
                ]) }}
              </div>
              <div class="col-auto">
                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                      <div class="input-group-text">Rp.</div>
                  </div>
                  {{ Form::text('expenses[0][amount]', '', [
                  'placeholder' => 'Input the amount',
                  'class' => 'form-control'
                  ]) }}
                </div>
              </div>
              <div class="col-auto">
                {{ Form::file('expenses[0][file][]', $attributes = [
                  'id' => 'attachments1',
                  'class' => 'custom-file-input',
                  'multiple' => 'multiple',
                ]) }}
                {{ Form::label('attachments1', 'Attachments', [
                  'class' => 'custom-file-label'
                ]) }}
              </div>
              <div class="col-9">
                {{ Form::textarea('expenses[0][remarks]', '', [
                  'rows' => 4,
                  'placeholder' => 'Type remarks if any',
                  'class' => 'form-control'
                ]) }}
              </div>
            </div>


          {{ Form::submit('Submit!', ['class' => 'btn btn-primary']) }}
          {{ Form::close() }}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
  <script>
    // DATERANGE PICKER
    $('input[name="period_date"]').daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear'
        }
    });

    $('input[name="period_date"]').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
    });

    $('input[name="period_date"]').on('cancel.daterangepicker', function(ev, picker) {
      $(this).val('');
    });

    // MULTIPLE FILE INPUT 
    $('#attachments1').on('change', function(e) {
      var labelValue;
      var files = $('#attachments1').prop("files");
      var names = $.map(files, function(val) { 
        return val.name; 
      });
      
      if(names.length > 1) {
        labelValue = names.length + ' files selected';
      } else {
        labelValue = names[0];
      }

      $(this).next('.custom-file-label').html(labelValue);
    })

  </script>
@endpush