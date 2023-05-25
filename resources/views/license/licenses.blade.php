@extends('license.layout')

@section('content')
  <div class="container pt-3 pb-5">
    <div class="w-100">
      <button class="btn btn-primary">Total License: {{$total}}</button>
      <button class="btn btn-warning">Disabled License: {{$disabled}}</button>
      <button class="btn btn-danger">Expired License: {{$expired}}</button>
      <a href="{{route('new_license')}}" class="btn btn-primary float-end">Add New License</a>
    </div>
    {{-- @php
        $count = Session::get('count');
    @endphp --}}
    <div class="row mt-2">
      <div class="col-md-6">
        <select name="count" id="count" class="p-1 border bg-white mt-3">
          <option value="10" @if($count==10)selected @endif>10</option>
          <option value="25" @if($count==25)selected @endif>25</option>
          <option value="50" @if($count==50)selected @endif>50</option>
          <option value="100" @if($count==100)selected @endif>100</option>
          <option value="all" @if($count=="all")selected @endif>All</option>
        </select>
      </div>
      <div class="col-md-6">
        <div class="float-end">
          {{ $licenses->links() }}
        </div>
      </div>
    </div>
    <table class="table table-bordered mt-3">
      <thead>
        <tr>
          <th>#</th>
          <th>UID</th>
          <th>Expiry</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($licenses as $key=>$license)
          <tr>
            {{-- <td>{{$key+1}}</td> --}}
            <td>{{$license->id}}</td>
            <td>{{$license->uid}}</td>
            <td>{{$license->expiry_date}}</td>
            <td>{{strtoupper($license->status)}}</td>
            <td>
              <a href="{{route('edit_license',['id'=>$license->id])}}" class="btn btn-primary">Edit</a>
              <span>
                <form action="{{route('delete_license',['id'=>$license->id])}}" method="post" style="display:inline;">
                  @csrf
                  {{ method_field('DELETE') }} 
                  <button class="btn btn-danger text-white">Delete</button>
                </form>
              </span>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    {{ $licenses->links() }}
  </div>
@endsection

@section('js_script')
    
<script type="text/javascript">
  document.addEventListener("DOMContentLoaded", () => {
    document.querySelector('#count').addEventListener('change', function(e) {
      var count = e.target.value;
      console.log("change   ed");
      var target = event.target;
      var credential_id = event.target.value;
      var url = location.protocol + '//' + location.host + location.pathname;
      window.location.href = url + "?count=" + count;
    });
  });
</script>
@endsection