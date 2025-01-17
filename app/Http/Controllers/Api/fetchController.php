<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class fetchController extends Controller
{
    public function fetchArticle(Request $request){
        $validate = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validate->fails()){
            return response()->json(['error' => $validate->errors()], 404);
        }

        $article = Article::find($request->id);

        if (!$article){
            return response()->json(['error' => 'Data not found'], 404);
        }

        return response()->json(["article"=>$article]);
    }
}
