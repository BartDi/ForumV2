@extends('layouts.temp')


@section('content')
<div class="card w-50 mx-auto">
            <div class="card-header">
                <div>
                    <a href='{{ url("/show/user/{$post->userId}") }}' class="text-decoration-none float-start">
                        <h3 style="color:#458051;">{{ $post->name }}</h3>
                    </a>
                    <h5 class="text-end text-secondary">{{ substr($post->created_at, 5,11) }}</h5>
                </div>
            </div>
            <div class="card-body">
                <a class="text-decoration-none" style="color:#305939;" href='{{url("/post/{$post->id}")}}'><h3 class="card-title text-center">{{ $post->title }}</h3></a>
                <p class="card-text">{{ substr($post->description, 0, 500) }}<a href='{{url("/post/{$post->id}")}}' style="color:#579e65;">See More</a></p>
            </div>
            <div class="card-footer">
                <h4 class="float-start mx-1 mt-1">{{ $post->likes }}</h4>
                <button type="button" class="btn btn-success float-start mx-1 like" data-post-id="{{$post->id}}">
                @if($icon=='unliked')
                    <i class="fa-regular fa-heart"></i>
                @elseif($icon=='liked')
                    <i class="fa-solid fa-heart"></i>
                @endif
                </button>
                <button type="button" class="btn btn-success float-start mx-1 comment" data-post-id="{{$post->id}}">Coment</button>
                <div class="card commentForm" style="visibility:hidden;">
                    <form action="{{url('/addComment')}}" method="POST" class="form-control">
                        @csrf
                        <input type="hidden" name="type" value="Post">
                        <input type="hidden" name="parent" value="{{$post->id}}">
                        <textarea name="description" class="form-control float-start" style="width:80%;"></textarea>
                        <input class="btn btn-success form-control float-start" style="width:19%;height100px;margin-left:5px;" type="submit">
                    </form>
                </div>
                </div>
            </div>
        </div>
@endsection