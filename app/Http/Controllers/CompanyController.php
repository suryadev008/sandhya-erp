<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use App\DataTables\CompanyDataTable;

class CompanyController extends Controller
{
    public function index(CompanyDataTable $dataTable)
    {
        if (request()->ajax()) {
            return $dataTable->ajax();
        }
        return view('companies.index');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'company_name'   => 'required|string|max:255',
                'plant_name'     => 'nullable|string|max:255',
                'contact_person' => 'nullable|string|max:255',
                'contact_phone'  => 'nullable|string|max:50',
                'address'        => 'nullable|string',
                'remark'         => 'nullable|string',
            ]);

            $validated['is_active'] = $request->has('is_active') ? true : false;
            Company::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Company created successfully.'
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

    public function show(string $id)
    {
        $company = Company::findOrFail($id);
        return view('companies.show', compact('company'));
    }

    public function edit(string $id)
    {
        $company = Company::findOrFail($id);
        return response()->json([
            'success' => true,
            'data'    => $company
        ]);
    }

    public function update(Request $request, string $id)
    {
        try {
            $company = Company::findOrFail($id);
            $validated = $request->validate([
                'company_name'   => 'required|string|max:255',
                'plant_name'     => 'nullable|string|max:255',
                'contact_person' => 'nullable|string|max:255',
                'contact_phone'  => 'nullable|string|max:50',
                'address'        => 'nullable|string',
                'remark'         => 'nullable|string',
            ]);

            $validated['is_active'] = $request->has('is_active') ? true : false;
            $company->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Company updated successfully.'
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
            Company::findOrFail($id)->delete();
            return response()->json([
                'success' => true,
                'message' => 'Company deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to delete company.'
            ], 500);
        }
    }
}
