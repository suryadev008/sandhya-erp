<?php

namespace App\Http\Controllers;

use App\DataTables\MachineDataTable;
use App\Models\Machine;
use App\Models\MachineType;
use Illuminate\Http\Request;

class MachineController extends Controller
{
    public function index(MachineDataTable $dataTable)
    {
        if (request()->ajax()) {
            return $dataTable->ajax();
        }
        $machineTypes = MachineType::active()->orderBy('type_name')->get(['id', 'type_name']);
        return view('machines.index', compact('machineTypes'));
    }

    public function create() {}

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'machine_name'   => 'required|string|max:255',
                'machine_number' => 'required|string|max:255|unique:machines',
                'machine_type_id'=> 'required|exists:machine_types,id',
                'working'        => 'nullable|string',
            ]);

            $validated['is_active'] = $request->has('is_active');
            Machine::create($validated);

            return response()->json(['success' => true, 'message' => 'Machine created successfully.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Validation error', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }

    public function show(string $id)
    {
        $machine = Machine::with('machineType')->findOrFail($id);
        return response()->json(['success' => true, 'data' => $machine]);
    }

    public function edit(string $id)
    {
        $machine = Machine::findOrFail($id);
        return response()->json(['success' => true, 'data' => $machine]);
    }

    public function update(Request $request, string $id)
    {
        try {
            $machine = Machine::findOrFail($id);

            $validated = $request->validate([
                'machine_name'   => 'required|string|max:255',
                'machine_number' => 'required|string|max:255|unique:machines,machine_number,' . $id,
                'machine_type_id'=> 'required|exists:machine_types,id',
                'working'        => 'nullable|string',
            ]);

            $validated['is_active'] = $request->has('is_active');
            $machine->update($validated);

            return response()->json(['success' => true, 'message' => 'Machine updated successfully.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Validation error', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            Machine::findOrFail($id)->delete();
            return response()->json(['success' => true, 'message' => 'Machine deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Unable to delete machine.'], 500);
        }
    }
}
