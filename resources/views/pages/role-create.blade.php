@extends('welcome')
@section('page_name')
    Role Create
@endsection
@section('content')
    <div class="row">
      <div class="col-md-12">
        <form action="{{route('role.store')}}" method="POST">
          @csrf
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Role Create</h3>
            </div>
            <div class="card-body">
              <div class="col-md-12">
                <input type="text" name="name" id="name" class="form-control">
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Permissions</h3>
            </div>
            <div class="card-body">
              @foreach ($dataPermissions as $key => $permission)
              <div class="card">
                <div class="card-header">
                  <h2 class="card-title">{{$key}}</h2>
                </div>
                <div class="card-body">
                  @foreach ($permission as $index => $item)
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" name="permissions[]" type="checkbox" id="{{$item['id']}}" value="{{$item['name']}}" >
                    <label class="form-check-label" for="{{$item['id']}}">{{$item['display']}}</label>
                  </div>
                  @endforeach
                </div>
              </div>
              @endforeach
            </div>
          </div>
          <div class="card">
            <div class="card-body">
              <div class="btn-group" role="group" aria-label="Basic example">
                @can('role-management-create')
                  <button type="submit" class="btn btn-primary mr-2">Save</button>
                @endcan
                <button type="button" class="btn btn-danger">
                  <a href="{{route('role.index')}}" class="text-white">Back</a>
                </button>
              </div>
            </div>
          </div>
        </form>


      </div>
    </div>
@endsection