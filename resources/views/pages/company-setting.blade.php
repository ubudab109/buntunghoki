@extends('welcome')
@section('page_name')
Company Setting
@endsection
@section('content')
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Company Setting</h3>
        </div>
        <div class="card-body">
          <div class="col-12 text-center">
            <h5>Current Logo</h5>
            <img width="200" style="clip-path: circle()" src="{{getSettingValue('company_logo')}}" alt="">
          </div>
          <div class="col-12">
            <form action="{{route('company-setting.update')}}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="form-group">
                <label for="">Company Name</label>
                <input type="text" name="company_name" value="{{getSettingValue('company_name')}}" id="company_name" class="form-control">
              </div>
              <div class="form-group">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text">Upload To Change Logo</span>
                  </div>
                  <div class="custom-file">
                    <input type="file" name="company_logo" class="form-control" id="inputGroupFile01">
                    <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                  </div>
                </div>
              </div>
              <button type="submit" class="btn btn-success">Save</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection