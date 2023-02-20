@extends('welcome')
@section('page_name')
Admin Detail
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Admin Data</h3>
      </div>
      <div class="card-body">
        <form action="{{$isCanEdit ? route('admin.update', $user->id) : '#'}}" method="post">
          @csrf
          <div class="form-row">
            <div class="col">
              <div class="form-group">
                <label for="fullname">Fullname</label>
                <input {{!$isCanEdit ? 'disabled' : '' }} type="text" placeholder="Fullname" required name="fullname"
                  id="fullname" class="form-control" value="{{$user->fullname}}">
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label for="email">Email</label>
                <input {{!$isCanEdit ? 'disabled' : '' }} type="email" placeholder="example@example.com" required
                  name="email" id="email" class="form-control" value="{{$user->email}}">
              </div>
            </div>
          </div>

          <div class="form-row">
            <div class="col">
              <div class="form-group">
                <label for="username">Username</label>
                <input {{!$isCanEdit ? 'disabled' : '' }} type="text" placeholder="Username" required name="username"
                  id="username" class="form-control" value="{{$user->username}}">
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label for="role">Role</label>
                <select {{!$isCanEdit ? 'disabled' : '' }} name="role" id="role" required class="form-control">
                  <option value="">Select Role...</option>
                  @foreach ($roles as $role)
                  <option value="{{$role->id}}" {{$user->roles[0]->id == $role->id ? 'selected' :
                    ''}}>{{ucwords($role->name)}}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="phone_number">Phone Number</label>
            <input type="number" {{!$isCanEdit ? 'disabled' : '' }} placeholder="0858XXXXX" required name="phone_number"
              id="phone_number" class="form-control" value="{{$user->phone_number}}">
          </div>

          <div class="btn-group" role="group" aria-label="Basic example">
            @can('admin-management-edit')
            <button type="submit" id="submit" class="btn btn-primary mr-2">Save</button>
            @endcan
            <button type="button" class="btn btn-danger">
              <a href="{{route('admin.index')}}" class="text-white">Back</a>
            </button>
          </div>
        </form>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <ul class="nav nav-pills">
          <li class="nav-item">
            <a class="nav-link active" href="#bank" data-toggle="tab">Bank</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#transaction" data-toggle="tab">Transaction History</a>
          </li>
        </ul>
      </div>
      <div class="card-body">
        <div class="tab-content">
          {{-- BANK --}}
          <div class="tab-pane active" id="bank">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="payment">Filter Payment Type</label>
                  <select name="payment" id="payment" class="form-control">
                    <option value="">All</option>
                    @foreach ($paymentTypes as $item)
                    <option value="{{$item->id}}">{{$item->name}}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label for="bank">Filter Bank</label>
                  <select disabled name="bank" id="bank" class="form-control">
                    <option value="">All</option>
                    @foreach ($paymentTypes as $item)
                    <option value="{{$item->id}}">{{$item->name}}</option>
                    @endforeach
                  </select>
                  <span class="text-bold" id="fetching"></span>
                </div>
              </div>
            </div>
            @can('admin-bank-create')
            <button class="btn btn-primary mr-2 float-right mb-2">
              <a href="/admin-bank/create?admin_id={{$user->id}}" class="text-white">Create</a>
            </button>
            @endcan
            <div class="table-responsive">
              <table class="table" id="table-bank">
                <thead>
                  <tr>
                    <th>Payment Type</th>
                    <th>Bank Type</th>
                    <th>Account Number</th>
                    <th>Account Name</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>

          {{-- TRANSACTION --}}
          <div class="tab-pane" id="transaction">
            <div class="table-responsive">
              <table class="table" id="transaction-table">
                <thead>
                  <tr>
                    <th>Member ID</th>
                    <th>Member Username</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Status</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
    <script>
      var tableBank = $("#table-bank").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: "{{route('admin-bank.data')}}",
          data: function (data) {
            data.admin_id = "{{$user->id}}"
            data.payment = $("#payment").val()
            data.bank = $("#bank").val()
          }
        },
        columns: [
          {data: 'payment_types', name: 'payment_types', class: 'text-center'},
          {data: 'bank_name', name: 'bank_name', class: 'text-center'},
          {data: 'account_name', name: 'account_name', class: 'text-center'},
          {data: 'account_number', name: 'account_number', class: 'text-center'},
          {data: 'status_badge', name: 'status_badge', class: 'text-center'},
        ]
      });
    </script>
@endsection