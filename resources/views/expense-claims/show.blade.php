@extends('layouts.app')

@section('content')
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
        @if($expenseClaim->rejected_by)
          <div class="mt-4 alert alert-danger" role="alert">
            <p>This claim has been rejected by:</p>
            <ul>
              <li>
                {{ $expenseClaim->rejected_by->user->name }} on 
                {{ \Carbon\Carbon::parse($expenseClaim->rejected_by->created_at)
                  ->format('j M, Y') }}
              </li>
              <li>Reason: {{ $expenseClaim->rejected_by->reason }}</li>
            </ul>
          </div>
        @elseif($expenseClaim->expenseClaimsApproved->sum('approved') === 2)
          <div class="mt-4 alert alert-success" role="alert">
            <p>This claim has been completed, and approved by:</p>
            <ul>
              @foreach($expenseClaim->expenseClaimsApproved as $expenseClaimApproved)
                <li>
                  {{ $expenseClaimApproved->user->name }} on
                  {{ \Carbon\Carbon::parse($expenseClaimApproved->created_at)
                    ->format('j M, Y') }}
                </li>
              @endforeach
            </ul>
          </div>
        @else
          @can('approve', $expenseClaim)
            @if($expenseClaim->getApprovedByAttribute(Auth::user()->id))
              <div class="mt-4 alert alert-info" role="alert">
                <p>You already approved this claim.</p>
              </div>
            @else
              @if($expenseClaim->expenseClaimsApproved->first())
                <div class="mt-4 alert alert-info" role="alert">
                  <p>
                    This claim is currently approved by: 
                    {{ $expenseClaim->expenseClaimsApproved->first()->user->name }}
                  </p>
                </div>
              @endif
              <div class="mt-4 float-right">
                <a href="" class="btn btn-primary" data-toggle="modal" data-target="#approveModal">Approve</a>
                <a href="" class="btn btn-danger" data-toggle="modal" data-target="#rejectModal">Reject</a>
              </div>

              <div class="modal fade" id="approveModal" tabindex="-1" role="dialog" 
              aria-labelledby="approveModalTitle">
                <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                    {{ Form::open([
                      'route' => ['expense-claims.approve', $expenseClaim->id], 
                      'id' => 'expense_claim-form-approve'
                    ]) }}
                    {{ Form::hidden('user_id', Auth::user()->id) }}
                    <div class="modal-header">
                      <h5 class="modal-title" id="approveModalTitle">Approval Confirmation</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span  aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      Are you sure you want to approve this claim by {{ $expenseClaim->user->name }}?
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                      <button type="submit" class="btn btn-primary">Yes, Continue</button>
                    </div>
                    {{ Form::close() }}
                  </div>
                </div>
              </div>

              <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" 
              aria-labelledby="rejectModalTitle">
                <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                    {{ Form::open([
                      'route' => ['expense-claims.reject', $expenseClaim->id], 
                      'id' => 'expense_claim-form-reject'
                    ]) }}
                    {{ Form::hidden('user_id', Auth::user()->id) }}
                    <div class="modal-header">
                      <h5 class="modal-title" id="rejectModalTitle">Reject Confirmation</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span  aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      {{ Form::textarea('reason', '', [
                        'rows' => 5,
                        'placeholder' => 'Type the reason for this rejection',
                        'class' => 'form-control'
                      ]) }}
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                      <button type="submit" class="btn btn-danger">Reject</button>
                    </div>
                    {{ Form::close() }}
                  </div>
                </div>
              </div>
            @endif
          @endcan
        @endif
    </div>
  </div>
@endsection