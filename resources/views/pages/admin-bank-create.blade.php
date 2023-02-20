@extends('welcome')
@section('page_name')
Admin Bank Create
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
        <div class="card-header">
          <h3 class="card-title">Admin Bank Create</h3>
        </div>
        <div class="card-body">
          <form action="{{route('admin-bank.store')}}" method="POST">
            @csrf
            <div class="form-group">
              <label for="fullname">Admin</label>
              @if (request()->get('admin_id') != null)
              <input type="text" value="{{$admin->id}} | {{$admin->fullname}}" disabled readonly class="form-control">
              <input type="hidden" name="user_id" value="{{$admin->id}}">
              @else
              <select required name="user_id" id="user_id" class="form-control">
                <option value="">Select Admin...</option>
                @foreach ($admins as $admin)
                <option value="{{$admin->id}}">{{$admin->id}} | {{$admin->fullname}} | {{$admin->email}}</option>
                @endforeach
              </select>
              @endif
            </div>
            <div class="form-row">
              <div class="col">
                <div class="form-group">
                  <label for="payment_type_id">Payment Type</label>
                  <select required class="form-control" name="payment_type_id" id="payment_type_id">
                    <option value="">Select Payment Type...</option>
                    @foreach ($paymentTypes as $paymentType)
                    <option value="{{$paymentType->id}}">{{$paymentType->name}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label for="bank_payment_id">Bank</label>
                  <select required class="form-control" disabled name="bank_payment_id" id="bank_payment_id">
                    <option value="">Select Bank...</option>
                  </select>
                  <span class="text-bold" id="fetching"></span>
                </div>
              </div>
            </div>
            <div class="form-row">
              <div class="col">
                <div class="form-group">
                  <label for="account_name">Account Name</label>
                  <input required type="text" placeholder="Ex: John Doe" name="account_name" id="account_name" class="form-control">
                </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label for="account_number">Account Number</label>
                  <input required type="number" placeholder="Ex : 689875628" name="account_number" id="account_number" class="form-control">
                </div>
              </div>
            </div>
            <div class="btn-group" role="group" aria-label="Basic example">
              <button type="submit" id="submit" class="btn btn-primary mr-2">Submit</button>
              <button type="button" class="btn btn-danger">
                @if (request()->get('admin_id') != null)
                  <a href="{{route('admin.detail', $admin->id)}}" class="text-white">Back</a>
                @else
                  <a href="{{route('admin-bank.index')}}" class="text-white">Back</a>
                @endif
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
  <script>
    $("#payment_type_id").change(() => {
      let value = $("#payment_type_id").val();
      $("#fetching").text('Getting Data...');
      $.ajax({
        method: 'GET',
        url: "{{route('bank-payment.dataset')}}",
        data: {
          payment_id : value,
        }
      }).then(res => {
        var $bankPayment = $("#bank_payment_id");
        if (res.length !== 0) {
          $bankPayment.empty();
          for (var i = 0; i < res.length; i++) {
            $bankPayment.append(`<option value='${res[i].id}'>${res[i].name}</option>`);
          }
          $("#bank_payment_id").attr('disabled', false);
          $("#fetching").text('');
        } else {
          $bankPayment.empty();
          $bankPayment.append(`<option value=''>Select Bank...</option>`);
          $("#bank_payment_id").attr('disabled', false);
          $("#fetching").text('');
        }
      })
    });
  </script>
@endsection