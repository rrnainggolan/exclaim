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

            <div class="expenses">
              <p>Expenses: <a href="javascript:void(0);" id="addExpense">Add more expenses</a></p>
              <div class="expense-0">
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
                          {{ Form::hidden('expenses[0][currency]', 1) }}
                      </div>
                      {{ Form::text('expenses[0][amount]', '', [
                      'placeholder' => 'Input the amount',
                      'class' => 'form-control'
                      ]) }}
                    </div>
                  </div>
                  <div class="col-auto">
                    {{ Form::file('expenses[0][file][]', $attributes = [
                      'id' => 'attachments0',
                      'class' => 'custom-file-input',
                      'multiple' => 'multiple',
                    ]) }}
                    {{ Form::label('attachments0', 'Attachments', [
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
    $(document).on('change', '.custom-file-input', function(e) {
      var labelValue;
      var files = $(this).prop("files");
      var names = $.map(files, function(val) { 
        return val.name; 
      });
      
      if(names.length > 1) {
        labelValue = names.length + ' files selected';
      } else {
        labelValue = names[0];
      }

      $(this).next('.custom-file-label').html(labelValue);
    });

    // ADD MULTIPLE EXPENSES
    var x = 1;
    var addExpense = $('#addExpense');
    var wrapper = $('.expenses');
    var expenseHTML;
    var expenseTypes = @json($expenseTypes);

    // on add expense click
    addExpense.on('click', function() {
      expenseHTML = '<div class="form-group form-row">';

      // type
      expenseHTML += '<div class="col-auto"><select class="custom-select" name="expenses[' + x + '][type]"><option selected="selected" value="">Choose expense type</option>';

      Object.keys(expenseTypes).forEach(function(key) {
        expenseHTML += '<option value="' + key + '">' + expenseTypes[key] + ' </option>';
      });
        
      expenseHTML += '</option></select></div>';

      // amount
      expenseHTML += '<div class="col-auto"><div class="input-group mb-2"><div class="input-group-prepend"><div class="input-group-text">Rp.</div><input name="expenses[' + x +'][currency]" type="hidden" value="1"></div><input placeholder="Input the amount" class="form-control" name="expenses[' + x + '][amount]" type="text" value=""></div></div>';

      // attachments
      expenseHTML += '<div class="col-auto"><input id="attachments' + x + '" class="custom-file-input" multiple="multiple" name="expenses[' + x + '][file][]" type="file"><label for="attachments' + x + '" class="custom-file-label">Attachments</label></div>';

      // remarks
      expenseHTML += '<div class="col-9"><textarea rows="4" placeholder="Type remarks if any" class="form-control" name="expenses[' + x + '][remarks]" cols="50"></textarea></div>';

      // remove expense link
      expenseHTML += '<div class="col-auto"><a href="javascript:void(0);" class="remove-expense">Remove this expense</a></div>';
      
      expenseHTML += '</div>';

      wrapper.append(expenseHTML);
      x++;
    });

    // on remove expense click
    $(wrapper).on('click', '.remove-expense', function(e){
        e.preventDefault();
        $(this).parent('div').parent('div').remove(); //Remove field html
    });

  </script>
@endpush