<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class likeController extends Controller
{
    public function likeCreate(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'type' => 'required|string',
            'article_id' => 'nullable|integer|exists:articles,id',
            'post_id' => 'nullable|integer|exists:posts,id'
        ], [
            'user_id.exists' => 'The selected user does not exist.',
            'article_id.exists' => 'The article does not exist.',
            'post_id.exists' => 'The post does not exist.'
        ]);


        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 404);
        }

        $record = Like::where("user_id", $request->user_id)->where("article_id", $request->type == "article" ? $request->article_id : null)->where("post_id", $request->type == "post" ? $request->post_id : null);

        if ($record->count() > 0) {
            return response()->json(["error" => "This is Record Already Like!"],404);
        }


        $like = Like::create([
            'user_id' => $request->user_id,
            'type' => $request->type,
            "article_id" => $request->type == 'article' ? $request->article_id : null,
            "post_id" => $request->type == 'post' ? $request->post_id : null,
        ]);
        return response()->json([
            'message' => 'Liked',
            // 'Like' => $like
        ], 201);
    }

    public function unlikeDelete(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'type' => 'required|string',
            'article_id' => 'nullable|integer|exists:articles,id',
            'post_id' => 'nullable|integer|exists:posts,id'
        ], [
            'user_id.exists' => 'The selected user does not exist.',
            'article_id.exists' => 'The article does not exist.',
            'post_id.exists' => 'The post does not exist.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 404);
        }

        $unlike = Like::where('user_id', $request->user_id)->where("post_id", $request->type == "post" ? $request->post_id : null)->where("article_id", $request->type == "article" ? $request->article_id : null)->delete();

        if (!$unlike) {
            return response()->json([
                'message' => 'Data Not Found!'
            ], 404);
        }
        return response()->json([
            'message' => 'UnLiked',
        ], 201);
    }
}
