<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Connections;
use App\Models\Like;
use App\Models\Post;
use App\Models\Saved;
use App\Models\User;
use Illuminate\Http\Request;


class homeController extends Controller
{
    public function homefeed(Request $request)
    {
        $users = User::where("id", $request->user_id)->select("name", "id as user_id")->get()
            ->map(function ($user) {
                $user->profile_image = null;
                return $user;
            });
        // $user_id =$request->user_id;
        $connect = Connections::where("loggeduser_id", $request->user_id)->pluck("following");
        $post = Post::whereIn("user_id", $connect)->join('users', 'posts.user_id', '=', 'users.id')
            ->select("posts.user_id as postUserId", 'posts.id as postid', 'users.name as PostUsername', "posts.description as title", "posts.post as image") // Select relevant fields
            ->orderBy('posts.created_at', 'desc')
            ->get()
            ->map(function($user) use ($request){
                $user->postUserLocation=null;
                $like = Like::where("user_id", $request->user_id)->where("post_id", $user->postid)->get();
                $like_count = Like::where("post_id", $user->postid)->get();
                $user->am_i_liked=$like->count()>0 ? true : false;
                $user->likeCount=$like_count->count();
                $user->viewsCount=null;
                $save = Saved::where("user_id", $request->user_id)->where("post_id", $user->postid)->get();
                $user->isSaved=$save->count()>0 ? true : false;
                $user->am_i_following=True;
                return $user;
            });

        $article =  Article::whereIn("user_id", $connect)->join('users', 'articles.user_id', '=', 'users.id')
            ->select("articles.user_id as articleUserId", 'articles.id as articleId', 'users.name as articleUsername', "articles.paper_title as title", "articles.abstract as description", "articles.article as image") // Select relevant fields
            ->orderBy('articles.created_at', 'desc')
            ->get()
            ->map(function($user) use ($request){
                $like = Like::where("user_id", $request->user_id)->where("article_id", $user->articleId)->get();
                $user->am_i_liked=$like->count()>0 ? true : false;
                $like_count = Like::where("article_id", $user->articleId)->get();
                $user->likeCount=$like_count->count();
                $user->viewsCount=null;
                $save = Saved::where("user_id", $request->user_id)->where("article_id", $user->articleId)->get();
                $user->isSaved=$save->count()>0 ? true : false;
                $user->am_i_following=True;
                return $user;
            });


        $suggestions = User::inRandomOrder()->select("id as userId", "name")->limit(6)->get()->map(function($user){
            $user->profile_image = null;
            return $user;
        });


        return response()->json([
            "userDetails" => $users,
            "homeFeed" => [
                "posts" => $post,
                "articles" => $article,
                "suggestions" => $suggestions
            ]
        ]);
    }
}
