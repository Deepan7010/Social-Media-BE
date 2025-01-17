<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\works;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class profileWorksController extends Controller
{
    public function create(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'work_type' => 'required|string|max:255',
            'work_title' => 'required|string|max:255',
            'journal_title' => 'required|string|max:255',
            'link' => 'required',
            'publication_date' => 'required|date',
            'profile_id' => 'required|exists:profiles,id',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'error' => $validated->errors()
            ], 400);
        }

        // Create the work record
        $work = works::create($request->all());

        return response()->json([
            'message' => 'Works created successfully!',
            'work' => $work
        ], 201);
    }

    // Edit an existing work record
    public function edit(Request $request, $id)
    {
        $validated = Validator::make($request->all(), [
            'work_type' => 'required|string|max:255',
            'work_title' => 'required|string|max:255',
            'journal_title' => 'required|string|max:255',
            'link' => 'required',
            'publication_date' => 'required|date',
            'profile_id' => 'required|exists:profiles,id',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'error' => $validated->errors()
            ], 400);
        }

        $work = works::find($id);

        if (!$work) {
            return response()->json([
                'error' => 'Data Not Found!'
            ], 404);
        }


        $work->update($request->all());

        return response()->json([
            'message' => 'Work updated successfully!',
            'work' => $work
        ], 200);
    }


    public function delete($id)
    {
        $work = works::find($id);

        if (!$work) {
            return response()->json([
                'error' => 'Data Not Found!'
            ], 404);
        }

        $work->delete();

        return response()->json([
            'message' => 'Work deleted successfully!'
        ], 200);
    }
}
