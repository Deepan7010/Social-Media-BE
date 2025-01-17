<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\education;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class profileEducationController extends Controller
{
    public function create(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'organization_name' => 'required|string',
            'region' => 'required|string',
            'department' => 'required|string',
            'degree' => 'required|string',
            'city' => 'required|string',
            'country' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'profile_id' => 'required|exists:profiles,id',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'error' => $validated->errors()
            ], 400);
        }

        // Create the employee record
        $education = education::create($request->all());

        return response()->json([
            'message' => 'Education created successfully!',
            'education' => $education
        ], 201);
    }

    public function edit(Request $request, $id)
    {
        $validated = Validator::make($request->all(), [
            'organization_name' => 'required|string',
            'region' => 'required|string',
            'department' => 'required|string',
            'degree' => 'required|string',
            'city' => 'required|string',
            'country' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'profile_id' => 'required|exists:profiles,id',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'error' => $validated->errors()
            ], 400);
        }

        $education = Education::find($id);

        if (!$education) {
            return response()->json([
                'error' => 'Data Not Found!'
            ], 404);
        }
        $education->organization_name = $request->organization_name;
        $education->region = $request->region;
        $education->department = $request->department;
        $education->degree = $request->degree;
        $education->city = $request->city;
        $education->country = $request->country;
        $education->start_date = $request->start_date;
        $education->end_date = $request->end_date;
        $education->profile_id = $request->profile_id;
        $education->save();

        return response()->json([
            'message' => 'Education updated successfully!',
            'employee' => $education
        ], 200);
    }

    public function delete($id)
    {
        $education = Education::find($id);

        if (!$education) {
            return response()->json([
                'error' => 'Data Not Found!'
            ], 404);
        }
        $education->delete();
        return response()->json([
            'message' => 'Education deleted successfully!',
        ], 200);
    }
}
