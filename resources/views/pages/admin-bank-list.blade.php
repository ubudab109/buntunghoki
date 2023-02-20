@extends('welcome')
@section('page_name')
Admin Bank
@endsection
@section('content')
<div class="row">
  {{-- MODAL UPDATE OR DETAIL --}}
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
              <label for="updatePaymentId">Payment Type</label>
              <select required required name="payment_type_id" id="updatePaymentId" class="form-control">
                <option value="">Select Payment Type...</option>
                @foreach ($paymentTypes as $item)
                <option value="{{$item->id}}">{{$item->name}}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label for="updateBankName">Bank Name</label>
              <select required name="updateBankName" id="updateBankName" class="form-control">
                <option value="">Select Bank Name...</option>
              </select>
              <span class="text-bold" id="fetchingUpdate"></span>
            </div>

            <div class="form-group">
              <label for="updateAccountName">Account Name</label>
              <input required placeholder="Ex: John Doe" type="text" name="updateAccountName" id="updateAccountName"
                class="form-control">
            </div>

            <div class="form-group">
              <label for="updateAccountNumber">Account Number</label>
              <input required placeholder="Ex: 56789009" type="text" name="updateAccountNumber" id="updateAccountNumber"
                class="form-control">
            </div>

            <div class="form-group">
              <label for="updateStatus">Status</label>
              <select required name="status" id="updateStatus" required class="form-control">
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
        <h3 class="card-title">Admin Bank</h3>
      </div>
      <div class="card-body">
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

          <div class="col-md-4">
            <div class="form-group">
              <label for="bank">Filter Admin</label>
              <select name="admin" id="admin" class="form-control">
                <option value="">All</option>
                @foreach ($admins as $item)
                <option value="{{$item->id}}">{{$item->fullname}}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
        @can('admin-bank-create')
        <button class="btn btn-primary mr-2 float-right mb-2">
          <a href="/admin-bank/create" class="text-white">Create</a>
        </button>
        @endcan
        <div class="table-responsive">
          <table class="table" id="admin-bank">
            <thead>
              <tr>
                <th>Admin</th>
                <th>Payment Type</th>
                <th>Bank Name</th>
                <th>Account Name</th>
                <th>Account Number</th>
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
  var table = $("#admin-bank").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: "{{route('admin-bank.data')}}",
          data: function (data) {
            data.admin_id = $("#admin").val()
            data.payment = $("#payment").val()
            data.bank = $("#bank").val()
          }
        },
        columns: [
          {data: 'admin', name: 'admin', class: 'text-center'},
          {data: 'payment_types', name: 'payment_types', class: 'text-center'},
          {data: 'bank_name', name: 'bank_name', class: 'text-center'},
          {data: 'account_name', name: 'account_name', class: 'text-center'},
          {data: 'account_number', name: 'account_number', class: 'text-center'},
          {data: 'status_badge', name: 'status_badge', class: 'text-center'},
          {data: 'action', name: 'action', class: 'text-center', orderable: false, searchable: false},
        ]
      });

      $("#payment").change(() => {
        let value = $("#payment").val();
        $("#fetching").text('Getting Data...');
        $.ajax({
          method: 'GET',
          url: "{{route('bank-payment.dataset')}}",
          data: {
            payment_id : value,
          }
        }).then(res => {
          var $bankPayment = $("#bank");
          if (res.length !== 0) {
            $bankPayment.empty();
            for (var i = 0; i < res.length; i++) {
              $bankPayment.append(`<option value='${res[i].id}'>${res[i].name}</option>`);
            }
            $("#bank").attr('disabled', false);
            $("#fetching").text('');
          } else {
            $bankPayment.empty();
            $bankPayment.append(`<option value='' selected>Select Bank...</option>`);
            $("#bank").attr('disabled', true);
            $("#fetching").text('');
          }
        });

        table.draw();
      });

      $("#bank").change(() => {
        table.draw();
      });

      $("#admin").change(() => {
        table.draw();
      });

      function deleteUserBank(id)
      {
        let url = "{{route('admin-bank.delete', ':id')}}"
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

      function getBankName(paymentId, selectedBank)
      {
        $("#fetching").text('Getting Data...');
        $.ajax({
          method: 'GET',
          url: "{{route('bank-payment.dataset')}}",
          data: {
            payment_id : paymentId,
          }
        }).then(res => {
          var $bankPayment = $("#updateBankName");
          if (res.length !== 0) {
            $bankPayment.empty();
            for (var i = 0; i < res.length; i++) {
              $bankPayment.append(`<option ${selectedBank === res[i].id ? 'selected' : ''} value='${res[i].id}'>${res[i].name}</option>`);
            }
            $("#updateBankName").attr('disabled', false);
            $("#fetchingUpdate").text('');
          } else {
            $bankPayment.empty();
            $bankPayment.append(`<option value=''>Select Bank...</option>`);
            $("#updateBankName").attr('disabled', true);
            $("#fetchingUpdate").text('');
          }
        })
      }

      function showUserBank(id)
      {
        let url = "{{route('admin-bank.detail', ':id')}}";
        url = url.replace(':id', id);
        $.ajax({
          method: 'GET',
          url: url,
        }).then(res => {
          $("#idUpdate").val(res.id);
          let paymentId = document.getElementById('updatePaymentId').value = res.payment_type_id;
          document.getElementById('updateStatus').value = res.status;
          document.getElementById('updateAccountName').value = res.account_name;
          document.getElementById('updateAccountNumber').value = res.account_number;
          getBankName(paymentId, res.bank_payment_id);
          if (!res.can_edit) {
            document.getElementById('submitUpdate').style.display = 'none';
            $("#updateBankName").attr('disabled', true);
            $("#updateStatus").attr('disabled', true);
            $("#updateAccountName").attr('disabled', true);
            $("#updateAccountNumber").attr('disabled', true);
            $("#updatePaymentId").attr('disabled', true);
          } else {
            document.getElementById('submitUpdate').style.display = 'block';
            $("#updateBankName").attr('disabled', false);
            $("#updateStatus").attr('disabled', false);
            $("#updateAccountName").attr('disabled', false);
            $("#updateAccountNumber").attr('disabled', false);
            $("#updatePaymentId").attr('disabled', false);
          }
          $("#updateModal").modal('show');
        });
      }

      $("#updatePaymentId").change(() => {
        let value = $("#updatePaymentId").val();
        $("#fetchingUpdate").text('Getting Data...');
        $.ajax({
          method: 'GET',
          url: "{{route('bank-payment.dataset')}}",
          data: {
            payment_id : value,
          }
        }).then(res => {
          var $bankPayment = $("#updateBankName");
          if (res.length !== 0) {
            $bankPayment.empty();
            for (var i = 0; i < res.length; i++) {
              $bankPayment.append(`<option value='${res[i].id}'>${res[i].name}</option>`);
            }
            $("#updateBankName").attr('disabled', false);
            $("#fetchingUpdate").text('');
          } else {
            $bankPayment.empty();
            $bankPayment.append(`<option value='' selected>Select Bank...</option>`);
            $("#updateBankName").attr('disabled', true);
            $("#fetchingUpdate").text('');
          }
        });

        table.draw();
      });

      $("#formUpdate").submit(e => {
        e.preventDefault();
        var id = $("#idUpdate").val();
        let url = "{{route('admin-bank.update', ':id')}}"
        url = url.replace(':id', id);
        var bankName = $("#updateBankName").val();
        var status = $("#updateStatus").val();
        var payment = $("#updatePaymentId").val();
        var accountName = $("#updateAccountName").val();
        var accountNumber = $("#updateAccountNumber").val()
        $.ajax({
          method: 'POST',
          url: url,
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          data: {
            bank_payment_id: bankName,
            status: status,
            payment_type_id: payment,
            account_name: accountName,
            account_number: accountNumber,
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
</script>
@endsection