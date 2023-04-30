@extends('welcome')
@section('page_name')
Bank List
@endsection
@section('content')
<div class="row">
  {{-- MODAL CREATE --}}
  <div class="modal" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="labelCreate">Create Bank</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="formCreate">
          <div class="modal-body">

            <div class="form-group">
              <label for="createPaymentId">Payment Type</label>
              <select required name="payment_type_id" id="createPaymentId" class="form-control">
                <option value="">Select Payment Type...</option>
                @foreach ($paymentTypes as $item)
                <option value="{{$item->id}}">{{$item->name}}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label for="createBankName">Bank Name</label>
              <input placeholder="Ex: BCA, OVO, DANA" type="text" name="createBankName" id="createBankName"
                class="form-control">
            </div>

            <div class="form-group">
              <label for="createPaymentId">Logo</label>
              <br>
              @foreach ($logos as $key => $logo)
              <div class="form-check form-check-inline">
                <input onchange="createBankLogo()" class="form-check-input" type="radio" name="create_logo"
                  id="logo-{{$key}}" value="{{$logo}}">
                <label class="form-check-label" for="logo-{{$key}}"><img width="50" src="{{$logo}}" alt=""></label>
              </div>
              @endforeach
            </div>

            <div class="form-group">
              <label for="createStatus">Status</label>
              <select name="status" id="createStatus" required class="form-control">
                <option value="">Select Status...</option>
                <option value="1">Active</option>
                <option value="2">Not Active</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
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
            <div class="form-group">

              <label for="">Current Logo</label>
              <img width="50" id="show_logo" src="" alt="">
            </div>
            <input type="hidden" name="id" id="idUpdate">
            <div class="form-group">
              <label for="updatePaymentId">Payment Type</label>
              <select required name="payment_type_id" id="updatePaymentId" class="form-control">
                <option value="">Select Payment Type...</option>
                @foreach ($paymentTypes as $item)
                <option value="{{$item->id}}">{{$item->name}}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label for="updateBankName">Bank Name</label>
              <input placeholder="Ex: BCA, OVO, DANA" type="text" name="updateBankName" id="updateBankName"
                class="form-control">
            </div>

            <div class="form-group">
              <label for="update_logo">Logo</label>
              <br>

              @foreach ($logos as $key => $logo)
              <div class="form-check form-check-inline">
                <input checked="false" onchange="updateBankLogo()" class="form-check-input" type="radio"
                  name="update_logo" id="updatelogo-{{$key}}" value="{{$logo}}">
                <label class="form-check-label" for="updatelogo-{{$key}}"><img width="50" src="{{$logo}}"
                    alt=""></label>
              </div>
              @endforeach
            </div>



            <div class="form-group">
              <label for="updateStatus">Status</label>
              <select name="status" id="updateStatus" required class="form-control">
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
        <h3 class="card-title">Bank List</h3>
      </div>
      <div class="card-body">
        <div class="col-md-6">
          <div class="form-group">
            <label for="payment">Filter Payment Type</label>
            <select name="payment" id="payment" class="form-control">
              <option value="all">All</option>
              @foreach ($paymentTypes as $item)
              <option value="{{$item->id}}">{{$item->name}}</option>
              @endforeach
            </select>
          </div>
        </div>

        @can('payment-type-create')
        <button class="btn btn-primary mr-2 float-right mb-2">
          <a href="#" data-toggle="modal" data-target="#createModal" class="text-white">Create</a>
        </button>
        @endcan
        <div class="table-responsive">
          <table id="bank-list" class="table">
            <thead>
              <tr>
                <th>Payment Type</th>
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
  var table = $("#bank-list").DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: "{{route('bank.data')}}",
      data: function (data) {
        data.payment = $("#payment").val()
      }
    },
    columns: [
      {data: 'payment_name', name: 'payment_name', class: 'text-center'},
      {data: 'name', name: 'name', class: 'text-center'},
      {data: 'status_badge', name: 'status_badge', class: 'text-center'},
      {data: 'action', name: 'action', class: 'text-center', orderable: false, searchable: false},
    ],
  });

  $("#payment").change(e => {
    table.draw();
  });

  function createBankLogo() {
    let value = $('input[name="create_logo"]:checked').val();
    return value;
  }

  function updateBankLogo() {
    let value = $('input[name="update_logo"]:checked').val();
    return value;
  }

  // CREATE BANK
  $("#formCreate").on('submit', e => {
    e.preventDefault();
    let url = "{{route('bank.store')}}"
    let paymentId = $("#createPaymentId").val();
    let bankName = $("#createBankName").val();
    let status = $("#createStatus").val();
    let logo = createBankLogo();
    $.ajax({
      method: 'POST',
      url: url,
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      data: {
        name: bankName,
        payment_type_id: paymentId,
        logo: logo,
        status: status
      },
    }).then(res => {
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
      $('input[name=create_logo]').prop('checked',false);
    })
  });

   // UPDATE BANK
   $("#formUpdate").on('submit', e => {
      e.preventDefault();
      var id = $("#idUpdate").val();
      let url = "{{route('bank.update', ':id')}}"
      url = url.replace(':id', id);
      var name = $("#updateBankName").val();
      var status = $("#updateStatus").val();
      var payment = $("#updatePaymentId").val();
      var logo = updateBankLogo() !== '' ? updateBankLogo() : null;
      $.ajax({
        method: 'POST',
        url: url,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
          name: name,
          status: status,
          logo: logo,
          payment_type_id: payment,
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
          $('input[name=update_logo]').prop('checked',false);
        } else {
          Swal.fire({
            title: 'Failed',
            text: res.message,
            icon: 'error',
          });
        }
      })
    });

  // DELETE BANK
  function deleteBank(id) {
    let url = "{{route('bank.delete', ':id')}}"
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

  // SHOW BANK
  function showBank(id) {
    let url = "{{route('bank.detail', ':id')}}";
    url = url.replace(':id', id);
    $.ajax({
      method: 'GET',
      url: url,
    }).then(res => {
      $("#updateBankName").val(res.name);
      document.getElementById('updatePaymentId').value = res.payment_id;
      document.getElementById('updateStatus').value = res.status;
      $("#show_logo").attr('src', res.logo);
      console.log(res.logo);
      $("#idUpdate").val(res.id);
      if (!res.can_edit) {
        document.getElementById('submitUpdate').style.display = 'none';
        $("#updateBankName").attr('disabled', true);
        $("#updateStatus").attr('disabled', true);
        $("#updatePaymentId").attr('disabled', true);
      } else {
        document.getElementById('submitUpdate').style.display = 'block';
        $("#updateBankName").attr('disabled', false);
        $("#updateStatus").attr('disabled', false);
        $("#updatePaymentId").attr('disabled', false);
      }
      $("#updateModal").modal('show');
    });
  }
</script>
@endsection