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
        <div class="card-header">My Claims</div>
        <div class="card-body">

          <table class="table">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Code</th>
                <th scope="col">Period</th>
                <th scope="col">Total Amount</th>
                <th scope="col">Submitted at</th>
              </tr>
            </thead>
            <tbody>
              @foreach($expenseClaims as $expenseClaim)
              <tr>
                <th scope="row">{{ $loop->iteration }}</th>
                <td>
                  <a href="{{ route('expense-claims.show', $expenseClaim->id) }}">
                    {{ $expenseClaim->code }}
                  </a>
                </td>
                <td>
                  {{ \Carbon\Carbon::parse($expenseClaim->start_date)->format('j M, Y') }} to 
                  {{ \Carbon\Carbon::parse($expenseClaim->end_date)->format('j M, Y') }}
                </td>
                <td>@money($expenseClaim->amount_total, 'IDR', true)</td>
                <td>{{ $expenseClaim->created_at }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection