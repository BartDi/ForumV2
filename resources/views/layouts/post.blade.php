@extends('layouts.temp')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@section('content')
<form action='{{ url("/addPost") }}' method="POST" style="margin-bottom:200px;">
@csrf
<div class="col">
    <div class="row">
        <input type="text" class="form-control" name="tit" placeholder="Title">
    </div>
    <div class="row">
        <textarea class="form-control" name="des" placeholder="Description"></textarea>
    </div>
  </div>
  <input type="file" name="file" class="form-control-file" id="file">
<br>
<input type="submit" value="Add">
</form>
@endsection