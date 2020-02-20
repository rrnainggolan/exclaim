@extends('layouts.app')

@section('content')
  @if(Session::has('flash_message'))
    <div class="alert alert-{!! session('class') !!}" role="alert">
      <div class="flash-message">{!! session('flash_message') !!}</div>
    </div>
  @endif

  <div class="card mb-4">
    <div class="card-header">My Expenses Claims</div>
    <div class="card-body">
      <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Code</th>
            <th scope="col" class="d-none d-sm-table-cell">Period</th>
            <th scope="col">Amount</th>
            <th scope="col" class="d-none d-sm-table-cell">Submitted at</th>
          </tr>
        </thead>
        <tbody>
          @foreach($myPendingExpenseClaims as $myPendingExpenseClaim)
          <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>
              <a href="{{ route('expense-claims.show', $myPendingExpenseClaim->id) }}">
                EXP-{{ str_pad($myPendingExpenseClaim->id, 6, '0', STR_PAD_LEFT) }}
              </a>
            </td>
            <td class="d-none d-sm-table-cell">
              {{ \Carbon\Carbon::parse($myPendingExpenseClaim->start_date)
                ->format('j M, Y') }} - 
              {{ \Carbon\Carbon::parse($myPendingExpenseClaim->end_date)
                ->format('j M, Y') }}
            </td>
            <td>
              @money(($myPendingExpenseClaim->amount_total - $myPendingExpenseClaim
                ->cash_advance), 'IDR', true)
            </td>
            <td class="d-none d-sm-table-cell">{{ $myPendingExpenseClaim->created_at }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-header">My Completed Expenses Claims</div>
    <div class="card-body">
      <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Code</th>
            <th scope="col" class="d-none d-sm-table-cell">Period</th>
            <th scope="col" class="d-none d-sm-table-cell">Amount</th>
            <th scope="col">Status</th>
          </tr>
        </thead>
        <tbody>
          @foreach($myCompletedExpenseClaims as $myCompletedExpenseClaim)
          <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>
              <a href="{{ route('expense-claims.show', $myCompletedExpenseClaim->id) }}">
                EXP-{{ str_pad($myCompletedExpenseClaim->id, 6, '0', STR_PAD_LEFT) }}
              </a>
            </td>
            <td class="d-none d-sm-table-cell">
              {{ \Carbon\Carbon::parse($myCompletedExpenseClaim->start_date)
                ->format('j M, Y') }} - 
              {{ \Carbon\Carbon::parse($myCompletedExpenseClaim->end_date)
                ->format('j M, Y') }}
            </td>
            <td class="d-none d-sm-table-cell">
              @money(($myCompletedExpenseClaim->amount_total - $myCompletedExpenseClaim
                ->cash_advance), 'IDR', true)
            </td>
            <td>{{ $myCompletedExpenseClaim->total_approved == 2 ? "Approved" : "Rejected" }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection