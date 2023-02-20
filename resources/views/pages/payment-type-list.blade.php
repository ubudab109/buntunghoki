@extends('welcome')
@section('page_name')
Payment Type List
@endsection
@section('content')
<div class="row">
  {{-- MODAL CREATE --}}
  <div class="modal" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="labelCreate">Create Payment Type</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="formCreate">
          <div class="modal-body">
            <div class="form-group">
              <label for="name">Payment Name</label>
              <input type="text" required name="name" id="name" class="form-control">
            </div>
            <div class="form-group">
              <label for="status">Status</label>
              <select name="status" id="status" required class="form-control">
                <option value="">Select Status...</option>
                <option value="1">Active</option>
                <option value="2">Not Active</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- MODAL UPDATE --}}
  <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="labelUpdate">Detail Payment Type</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="formUpdate">
          <div class="modal-body">
            <input type="hidden" name="id" id="idUpdate">
            <div class="form-group">
              <label for="updateName">Payment Name</label>
              <input type="text" required name="updateName" id="updateName" class="form-control">
            </div>
            <div class="form-group">
              <label for="updateStatus">Status</label>
              <select name="updateStatus" id="updateStatus" required class="form-control">
                <option value="">Select Status...</option>
                <option value="1">Active</option>
                <option value="2">Not Active</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" id="submitUpdate" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Payment Type List</h3>
      </div>
      <div class="card-body">
        @can('payment-type-create')
        <button class="btn btn-primary mr-2 float-right mb-2">
          <a href="#" data-toggle="modal" data-target="#createModal" class="text-white">Create</a>
        </button>
        @endcan
        <div class="table-responsive">
          <table class="table" id="payment-type">
            <thead>
              <tr>
                <th>Name</th>
                <th>Status</th>
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
      var table = $("#payment-type").DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{route('payment.data')}}",
        columns: [
          {data: 'name', name: 'name', class: 'text-center'},
          {data: 'status_badge', name: 'status_badge', class: 'text-center'},
          {data: 'action', name: 'action', class: 'text-center', orderable: false, searchable: false},
        ]
      });

      // CREATE PAYMENT
      $("#formCreate").on('submit', e => {
        e.preventDefault();
        let url = "{{route('payment.store')}}"
        var name = $("#name").val();
        var status = $("#status").val();
        $.ajax({
          method: 'POST',
          url: url,
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          data: {
            name: name,
            status: status,
          },
        })
        .then(res => {
          if (res.status) {
            Swal.fire({
              title: 'Success',
              text: res.message,
              icon: 'success',
            }).then(() => { 
              table.ajax.reload();
            });
          } else {
            Swal.fire({
              title: 'Failed',
              text: res.message,
              icon: 'error',
            });
          }
        })
      });

      // UPDATE PAYMENT
      $("#formUpdate").on('submit', e => {
        e.preventDefault();
        var id = $("#idUpdate").val();
        let url = "{{route('payment.update', ':id')}}"
        url = url.replace(':id', id);
        var name = $("#updateName").val();
        var status = $("#updateStatus").val();
        $.ajax({
          method: 'POST',
          url: url,
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          data: {
            name: name,
            status: status,
          },
        })
        .then(res => {
          if (res.status) {
            Swal.fire({
              title: 'Success',
              text: res.message,
              icon: 'success',
            }).then(() => {
              $("#updateModal").modal('hide');
              table.ajax.reload();
            });
          } else {
            Swal.fire({
              title: 'Failed',
              text: res.message,
              icon: 'error',
            });
          }
        })
      });
      
      // DELETE PAYMENT
      function deletePayment(id) {
        let url = "{{route('payment.delete', ':id')}}"
        url = url.replace(':id', id); 
        Swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              method: 'DELETE',
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              url: url,
            })
            .then(() => {
              Swal.fire({
                title: 'Success',
                text: 'Data deleted successfully',
                icon: 'success',
              }).then(() => {
                table.ajax.reload();
              });
            });
          }
        })
      }

      // SHOW PAYMENT
      function showPaymentType(id) {
        let url = "{{route('payment.detail', ':id')}}";
        url = url.replace(':id', id);
        $.ajax({
          method: 'GET',
          url: url,
        }).then(res => {
          $("#updateName").val(res.name);
          document.getElementById('updateStatus').value = res.status;
          $("#idUpdate").val(res.id);
          if (!res.can_edit) {
            document.getElementById('submitUpdate').style.display = 'none';
            $("#updateName").attr('disabled', true);
            $("#updateStatus").attr('disabled', true);
          } else {
            document.getElementById('submitUpdate').style.display = 'block';
            $("#updateName").attr('disabled', false);
            $("#updateStatus").attr('disabled', false);
          }
          $("#updateModal").modal('show');
        });
      }
</script>
@endsection