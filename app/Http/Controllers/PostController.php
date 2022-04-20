<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function latestPosts()
    {
        $iconArray = array();
        $userId = Auth::id();
        $posts = Post::sortable()
        ->join('users', 'posts.userId', '=', 'users.id')
        ->select('posts.id', 'posts.title', 'posts.description', 'posts.created_at', 'users.name', 'posts.userId', 'posts.likes')
        ->paginate(10);
        foreach($posts as $post){
            if (DB::table('liked_posts')->where('userId', '=', $userId)->where('postId', '=', $post->id)->count()==1){
                $iconArray[$post->id] = 'liked';
            }
            else{
                $iconArray[$post->id] = 'unliked';
            }
        }
        return view('post.main', ['posts'=>$posts, 'icon'=>$iconArray]);
    }

    //Attachment to add
    public function addPost(Request $request)
    {
        Post::create([
            'title' => $request->tit,
            'description' => $request->des,
            'likes' => 0,
            'userId' => Auth::id(),
        ]);

        return route('latest');
    }

    public function writePost()
    {
        return view('layouts.post');
    }

    //Liking/Unliking post | function works with ajax 
    public function likePost($id)
    {
        $likes = 0;
        $userid = Auth::id();
        $icon = 'liked';
        $count = DB::table('liked_posts')
            ->where('userId', '=', $userid)
            ->where('postId', '=', $id)
            ->count();
        if($count==0){
            DB::table('liked_posts')
                ->insert([
                    'userId' => $userid,
                    'postId' => $id
                ]);
            $post = Post::find($id);
            $post->likes = $post->likes+1;
            $likes = $post->likes;
            $post->save();
        }
        else{
            DB::table('liked_posts')
            ->where('userId', '=', $userid)
            ->where('postId', '=', $id)
            ->delete();
            $icon = 'null';
            $post = Post::find($id);
            $post->likes = $post->likes-1;
            $likes = $post->likes;
            $post->save();
        }
        return [$icon, $likes];
    }
    public function likeCom($id)
    {
        $likes = 0;
        $userid = Auth::id();
        $icon = 'liked';
        $count = DB::table('liked_comments')
        ->where('userId', '=', $userid)
        ->where('commentId', '=', $id)
        ->count();

        if($count==0){
            DB::table('liked_comments')
                ->insert([
                    'userId' => $userid,
                    'commentId' => $id 
                ]);
            $com = Comment::find($id);
            $com->likes = $com->likes+1;
            $likes = $com->likes;
            $com->save();
        }
        else{
            DB::table('liked_comments')
            ->where('userId', '=', $userid)
            ->where('commentId', '=', $id)
            ->delete();
            $com = Comment::find($id);
            $com->likes = $com->likes-1;
            $likes = $com->likes;
            $com->save();
        }
        return [$icon, $likes];
    }

    public function selectPost($id)
    {
        $icon = 'unliked';
        $userId = Auth::id();
        if (DB::table('liked_posts')->where('userId', '=', $userId)->where('postId', '=', $id)->count()==1){
            $icon = 'liked';
        }
        $post = Post::find($id);
        $comments = DB::table('comments')->where('parentId', '=', $id)->where('toPost', '=', 1)->get();
        $comAuthors = array();
        $comReply = array();
        foreach($comments as $com){
            $user = User::find($com->userId)->get('name');
            $comAuthors[$com->id] = $user[0]->name;
            $amountOfReplies = DB::table('comments')->where('parentId', '=', $com->id)->where('toPost', '=', 0)->count();
            $comReply[$com->id] = $amountOfReplies;
        }
        return view('post.select', ['post' => $post, 'icon' => $icon, 'comments' => $comments, 'comAuthor' => $comAuthors, 'comReply' => $comReply]);
    }

    public function addComment(Request $request)
    {
        $bool = false;
        $type = $request->type;
        if ($type=="Post") $bool = true;  
        $com = Comment::create([
            'parentId' => $request->parent,
            'userId' => Auth::id(),
            'likes' => 0,
            'description' => $request->description,
            'toPost' => $bool
        ]);
        return redirect()->back();
    }

    public function userPage($id)
    {
        return view('user', ['id'=>$id]);
    }
    public function getReplies($id)
    {
        $usersNames = array();
        $replies = Comment::where('parentId', '=', $id)->where('toPost', 0)->select('description', 'userId', 'likes')->get();
        foreach($replies as $reply)
        {
            $usersNames[$reply->userId] = User::find($reply->userId)->get('name');
        }
        $amount = $replies->count();
        return [$replies, $usersNames, $amount];
    }
}
