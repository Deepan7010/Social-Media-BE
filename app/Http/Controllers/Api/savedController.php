<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Saved;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class savedController extends Controller
{
    public function saveCreate(Request $request)
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

        $record = Saved::where("user_id", $request->user_id)->where("article_id", $request->type == "article" ? $request->article_id : null)->where("post_id", $request->type == "post" ? $request->post_id : null);

        if ($record->count() > 0) {
            return response()->json(["error" => "This is Record Already Saved!"],404);
        }

        $save = Saved::create([
            'user_id' => $request->user_id,
            'type' => $request->type,
            "article_id" => $request->type == 'article' ? $request->article_id : null,
            "post_id" => $request->type == 'post' ? $request->post_id : null,
        ]);
        return response()->json([
            'message' => 'Saved',
            'save' => $save
        ], 201);
    }

    public function deleteSave(Request $request)
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

        $unsaved = Saved::where('user_id', $request->user_id)->where("post_id", $request->type == "post" ? $request->post_id : null)->where("article_id", $request->type == "article" ? $request->article_id : null)->delete();

        if (!$unsaved) {
            return response()->json([
                'message' => 'Data Not Found!'
            ], 404);
        }
        return response()->json([
            'message' => 'Successfully Saved Deleted',
        ], 201);
    }

    // public function listOfSaved(Request $request)
    // {
    //     $validate = Validator::make($request->all(), [
    //         "user_id" => 'required|exists:users,id',
    //     ], [
    //         'user_id.exists' => 'The selected user does not exist.'
    //     ]);

    //     if ($validate->fails()) {
    //         return response()->json(["error" => $validate->errors()], 404);
    //     }

    //     $list = Saved::where("user_id", $request->user_id)->get();

    //     if (!$list) {
    //         return response()->json([
    //             "message" => "Data Not Found"
    //         ], 404);
    //     }

    //     return response()->json(["data" => $list], 200);
    // }
}
