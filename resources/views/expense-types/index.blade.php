@extends('layouts.app')

@section('content')
  @if(Session::has('flash_message'))
    <div class="alert alert-{!! session('class') !!}" role="alert">
      <div class="flash-message">{!! session('flash_message') !!}</div>
    </div>
  @endif

  <div class="card mb-4">
    <div class="card-header">
      Expense Types 
      <div class="float-right">
        <a class="btn btn-primary" href="{{ route('expense-types.create') }}">
          Add New
        </a>
      </div>
    </div>
    <div class="card-body">
      <table class="table">
        <thead>
          <tr>
            <th scope="col" style="width: 5%">#</th>
            <th scope="col">Name</th>
            <th scope="col">Description</th>
            <th scope="col" style="width: 20%">Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($expenseTypes as $expenseType)
            <tr>
              <th scope="row">{{ $loop->iteration }}</th>
              <td>{{ $expenseType->name }}</td>
              <td>{{ $expenseType->description }}</td>
              <td>
                <a class="btn btn-light" 
                href="{{ route('expense-types.edit', $expenseType->id) }}">
                  Edit
                </a>&nbsp;&nbsp;
                <a class="btn btn-danger delete-exp-type" 
                href="javascript:void(0)" data-exptypeid="{{ $expenseType->id }}">
                  Delete
                </a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <div id="errorModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Oops, there is an error!</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p class="content">Modal body text goes here.</p>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    $('.delete-exp-type').on('click', function(e) {
      let exptypeid = $(this).attr("data-exptypeid");
      $.ajax({
        type: 'DELETE',
        url: "{{ route('expense-types.index') }}/" + exptypeid,
        headers: { 
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        data: {
          id: exptypeid,
          "_token": "{{ csrf_token() }}"
        },
        success: function (data) {
          location.reload(); 
          //console.log(data);      
        },
        error: function (data) {
          console.log(data.responseJSON.message);
          $('.modal-body .content').html(data.responseJSON.message);
          $('#errorModal').modal('show');
        }
      });
    })
  </script>
@endpush