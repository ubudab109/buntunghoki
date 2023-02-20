@extends('welcome')
@section('page_name')
    Admin Create
@endsection
@section('content')
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Admin Create</h3>
          </div>
          <div class="card-body">
            <form action="{{route('admin.store')}}" method="post">
              @csrf
              <div class="form-row">
                <div class="col">
                  <div class="form-group">
                    <label for="fullname">Fullname</label>
                    <input type="text" placeholder="Fullname" required name="fullname" id="fullname" class="form-control">
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" placeholder="example@example.com" required name="email" id="email" class="form-control">
                  </div>
                </div>
              </div>

              <div class="form-row">
                <div class="col">
                  <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" placeholder="Username" required name="username" id="username" class="form-control">
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="role">Role</label>
                    <select name="role" id="role" required class="form-control">
                      <option value="">Select Role...</option>
                      @foreach ($roles as $role)
                          <option value="{{$role->id}}">{{ucwords($role->name)}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="number" placeholder="0858XXXXX" required name="phone_number" id="phone_number" class="form-control">
              </div>

              <div class="form-row">
                <div class="col">
                  <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" minlength="8" placeholder="Password" required name="password" id="password" class="form-control">
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="confirm_pass">Confirm Password</label>
                    <input type="password" onkeyup="confirmPassword()" minlength="8" placeholder="Confirm Password" required name="confirm_pass" id="confirm_pass" class="form-control">
                    <span class="text-red" id="error_pass"></span>
                  </div>
                </div>
              </div>

              <div class="btn-group" role="group" aria-label="Basic example">
                <button type="submit" id="submit" class="btn btn-primary mr-2">Submit</button>
                <button type="button" class="btn btn-danger">
                  <a href="{{route('admin.index')}}" class="text-white">Back</a>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
@endsection
@section('script')
    <script>
      function confirmPassword()
      {
        let valPassword = $("#password").val();
        let confirmPassword = $("#confirm_pass").val();
        if (confirmPassword !== valPassword) {
          $("#error_pass").text('Password not match');
          $("#submit").attr('disabled', true);
        } else {
          $("#error_pass").text('');
          $("#submit").attr('disabled', false);
        }
      }
    </script>
@endsection