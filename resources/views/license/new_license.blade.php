@extends('license.layout')

@section('content')
  <div class="container border p-5">
    <div class="w-100">
      <a href="{{route('licenses')}}" class="btn btn-primary float-end">Licenses</a>
      <div class="fw-bolder"><h2>New License</h2></div>
    </div>
    <form action="{{ route('store_license') }}" method="post" class="mt-5">
      @csrf
      @method('post')
      <div class="mb-3">
        <label for="uid" class="form-label">Unique ID</label><small class="text-danger">*</small>
        <input type="text" class="form-control" name="uid" id="uid" placeholder="Unique ID" required>
      </div>
      <div class="mb-3">
        <label for="expiry" class="form-label">Expiry Date</label><small class="text-danger">*</small>
        <input type="date" class="form-control" name="expiry" id="expiry" value="{{$expiry}}" required>
      </div>
      <div class="mb-3">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="on" name="on" id="on">
          <label class="form-check-label" for="on">
            Enable
          </label>
        </div>
      </div>
      
      <div class="mb-3">
        <br>
        <input type="submit" class="btn btn-success form-control" value="Submit">
      </div>
    </form>
  </div>
@endsection