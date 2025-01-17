<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\funding_details;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class profileFundingController extends Controller
{
    public function create(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'funding_type' => 'required|string|max:255',
            'funding_subtype' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'project_link' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'total_funding_amt' => 'required|string|max:255',
            'funding_agency_name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'funding_identifier' => 'required|string|max:255',
            'grant_link' => 'required',
            'relationship' => 'required|string|max:255',
            'profile_id' => 'required|exists:profiles,id',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'error' => $validated->errors()
            ], 404);
        }

        
        $fundingDetail = funding_details::create($request->all());

        return response()->json([
            'message' => 'Funding Detail created successfully!',
            'funding_detail' => $fundingDetail
        ], 201);
    }

    
    public function edit(Request $request, $id)
    {
        $validated = Validator::make($request->all(), [
            'funding_type' => 'required|string|max:255',
            'funding_subtype' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'project_link' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'total_funding_amt' => 'required|string|max:255',
            'funding_agency_name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'funding_identifier' => 'required|string|max:255',
            'grant_link' => 'required',
            'relationship' => 'required|string|max:255',
            'profile_id' => 'required|exists:profiles,id',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'error' => $validated->errors()
            ], 404);
        }


        $fundingDetail = funding_details::find($id);

        if (!$fundingDetail) {
            return response()->json([
                'error' => 'Data Not Found!'
            ], 404);
        }

        $fundingDetail->update($request->all());

        return response()->json([
            'message' => 'Funding Detail updated successfully!',
            'funding_detail' => $fundingDetail
        ], 200);
    }

    
    public function delete($id)
    {
        $fundingDetail = funding_details::find($id);

        if (!$fundingDetail) {
            return response()->json([
                'error' => 'Data Not Found!'
            ], 404);
        }

        $fundingDetail->delete();

        return response()->json([
            'message' => 'Funding Detail deleted successfully!'
        ], 200);
    }
}
