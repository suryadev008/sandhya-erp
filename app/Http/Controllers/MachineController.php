<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\DataTables\MachineDataTable;
use App\Models\Machine;


class MachineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     return view('machines.index');
    // }


    public function index(MachineDataTable $dataTable)
    {
        // AJAX request → JSON return करो
        if (request()->ajax()) {
            return $dataTable->ajax();
        }

        // Normal request → View return करो
        return view('machines.index');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'machine_name' => 'required|string|max:255',
                'machine_number' => 'required|string|max:255|unique:machines',
                'machine_type' => 'required|string',
            ]);

            $validated['is_active'] = $request->has('is_active') ? true : false;

            Machine::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Machine created successfully.'
            ]);
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        }
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $machine = Machine::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $machine
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $machine = Machine::findOrFail($id);

            $validated = $request->validate([
                'machine_name' => 'required|string|max:255',
                'machine_number' => 'required|string|max:255|unique:machines,machine_number,' . $id,
                'machine_type' => 'required|string',
            ]);

            $validated['is_active'] = $request->has('is_active') ? true : false;

            $machine->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Machine updated successfully.'
            ]);
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        }
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(string $id)
    {
        try {
            $machine = Machine::findOrFail($id);
            $machine->delete();

            return response()->json([
                'success' => true,
                'message' => 'Machine deleted successfully.'
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to delete machine.'
            ], 500);
        }
    }
}
