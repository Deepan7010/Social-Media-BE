<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\professional_activities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class profileProfessionalController extends Controller
{
    public function create(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'organization_name' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'profile_id' => 'required|exists:profiles,id',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'error' => $validated->errors()
            ], 400);
        }

        $records = professional_activities::create($request->all());

        return response()->json([
            'message' => 'Professional Activities created successfully!',
            'Professional_activities' => $records
        ], 201);
    }

    public function edit(Request $request, $id)
    {
        $validated = Validator::make($request->all(), [
            'organization_name' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'profile_id' => 'required|exists:profiles,id',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'error' => $validated->errors()
            ], 400);
        }

        $professionalActivity = professional_activities::find($id);

        if (!$professionalActivity) {
            return response()->json([
                'error' => 'Data Not Found!'
            ], 404);
        }
        $professionalActivity->update($request->all());

        return response()->json([
            'message' => 'Professional Activities updated successfully!',
            'Professional_activities' => $professionalActivity
        ], 200);
    }

    public function delete($id)
    {
        $professionalActivity = professional_activities::find($id);

        if (!$professionalActivity) {
            return response()->json([
                'error' => 'Data Not Found!'
            ], 404);
        }
        $professionalActivity->delete();

        return response()->json([
            'message' => 'Professional Activities deleted successfully!',
        ], 200);
    }
}
