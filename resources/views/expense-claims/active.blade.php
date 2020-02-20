@extends('layouts.app')

@section('content')
  @if(Session::has('flash_message'))
    <div class="alert alert-{!! session('class') !!}" role="alert">
      <div class="flash-message">{!! session('flash_message') !!}</div>
    </div>
  @endif

  <div class="card mb-4">
    <div class="card-header">Expenses Claims Waiting For My Approvals</div>
    <div class="card-body">
      <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Code</th>
            <th scope="col">Claimant</th>
            <th scope="col" class="d-none d-sm-table-cell">Period</th>
            <th scope="col">Amount</th>
            <th scope="col" class="d-none d-sm-table-cell">Submitted at</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($activeExpenseClaims['unapproved'] as $activeExpenseClaim)
            <tr>
              <th scope="row">{{ $loop->iteration }}</th>
              <td>
                <a href="{{ route('expense-claims.show', $activeExpenseClaim->id) }}">
                    EXP-{{ str_pad($activeExpenseClaim->id, 6, '0', STR_PAD_LEFT) }}
                </a>
              </td>
              <td>{{ $activeExpenseClaim->user_name }}</td>
              <td class="d-none d-sm-table-cell">
                {{ \Carbon\Carbon::parse($activeExpenseClaim->start_date)
                  ->format('j M, Y') }} -
                {{ \Carbon\Carbon::parse($activeExpenseClaim->end_date)
                  ->format('j M, Y') }}
              </td>
              <td>
                  @money(($activeExpenseClaim->amount_total - $activeExpenseClaim
                  ->cash_advance), 'IDR', true)
              </td>
              <td class="d-none d-sm-table-cell">{{ $activeExpenseClaim->created_at }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-header">Expenses Claims Already Approved By Me</div>
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
          @foreach ($activeExpenseClaims['approved'] as $activeExpenseClaim)
            <tr>
              <th scope="row">{{ $loop->iteration }}</th>
              <td>
                <a href="{{ route('expense-claims.show', $activeExpenseClaim->id) }}">
                    EXP-{{ str_pad($activeExpenseClaim->id, 6, '0', STR_PAD_LEFT) }}
                </a>
              </td>
              <td class="d-none d-sm-table-cell">
                {{ \Carbon\Carbon::parse($activeExpenseClaim->start_date)
                  ->format('j M, Y') }} -
                {{ \Carbon\Carbon::parse($activeExpenseClaim->end_date)
                  ->format('j M, Y') }}
              </td>
              <td>
                  @money(($activeExpenseClaim->amount_total - $activeExpenseClaim
                  ->cash_advance), 'IDR', true)
              </td>
              <td class="d-none d-sm-table-cell">{{ $activeExpenseClaim->created_at }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection