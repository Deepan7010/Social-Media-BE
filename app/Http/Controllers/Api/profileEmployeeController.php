<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class profileEmployeeController extends Controller
{
    public function create(Request $request)
    {

        $validated = Validator::make($request->all(), [
            'organization' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'profile_id' => 'required|exists:profiles,id',
        ]);


        // If validation fails, return errors
        if ($validated->fails()) {
            return response()->json([
                'error' => $validated->errors()
            ], 400);
        }

        // Create the employee record
        $employee = employee::create($request->all());

        return response()->json([
            'message' => 'Employee created successfully!',
            'employee' => $employee
        ], 201);
    }

    public function edit(Request $request, $id)
    {
        // Find the employee by ID
        $employee = employee::find($id);

        // If the employee is not found, return a 404 response
        if (!$employee) {
            return response()->json([
                'error' => 'Data Not Found!'
            ], 404);
        }

        // Validate the incoming request
        $validated = Validator::make($request->all(), [
            'organization' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date', // Ensuring end_date is after start_date if provided
            'profile_id' => 'required|exists:profiles,id',
        ]);

        // If validation fails, return errors
        if ($validated->fails()) {
            return response()->json([
                'error' => $validated->errors()
            ], 400);
        }

        // Update the employee record
        $employee->organization = $request->organization;
        $employee->city = $request->city;
        $employee->region = $request->region;
        $employee->country = $request->country;
        $employee->department = $request->department;
        $employee->role = $request->role;
        $employee->start_date = $request->start_date;
        $employee->end_date = $request->end_date;
        $employee->profile_id = $request->profile_id;

        // Save the updated employee
        $employee->save();

        // Return response with the updated employee data
        return response()->json([
            'message' => 'Employee updated successfully!',
            'employee' => $employee
        ], 200);
    }

    public function delete($id)
    {
        // Find the employee by ID
        $employee = employee::find($id);

        // If the employee is not found, return a 404 response
        if (!$employee) {
            return response()->json([
                'error' => 'Data Not Found!'
            ], 404);
        }

        // Delete the employee record
        $employee->delete();

        // Return response confirming deletion
        return response()->json([
            'message' => 'Employee deleted successfully!'
        ], 200);
    }
}
