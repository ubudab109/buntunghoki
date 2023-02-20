@extends('welcome')
@section('page_name')
    Role
@endsection
@section('content')
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Role List</h3>
          </div>
          <div class="card-body">
            @can('role-management-create')  
              <button class="btn btn-primary mr-2 float-right mb-2">
                <a href="{{route('role.create')}}" class="text-white">Create</a>
              </button>
            @endcan
            <div class="table-responsive">
              <table class="table" id="role-table">
                <thead>
                  <tr>
                    <th>Role</th>
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
      $(document).ready(() => {
        var table = $("#role-table").DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{route('role.data')}}",
          columns: [
            {data: 'role', name: 'role', class: 'text-center'},
            {data: 'action', name: 'action', orderable: false, searchable: false, class: 'text-center'},
          ]
        })
      });
    </script>
@endsection