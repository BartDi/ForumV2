<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\PostsService;

class PostController extends Controller
{

    private $PostsService;

    public function __construct(PostsService $service)
    {
        $this->middleware('auth');
        $this->PostsService = $service;
    }

    public function latestPosts()
    {
        [$posts, $iconArray] = $this->PostsService->getLatestPosts();
        return view('post.main', ['posts'=>$posts, 'icon'=>$iconArray]);
    }

    //Attachment to add
    public function addPost(Request $request)
    {
        $validated = $request->validate([
            'tit' => 'required|max:255',
            'des' => 'required',
        ]);
        Post::create([
            'title' => $request->tit,
            'description' => $request->des,
            'likes' => 0,
            'userId' => Auth::id(),
        ]);

        return redirect()->route('latest');
    }

    public function writePost()
    {
        return view('layouts.post');
    }

    //Liking/Unliking post | function works with ajax 
    public function likePost($id)
    {
        return $this->PostsService->handleLikePost($id);
    }
    public function likeCom($id)
    {
        return $this->PostsService->handleLikeCom($id);
    }

    public function selectPost($id)
    {
        [$post , $icon , $comments, $comAuthors, $comReply] = $this->PostsService->selectPostInfo($id);
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

    //TODO
    public function userPage($id)
    {
        return view('user', ['id'=>$id]);
    }
    public function getReplies($id)
    {
        return $this->PostsService->getReplies($id);
    }
}
