<?php

namespace App\Http\Controllers;

use App\DataTables\OperationDataTable;
use App\Models\Company;
use App\Models\Operation;
use App\Models\OperationPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OperationController extends Controller
{
    public function index(OperationDataTable $dataTable)
    {
        if (request()->ajax()) {
            return $dataTable->ajax();
        }
        $companies = Company::active()->orderBy('company_name')->get(['id', 'company_name']);
        return view('operations.index', compact('companies'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'company_id'     => 'required|exists:companies,id',
                'operation_name' => 'required|string|max:255',
                'applicable_for' => 'required|in:lathe,cnc,both',
                'remark'         => 'nullable|string',
                'price'          => 'required|numeric|min:0',
                'applicable_from'=> 'required|date',
            ]);

            $validated['is_active'] = $request->has('is_active');

            $operation = Operation::create([
                'company_id'     => $validated['company_id'],
                'operation_name' => $validated['operation_name'],
                'applicable_for' => $validated['applicable_for'],
                'remark'         => $validated['remark'] ?? null,
                'is_active'      => $validated['is_active'],
            ]);

            OperationPrice::create([
                'operation_id'   => $operation->id,
                'price'          => $validated['price'],
                'applicable_from'=> $validated['applicable_from'],
                'remark'         => 'Initial price',
                'created_by'     => auth()->id(),
            ]);

            return response()->json(['success' => true, 'message' => 'Operation created successfully.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Validation error', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Operation failed', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Something went wrong. Please try again.'], 500);
        }
    }

    public function show(string $id)
    {
        $operation = Operation::with(['company', 'prices.creator'])->findOrFail($id);
        return view('operations.show', compact('operation'));
    }

    public function edit(string $id)
    {
        $operation = Operation::findOrFail($id);
        return response()->json(['success' => true, 'data' => $operation]);
    }

    public function update(Request $request, string $id)
    {
        try {
            $operation = Operation::findOrFail($id);
            $validated = $request->validate([
                'company_id'     => 'required|exists:companies,id',
                'operation_name' => 'required|string|max:255',
                'applicable_for' => 'required|in:lathe,cnc,both',
                'remark'         => 'nullable|string',
            ]);

            $validated['is_active'] = $request->has('is_active');
            $operation->update($validated);

            return response()->json(['success' => true, 'message' => 'Operation updated successfully.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Validation error', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Something went wrong.'], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            Operation::findOrFail($id)->delete();
            return response()->json(['success' => true, 'message' => 'Operation deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Unable to delete operation.'], 500);
        }
    }

    /** Append a new price record — never overwrites history */
    public function storePrice(Request $request, string $id)
    {
        try {
            $operation = Operation::findOrFail($id);
            $validated = $request->validate([
                'price'          => 'required|numeric|min:0',
                'applicable_from'=> 'required|date',
                'remark'         => 'nullable|string|max:255',
            ]);

            $price = OperationPrice::create([
                'operation_id'   => $operation->id,
                'price'          => $validated['price'],
                'applicable_from'=> $validated['applicable_from'],
                'remark'         => $validated['remark'] ?? null,
                'created_by'     => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Price record added successfully.',
                'data'    => $price->load('creator'),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Validation error', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Operation failed', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Something went wrong. Please try again.'], 500);
        }
    }

    /** Return full price history as JSON */
    public function priceHistory(string $id)
    {
        $operation = Operation::findOrFail($id);
        $prices    = $operation->prices()->with('creator')->get();
        return response()->json(['success' => true, 'data' => $prices]);
    }
}
