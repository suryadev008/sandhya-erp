<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeSalary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\DataTables\EmployeesDataTable;

class EmployeeController extends Controller
{
    public function nextCode()
    {
        $nextId = (Employee::max('id') ?? 0) + 1;
        return response()->json(['emp_code' => 'EMP' . str_pad($nextId, 3, '0', STR_PAD_LEFT)]);
    }

    public function index(EmployeesDataTable $dataTable)
    {
        if (request()->ajax()) {
            return $dataTable->ajax();
        }
        return view('employees.index');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'              => 'required|string|max:255',
                'aadhar_no'         => 'nullable|string|size:12|unique:employees,aadhar_no',
                'mobile_primary'    => 'required|string|size:10',
                'mobile_secondary'  => 'nullable|string|size:10',
                'whatsapp_no'       => 'nullable|string|size:10',
                'upi_number'        => 'nullable|string|max:50',
                'permanent_address' => 'nullable|string',
                'present_address'   => 'required|string',
                'bank_account_no'   => 'nullable|string|max:20',
                'bank_name'         => 'nullable|string|max:100',
                'ifsc_code'         => 'nullable|string|max:11',
                'employee_type'     => 'required|in:lathe,cnc,both',
                'experience_years'  => 'nullable|numeric|min:0|max:99.9',
                'joining_date'      => 'nullable|date',
                'status'            => 'required|in:active,inactive,terminated',
                'aadhar_image'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            if ($request->hasFile('aadhar_image')) {
                $validated['aadhar_image'] = $request->file('aadhar_image')->store('employees/aadhar', 'public');
            }

            // Auto-generate emp_code
            $nextId = (Employee::max('id') ?? 0) + 1;
            $validated['emp_code'] = 'EMP' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

            $employee = Employee::create($validated);

            // Save initial salary if provided
            if ($request->filled('per_day') && $request->filled('effect_from')) {
                EmployeeSalary::create([
                    'employee_id' => $employee->id,
                    'per_day'     => $request->per_day,
                    'per_month'   => $request->per_month,
                    'effect_from' => $request->effect_from,
                    'remark'      => $request->remark ?: 'Joining Salary',
                    'created_by'  => auth()->id(),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Employee created successfully.'
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
        $employee = Employee::with(['currentSalary', 'salaries'])->findOrFail($id);
        return view('employees.show', compact('employee'));
    }

    public function storeSalary(Request $request, string $id)
    {
        try {
            $employee = Employee::findOrFail($id);

            $validated = $request->validate([
                'per_day'     => 'required|numeric|min:0',
                'per_month'   => 'required|numeric|min:0',
                'effect_from' => 'required|date',
                'remark'      => 'nullable|string|max:255',
            ]);

            $validated['employee_id'] = $employee->id;
            $validated['created_by']  = auth()->id();

            $salary = EmployeeSalary::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Salary added successfully.',
                'data'    => $salary,
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

    public function salaryHistory(string $id)
    {
        $employee = Employee::findOrFail($id);
        $salaries = $employee->salaries()->with('createdBy')->get();
        return response()->json(['success' => true, 'data' => $salaries]);
    }

    public function edit(string $id)
    {
        $employee = Employee::findOrFail($id);
        return response()->json([
            'success' => true,
            'data'    => $employee
        ]);
    }

    public function update(Request $request, string $id)
    {
        try {
            $employee = Employee::findOrFail($id);
            $validated = $request->validate([
                'emp_code'          => 'required|string|max:20|unique:employees,emp_code,' . $id,
                'name'              => 'required|string|max:255',
                'aadhar_no'         => 'nullable|string|size:12|unique:employees,aadhar_no,' . $id,
                'mobile_primary'    => 'required|string|size:10',
                'mobile_secondary'  => 'nullable|string|size:10',
                'whatsapp_no'       => 'nullable|string|size:10',
                'upi_number'        => 'nullable|string|max:50',
                'permanent_address' => 'nullable|string',
                'present_address'   => 'required|string',
                'bank_account_no'   => 'nullable|string|max:20',
                'bank_name'         => 'nullable|string|max:100',
                'ifsc_code'         => 'nullable|string|max:11',
                'employee_type'     => 'required|in:lathe,cnc,both',
                'experience_years'  => 'nullable|numeric|min:0|max:99.9',
                'joining_date'      => 'nullable|date',
                'status'            => 'required|in:active,inactive,terminated',
                'aadhar_image'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            if ($request->hasFile('aadhar_image')) {
                if ($employee->aadhar_image) {
                    Storage::disk('public')->delete($employee->aadhar_image);
                }
                $validated['aadhar_image'] = $request->file('aadhar_image')->store('employees/aadhar', 'public');
            }

            $employee->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Employee updated successfully.'
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
            Employee::findOrFail($id)->delete();
            return response()->json([
                'success' => true,
                'message' => 'Employee deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to delete employee.'
            ], 500);
        }
    }
}
