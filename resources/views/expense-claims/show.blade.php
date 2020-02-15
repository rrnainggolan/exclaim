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
        <div class="card-header">Claim Details for {{ $expenseClaim->code }}</div>
        <div class="card-body">
          <ul>
            <li>Claim Code: {{ $expenseClaim->code }}</li>
            <li>Date Submitted: {{ $expenseClaim->created_at }}</li>
            <li>Claimant: {{ $expenseClaim->user->name }}</li>
            <li>Period of Claim: 
              {{ \Carbon\Carbon::parse($expenseClaim->start_date)->format('j M, Y') }} to 
              {{ \Carbon\Carbon::parse($expenseClaim->end_date)->format('j M, Y') }}
            </li>
            <li>Cash Advance: 
              @money($expenseClaim->cash_advance, 'IDR', true)
            </li>
            <li>
              Description:
              {{ $expenseClaim->description }}
            </li>
          </ul>

          <p>Expenses:</p>
          <div id="accordion">
            @foreach ($expenseClaim->expenses as $expense)
            <div class="card">
              <div class="card-header" id="heading-{{ $expense->id }}">
                <h5 class="mb-0">
                  <button class="btn btn-link" data-toggle="collapse" 
                  data-target="#collapse-{{ $expense->id }}" aria-expanded="true" 
                  aria-controls="collapse-{{ $expense->id }}">
                  {{ $expense->expenseType->name }}: @money($expense->amount, 'IDR', true)
                  </button>
                </h5>
              </div>
              <div id="collapse-{{ $expense->id }}" class="collapse {{ $loop->first ? "show" : "" }}" 
              aria-labelledby="heading-{{ $expense->id }}" data-parent="#accordion">
                <div class="card-body">
                  <ul>
                    <li>Expense Type: {{ $expense->expenseType->name }}</li>
                    <li>Amount: @money($expense->amount, 'IDR', true)</li>
                    <li>Remarks: {{ $expense->remarks }}</li>
                    <li>
                      Attachments:
                      <ul>
                        @foreach($expense->expenseAttachments as $attachment)
                        <li>
                          <a href="{{ url('/attachments/'.$attachment->filename) }}">
                              {{ $attachment->filename }}
                          </a>
                        </li>
                        @endforeach
                      </ul>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection