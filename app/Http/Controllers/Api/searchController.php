<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;


class searchController extends Controller
{
    public function listOfUsers()
    {
        // Get all users and select only the id and name fields
        $users = User::select('id', 'name')->get();

        // Return the list of users with only id and name
        return response()->json([
            'users' => $users
        ], 200);
    }

}