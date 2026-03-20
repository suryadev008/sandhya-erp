<?php

namespace App\Http\Controllers;

use App\Models\Operation;
use Illuminate\Http\Request;
use App\DataTables\OperationDataTable;

class OperationController extends Controller
{
    public function index(OperationDataTable $dataTable)
    {
        if (request()->ajax()) {
            return $dataTable->ajax();
        }
        return view('operations.index');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'operation_name' => 'required|string|max:255',
                'price'          => 'required|numeric',
                'applicable_for' => 'required|in:lathe,cnc,both',
                'remark'         => 'nullable|string',
            ]);

            $validated['is_active'] = $request->has('is_active') ? true : false;
            Operation::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Operation created successfully.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
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
        $operation = Operation::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $operation
        ]);
    }

    public function update(Request $request, string $id)
    {
        try {
            $operation = Operation::findOrFail($id);
            $validated = $request->validate([
                'operation_name' => 'required|string|max:255',
                'price'          => 'required|numeric',
                'applicable_for' => 'required|in:lathe,cnc,both',
                'remark'         => 'nullable|string',
            ]);

            $validated['is_active'] = $request->has('is_active') ? true : false;
            $operation->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Operation updated successfully.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.'
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            Operation::findOrFail($id)->delete();
            return response()->json([
                'success' => true,
                'message' => 'Operation deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to delete operation.'
            ], 500);
        }
    }
}
