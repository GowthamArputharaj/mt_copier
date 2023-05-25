@extends('license.layout')

@section('content')
  <div class="container mb-5 border p-5">
    <div class="w-100">
      <a href="{{route('licenses')}}" class="btn btn-primary float-end">Licenses</a>
      <div class="fw-bolder"><h2>Update Settings</h2></div>
    </div>
    <small><b><i>
      Note: 
      <br>
      Authentication Pin -> Used for Registering New Admin, Password Resetting ( stored in database ).
      {{-- <br>
      Secuirity Pin      -> Used for Resetting Authentication Pin ( stored in .env file ). --}}
    </i></b></small>
    <form action="{{ route('store_settings') }}" method="post" class="mt-5">
      @csrf
      <div class="mb-3">
        <label for="auth_code" class="form-label">Authentication Pin</label><small class="text-danger">*</small>
        <input type="text" class="form-control" name="auth_code" id="auth_code" placeholder="Authentication ID" value="{{$auth_code}}" required>
      </div>
      {{-- <div class="mb-3">
        <label for="security_pin" class="form-label">Security Pin</label><small class="text-danger">*</small>
        <input type="text" class="form-control" name="security_pin" id="security_pin" placeholder="Security Pin" value="{{$security_pin}}" required>
      </div> --}}
      <div class="mb-3">
        <br>
        <input type="submit" class="btn btn-success form-control" value="Submit">
      </div>
    </form>
  </div>
@endsection


{{-- login page -> email, password
register page -> name, email, password, authentication pin
forgot password(password reset link generation request) -> email, authentication pin
forgot authentication pin(confirm request using .env)  --}}