<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\Company;
use Illuminate\Http\Request;
use App\DataTables\PartsDataTable;

class PartController extends Controller
{
    public function index(PartsDataTable $dataTable)
    {
        if (request()->ajax()) {
            return $dataTable->ajax();
        }
        $companies = Company::active()->orderBy('company_name')->get();
        return view('parts.index', compact('companies'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'company_id'  => 'required|exists:companies,id',
                'part_number' => 'required|string|max:100|unique:parts,part_number',
                'part_name'   => 'nullable|string|max:255',
                'description' => 'nullable|string',
            ]);

            $validated['is_active'] = $request->has('is_active') ? true : false;
            Part::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Part created successfully.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit(string $id)
    {
        $part = Part::findOrFail($id);
        return response()->json([
            'success' => true,
            'data'    => $part
        ]);
    }

    public function update(Request $request, string $id)
    {
        try {
            $part = Part::findOrFail($id);
            $validated = $request->validate([
                'company_id'  => 'required|exists:companies,id',
                'part_number' => 'required|string|max:100|unique:parts,part_number,' . $id,
                'part_name'   => 'nullable|string|max:255',
                'description' => 'nullable|string',
            ]);

            $validated['is_active'] = $request->has('is_active') ? true : false;
            $part->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Part updated successfully.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            Part::findOrFail($id)->delete();
            return response()->json([
                'success' => true,
                'message' => 'Part deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to delete part.'
            ], 500);
        }
    }
}
