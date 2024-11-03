<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class downloadController extends Controller
{
    public function downloadArticle(Request $request){
        $validate = Validator::make($request->all(),[
            "user_id"=>'required|exists:users,id',
            "id"=>'required|exists:articles,id'
        ],[
            "user_id.exists"=>"The selected user does not exists.",
            "id.exists"=>"The Article does not exists."
        ]);

        if($validate->fails()){
            return response()->json([
                "error"=>$validate->errors()
            ],404);
        }

        $article = Article::where("user_id",$request->user_id)->where("id", $request->id)->first();

        if(!$article){
            return response()->json([
                "message"=>"The Data Not Found!"
            ],404);
        }
        $filepath = storage_path("\app\public\\".$article->article);
        if (!file_exists($filepath)){
            return response()->json(["error"=>"The File Not Found!"],404);
        }
        return response()->download($filepath,$article->paper_title);
    }
}
