<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Connections;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class profileController extends Controller
{
    public function create(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'bio' => 'required|string|max:255',
            'skills' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'error' => $validated->errors()
            ], 404);
        }

        // Handle the image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('profile_images', 'public');
        }
        // Create the profile
        $profile = Profile::create([
            'name' => $request->name,
            'image' => $imagePath,
            'bio' => $request->bio,
            'skills' => $request->skills,
            'user_id' => $request->user_id,
        ]);

        return response()->json([
            'message' => 'Profile created successfully!',
            "profile" => $profile,

        ], 201);
    }

    public function edit(Request $request, $id)
    {
        // Find the profile by ID
        $profile = Profile::find($id);

        // If profile not found, return a 404 error
        if (!$profile) {
            return response()->json([
                'error' => 'Data Not Found.'
            ], 404);
        }

        // Validate the incoming request
        $validated = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'bio' => 'nullable|string|max:255',
            'skills' => 'nullable|string|max:255',
            'user_id' => 'required|exists:users,id',
        ]);

        // If validation fails, return errors
        if ($validated->fails()) {
            return response()->json([
                'error' => $validated->errors()
            ], 404);
        }

        // Handle the image upload if present
        $imagePath = $profile->image; // Retain the old image path if no new image is uploaded
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($imagePath && Storage::disk("public")->exists($imagePath)) {
                Storage::disk("public")->delete($imagePath);
            }
            // Store the new image
            $imagePath = $request->file('image')->store('profile_images', 'public');
        }

        // Update the profile with the validated data
        $profile->name = $request->name;
        $profile->image = $imagePath;
        $profile->bio = $request->bio;
        $profile->skills = $request->skills;
        $profile->user_id = $request->user_id;
        $profile->save();

        return response()->json([
            'profile' => $profile
        ], 200);
    }

    public function destroy($id)
    {
        $record = Profile::find($id);
        if (!$record) {
            return response()->json([
                'error' => 'Data Not Found.'
            ], 404);
        }

        $imagePath = $record->image;
        // Delete old image if exists
        if ($imagePath && Storage::disk("public")->exists($imagePath)) {
            Storage::disk("public")->delete($imagePath);
        }
        $record->delete();
        return response()->json([
            'message' => 'Profile deleted successfully.'
        ], 200);
    }

    public function fetchProfile(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'error' => $validated->errors()
            ], 404);
        }
        $user = User::with([
            'profile.employee',
            'profile.works',
            'profile.professionalActivities',
            'profile.fundingDetails'
        ])->find($request->user_id); // Replace $userId with the actual ID of the user

        $follow  = Connections::where("loggeduser_id", $request->user_id)->pluck("following");
        $followUser = User::whereIn("id", $follow)->select("id", "name")->get();
        $follow_res = ($follow->count() == 0) ? ["User Not Followed"] : $followUser;

        $follower = Connections::where("following", $request->user_id)->pluck("loggeduser_id");
        $followerUser = User::whereIn("id", $follower)->select("id", "name")->get();
        $follower_res = ($follower->count() == 0) ? ["User Not Followed"] : $followerUser;

        if (!$user) {
            return response()->json([
                'error' => 'Data Not Found.'
            ], 404);
        }
        // Access the data like this:
        // $profile = $user->profile; // Access profile data
        // $employee = $user->profile->employee; // Access employee data
        // $works = $user->profile->works; // Access works data
        // $professionalActivities = $user->profile->professionalActivities; // Access professional activities
        // $fundingDetails = $user->profile->fundingDetails; // Access funding details

        // Output data, for example:
        return response()->json([
            'profile_data' => ["user" => $user],
            "follow_users" =>$follow_res,
            "follower_users" =>$follower_res,
            "public_views"=>null,
            "mention"=>null,
            "co-author"=>null,
            "interests"=>null

        ]);

        /* 
         'profile' => $profile,
            'employee' => $employee,
            'works' => $works,
            'professionalActivities' => $professionalActivities,
            'fundingDetails' => $fundingDetails,
            */
    }
}
