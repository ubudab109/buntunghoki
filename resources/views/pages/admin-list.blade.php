@extends('welcome')
@section('page_name')
Admin
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Admin List</h3>
      </div>
      <div class="card-body">
        @can('admin-management-create')
        <button class="btn btn-primary mr-2 float-right mb-2">
          <a href="{{route('admin.create')}}" class="text-white">Create</a>
        </button>
        @endcan
        <div class="table-responsive">
          <table class="table" id="admin-table">
            <thead>
              <tr>
                <th>Fullname</th>
                <th>Username</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Role</th>
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

  <!-- Modal -->
  <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Delete Admin?</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          All data that related to this Admin will be deleted to.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-danger" onclick="processDelete()">Delete</button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
<script>
      var table = $("#admin-table").DataTable({
        processing: true,
        serverSied: true,
        ajax: "{{route('admin.data')}}",
        columns: [
          {data: 'fullname', name: 'fullname', class: 'text-center'},
          {data: 'username', name: 'username', class: 'text-center'},
          {data: 'email', name: 'email', class: 'text-center'},
          {data: 'phone_number', name: 'phone_number', class: 'text-center'},
          {data: 'role', name: 'role', class: 'text-center'},
          {data: 'status_badge', name: 'status_badge', class: 'text-center'},
          {data: 'action', name: 'action', orderable: false, searchable: false, class: 'text-center'},
        ]
      });

      function deleteAdmin(id) {
        let url = "{{route('admin.delete', ':id')}}"
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

</script>
@endsection