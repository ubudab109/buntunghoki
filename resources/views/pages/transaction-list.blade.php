@extends('welcome')
@section('page_name')
Member Transaction
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Member Transaction List</h3>
      </div>
      <div class="card-body">
        <div class="col-md-6">
          <div class="form-group">
            <label for="payment">Filter Transaction Type</label>
            <select name="transaction_type" id="transaction_type" class="form-control">
              <option value="">All</option>
              <option value="deposit">Deposit</option>
              <option value="withdraw">Withdraw</option>
            </select>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table" id="member-transaction">
            <thead>
              <tr>
                <th>Transaction ID</th>
                <th>Member</th>
                <th>Member Bank</th>
                <th>Type</th>
                <th>Admin Bank</th>
                <th>Amount</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
<script>
      var table = $("#member-transaction").DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{route('transaction.data')}}",
        ajax: {
          url: "{{route('transaction.data')}}",
          data: function (data) {
            data.type = $("#transaction_type").val()
          }
        },
        columns: [
          {data: 'id', name: 'id', class: 'text-center'},
          {data: 'members', name: 'members', class: 'text-center'},
          {data: 'memberBanks', name: 'memberBanks', class: 'text-center'},
          {data: 'type', name: 'type', class: 'text-center'},
          {data: 'adminBanks', name: 'adminBanks', class: 'text-center'},
          {data: 'amount', name: 'amount', class: 'text-center'},
          {data: 'action', name: 'action', class: 'text-center', orderable: false, searchable: false},
        ]
      });

      $("#transaction_type").change(e => {
        table.draw();
      });
      
      function approve(id) {
        let url = "{{route('transaction.update', ':id')}}"
        url = url.replace(':id', id); 
        Swal.fire({
          title: 'Are you sure Want To Approve This Transaction?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, approve it!'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              method: 'POST',
              data: {
                status: '1'
              },
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              url: url,
            })
            .then((res) => {
              if (!res.status) {
                Swal.fire({
                  title: 'Failed',
                  text: res.message,
                  icon: 'error',
                })
              } else {
                Swal.fire({
                  title: 'Success',
                  text: 'Data Approved successfully',
                  icon: 'success',
                }).then(() => {
                  table.ajax.reload();
                });
              }
            });
          }
        })
      }

      function reject(id) {
        let url = "{{route('transaction.update', ':id')}}"
        url = url.replace(':id', id); 
        Swal.fire({
          title: 'Are you sure Want To Reject This Transaction?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, reject it!'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              method: 'POST',
              data: {
                status: '2'
              },
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              url: url,
            })
            .then(() => {
              Swal.fire({
                title: 'Success',
                text: 'Data Rejected successfully',
                icon: 'success',
              }).then(() => {
                table.ajax.reload();
              });
            });
          }
        })
      }
</script>
@endsection