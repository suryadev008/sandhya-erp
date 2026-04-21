<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    /** GET /master/designations — list all (JSON) */
    public function index()
    {
        $designations = Designation::orderBy('name')->get(['id', 'name', 'is_active']);
        return response()->json(['success' => true, 'data' => $designations]);
    }

    /** POST /master/designations — create */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:designations,name',
        ]);

        $designation = Designation::create([
            'name'      => trim($request->name),
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Designation added successfully.',
            'data'    => $designation,
        ]);
    }

    /** DELETE /master/designations/{id} — remove */
    public function destroy(string $id)
    {
        $designation = Designation::findOrFail($id);

        // Nullify FK on linked companies before deleting
        $designation->companies()->update(['designation_id' => null]);
        $designation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Designation deleted successfully.',
        ]);
    }
}
