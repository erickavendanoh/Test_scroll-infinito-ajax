<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Response;

class PostController extends Controller
{
    public function index(Request $request){
        $posts = Post::latest()->paginate(10);
        if($request->ajax()){
            $view = view('posts.load', compact('posts'))->render();
            return Response::json(['view'=>$view, 'nextPageUrl'=>$posts->nextPageUrl()]);
        }
        return view('posts.index', ['posts'=>$posts]);
    }
}
