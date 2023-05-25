@extends('license.layout')

@section('content')
  <div class="container border p-5">
    <div class="w-100">
      <a href="{{route('licenses')}}" class="btn btn-primary float-end">Licenses</a>
      <div class="fw-bolder"><h2>Edit License</h2></div>
    </div>
    <form action="{{ route('update_license',['id'=>$license->id]) }}" method="post" class="mt-5">
      @csrf
      @method('put')
      <div class="mb-3">
        <label for="uid" class="form-label">Unique ID</label><small class="text-danger">*</small>
        <input type="text" class="form-control" name="uid" id="uid" placeholder="Unique ID" value="{{$license->uid}}" required>
      </div>
      <div class="mb-3">
        <label for="expiry" class="form-label">Expiry Date</label><small class="text-danger">*</small>
        <input type="date" class="form-control" name="expiry" id="expiry" value="{{$license->expiry_date}}" required>
      </div>
      <div class="mb-3">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="on" name="on" id="on"
            @if($license->status == "on")
              checked
            @endif
          >
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