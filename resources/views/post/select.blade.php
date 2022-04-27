@extends('layouts.temp')


@section('content')
<div class="card w-75 mx-auto">
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
                <p class="card-text">{{ substr($post->description, 0, 500) }}</p>
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
                </div>
            </div>
            <div class="card w-75 mx-auto commentForm">
                    <form action="{{url('/addComment')}}" method="POST" class="form-control">
                        @csrf
                        <input type="hidden" name="type" value="Post">
                        <input type="hidden" name="parent" value="{{$post->id}}">
                        <textarea name="description" class="form-control float-start" style="width:80%;"></textarea>
                        <input class="btn btn-success form-control float-start" style="width:19%;height100px;margin-left:5px;max-width:200px;" type="submit">
                    </form>
                </div>
        </div>
        @foreach($comments as $comment)
        <br>
            <div class="card w-75 mx-auto">
                <div class="card-header">
                    <a href='{{url("/show/user/{$comment->userId}")}}'><h4 style="color:#458051;" class="float-start">{{$comAuthor[$comment->id]}} | </h4></a>
                    <h4 class="float-start ml-5"> {{$comment->description}}</h4>
                    
                    <button type="button" class="btn btn-success float-end mx-1 likeCom " data-com-id="{{$comment->id}}">
                    @if($icon=='unliked')
                        <i class="fa-regular fa-heart"></i>
                    @elseif($icon=='liked')
                        <i class="fa-solid fa-heart"></i>
                    @endif
                    </button>
                    <h4 class="float-end py-1">{{$comment->likes}}</h4>
                    <span class="float-end py-1 px-2"><i class="fa-solid fa-reply fa-lg"></i></span>
                    
                </div>
            </div>
            @if($comReply[$comment->id]>0)
            <div class="w-75 mx-auto" style="cursor:pointer;">
                <h5 class="reply" style="padding-left:5%; color:#458051;" data-com-id="{{$comment->id}}">
                    &#8627; {{$comReply[$comment->id]}} replies
                </h5>
            </div>
            @endif
        @endforeach

        <script src="http://code.jquery.com/jquery-3.3.1.min.js"
    integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
    crossorigin="anonymous">
</script>
<script>
    $(document).ready(function(){
        $(".reply").click(function (e) {
            var elem = $(this);
            var div = elem.parent()[0];
            var id = elem.attr('data-com-id');
            $.ajax({
                url: '{{url("/comment/replies/")}}/'+id,
                type: 'GET',
                data: {},
                success: function(result){
                    var encodedStr = "&#8627; " + result[2] +' replies';
                    $(elem).html(encodedStr).text();
                    $(elem).hide();
                    result[0].forEach(element =>{
                        var id = element['userId'];
                        $(div).append("<a style='text-decoration:none;' href='{{url('/show/user/1')}}'><h5 class='float-start' style='margin-left:5%;color:#458051;'>"+result[1][id][0]['name'] + ' |' + "</h5></a><h5 class='float-start'>"+ element['description'] + '</h5><span class="clearfix">');
                    });
                    $(div).append("<h5 class='float-start hide' style='margin-left:5%;color:#458051;'>Hide</h5><span class='clearfix'>");
                    $(div).on("click", "h5.hide", function(){
                        $(div).children().hide();
                        $(elem).show();
                    });
                }
            });
        });
    });

$(document).ready(function(){
        jQuery('.likeCom').click(function(e){
            var id = parseInt($(this).attr('data-com-id'));
            var elem = $(this)[0].children[0];
            var likes = $(this).next();
            console.log(likes);
            $.ajax({
                url: '{{url("/like/com/")}}/'+id,
                type: 'GET',
                data: {},
                success: function(result){
                    $(likes).text(result[1]);
                    if(result[0]=='liked'){
                        $(elem).attr('class', 'fa-solid fa-heart')
                    }
                    else{
                        $(elem).attr('class', 'fa-regular fa-heart')
                    }
                }
            });
        });
    });





</script>
@endsection