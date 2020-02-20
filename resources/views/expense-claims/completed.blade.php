@extends('layouts.app')

@section('content')
  @if(Session::has('flash_message'))
    <div class="mt-4 alert alert-{!! session('class') !!}" role="alert">
      <div class="flash-message">{!! session('flash_message') !!}</div>
    </div>
  @endif

  <div class="card mb-4">
    <div class="card-header">Completed Expenses Claims</div>
    <div class="card-body">
      <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Code</th>
            <th scope="col">Claimant</th>
            <th scope="col" class="d-none d-sm-table-cell">Period</th>
            <th scope="col" class="d-none d-sm-table-cell">Amount</th>
            <th scope="col">Status</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($completedExpenseClaims as $completedExpenseClaim)
            <tr>
              <th scope="row">{{ $loop->iteration }}</th>
              <td>
                <a href="{{ route('expense-claims.show', $completedExpenseClaim->id) }}">
                    EXP-{{ str_pad($completedExpenseClaim->id, 6, '0', STR_PAD_LEFT) }}
                </a>
              </td>
              <td>{{ $completedExpenseClaim->user_name }}</td>
              <td class="d-none d-sm-table-cell">
                {{ \Carbon\Carbon::parse($completedExpenseClaim->start_date)
                  ->format('j M, Y') }} -
                {{ \Carbon\Carbon::parse($completedExpenseClaim->end_date)
                  ->format('j M, Y') }}
              </td>
              <td class="d-none d-sm-table-cell">
                  @money(($completedExpenseClaim->amount_total - $completedExpenseClaim
                  ->cash_advance), 'IDR', true)
              </td>
              <td>
                {{ $completedExpenseClaim->total_approved == 2 ? "Approved" : "Rejected" }}
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection