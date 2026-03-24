<?php

namespace App\Http\Controllers;

use App\DataTables\MachineTypeDataTable;
use App\Models\MachineType;
use Illuminate\Http\Request;

class MachineTypeController extends Controller
{
    public function index(MachineTypeDataTable $dataTable)
    {
        if (request()->ajax()) {
            return $dataTable->ajax();
        }
        return view('machine_types.index');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'type_name' => 'required|string|max:100|unique:machine_types,type_name',
                'remark'    => 'nullable|string|max:255',
            ]);

            $validated['is_active'] = $request->has('is_active') ? true : false;
            MachineType::create($validated);

            return response()->json(['success' => true, 'message' => 'Machine type created successfully.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Validation error', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }

    public function edit(string $id)
    {
        $type = MachineType::findOrFail($id);
        return response()->json(['success' => true, 'data' => $type]);
    }

    public function update(Request $request, string $id)
    {
        try {
            $type = MachineType::findOrFail($id);
            $validated = $request->validate([
                'type_name' => 'required|string|max:100|unique:machine_types,type_name,' . $id,
                'remark'    => 'nullable|string|max:255',
            ]);

            $validated['is_active'] = $request->has('is_active') ? true : false;
            $type->update($validated);

            return response()->json(['success' => true, 'message' => 'Machine type updated successfully.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Validation error', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $type = MachineType::findOrFail($id);

            if ($type->machines()->count() > 0) {
                return response()->json(['success' => false, 'message' => 'Cannot delete: machines are assigned to this type.'], 422);
            }

            $type->delete();
            return response()->json(['success' => true, 'message' => 'Machine type deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Unable to delete machine type.'], 500);
        }
    }
}
