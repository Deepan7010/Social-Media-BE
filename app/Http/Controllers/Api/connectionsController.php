<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\connections as connect;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class connectionsController extends Controller
{
    public function followCreate(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "logged_id" => 'required|exists:users,id',
            "follow_id" => 'required|exists:users,id'
        ], [
            "logged_id.exists" => "The selected user does not exists.",
            "follow_id.exists" => "The selected user does not exists.",
        ]);

        if ($validate->fails()) {
            return response()->json([
                "error" => $validate->errors()
            ], 404);
        }

        $record = connect::where("loggeduser_id", $request->logged_id)->where("following", $request->follow_id);
        if ($record->count() > 0) {
            return response()->json(["message" => "This User Already Followed!"], 404);
        } elseif ($request->logged_id == $request->follow_id) {
            return response()->json(["message" => "Logged User and Follow User are Same!"], 404);
        }

        $follow = connect::create([
            "loggeduser_id" => $request->logged_id,
            "following" => $request->follow_id
        ]);

        return response()->json([
            "message" => "Successfully Followed",
            "data" => $follow
        ], 201);
    }

    public function unfollow(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "logged_id" => 'required|exists:users,id',
            "follow_id" => 'required|exists:users,id'
        ], [
            "logged_id.exists" => "The selected user does not exists.",
            "follow_id.exists" => "The selected user does not exists.",
        ]);

        if ($validate->fails()) {
            return response()->json([
                "error" => $validate->errors()
            ], 404);
        }

        if ($request->logged_id == $request->follow_id) {
            return response()->json(["message" => "Logged User and Unfollow User are Same!"], 404);
        }

        $record = connect::where("loggeduser_id", $request->logged_id)->where("following", $request->follow_id)->delete();
        if (!$record) {
            return response()->json(["message" => "Data Not Found!"], 404);
        }
        return response()->json([
            "message" => "Successfully Unfollowed"
        ], 200);
    }

    public function fetchConnectionsList(Request $request)
    {

        $validate = Validator::make($request->all(), [
            "follow_id" => 'required|exists:users,id',
            "type" => 'required'
        ], [
            "follow_id.exists" => "The selected user does not exists.",
        ]);

        if ($validate->fails()) {
            return response()->json([
                "error" => $validate->errors()
            ], 404);
        }
        $type = null;
        $count = null;
        if ($request->type == "follow") {
            $follow  = connect::where("loggeduser_id", $request->follow_id)->pluck("following");
            $followUser = User::whereIn("id", $follow)->select("id", "name")->get();
            $type = "follow";
            $count = "followCount";
            if ($follow->count()==0) {
                return response()->json(["message" => "User Not Followed"]);
            }
        } elseif($request->type == "followers") {
            $follower = connect::where("following", $request->follow_id)->pluck("loggeduser_id");
            $followUser = User::whereIn("id", $follower)->select("id", "name")->get();
            $type = "followers";
            $count ="followersCount";
            if ($follower->count()==0) {
                return response()->json(["message" => "User does not Followers"]);
            }
        }
        $random = User::inRandomOrder()->limit(6)->get();
       
        return response()->json([
            $type =>  $followUser,
            $count => $request->type=="follow"? $follow->count(): $follower->count(),
            "suggestion" => $random
        ], 200);
    }
}
