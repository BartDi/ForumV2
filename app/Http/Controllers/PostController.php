<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
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

    public function selectPost($id)
    {
        $icon = 'unliked';
        $userId = Auth::id();
        if (DB::table('liked_posts')->where('userId', '=', $userId)->where('postId', '=', $id)->count()==1){
            $icon = 'liked';
        }
        $post = Post::find($id);
        return view('post.select', ['post' => $post, 'icon' => $icon]);
    }

    public function addComment(Request $request)
    {
        $bool = false;
        $type = $request->type;
        if ($type=="Post") $bool = true;  
        Comment::create([
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
}
