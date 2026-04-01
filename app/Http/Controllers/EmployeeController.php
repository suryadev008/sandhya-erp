<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeSalary;
use App\Models\EmployeeOperationRate;
use App\Models\Operation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\DataTables\EmployeesDataTable;

class EmployeeController extends Controller
{
    public function nextCode()
    {
        $count = Employee::withTrashed()->max('id') ?? 0;
        $next  = $count + 1;
        return response()->json(['emp_code' => 'EMP' . str_pad($next, 3, '0', STR_PAD_LEFT)]);
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
            DB::beginTransaction();

            $validated = $request->validate([
                'name'              => 'required|string|max:255',
                'aadhar_no'         => 'nullable|regex:/^\d{12}$/|unique:employees,aadhar_no',
                'mobile_primary'    => 'required|regex:/^[6-9]\d{9}$/',
                'mobile_secondary'  => 'nullable|regex:/^[6-9]\d{9}$/',
                'whatsapp_no'       => 'nullable|regex:/^[6-9]\d{9}$/',
                'upi_number'        => 'nullable|string|max:50',
                'permanent_address' => 'nullable|string',
                'present_address'   => 'required|string',
                'bank_account_no'   => 'nullable|string|max:20',
                'bank_name'         => 'nullable|string|max:100',
                'ifsc_code'         => 'nullable|string|max:11',
                'employee_type'        => 'required|in:lathe,cnc,both',
                'cnc_payment_type'     => 'nullable|in:day_rate,per_piece',
                'cnc_target_per_shift' => 'nullable|integer|min:1|max:9999',
                'cnc_incentive_rate'   => 'nullable|numeric|min:0',
                'experience_years'     => 'nullable|numeric|min:0|max:99.9',
                'joining_date'         => 'nullable|date',
                'status'               => 'required|in:active,inactive,terminated',
                'aadhar_image'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            // Set CNC defaults for non-CNC employees so DB doesn't get null
            $validated['cnc_payment_type']     = $validated['cnc_payment_type']     ?? 'day_rate';
            $validated['cnc_target_per_shift'] = $validated['cnc_target_per_shift'] ?? 90;
            $validated['cnc_incentive_rate']   = $validated['cnc_incentive_rate']   ?? 0;

            if ($request->hasFile('aadhar_image')) {
                $validated['aadhar_image'] = $request->file('aadhar_image')->store('employees/aadhar', 'public');
            }

            // Auto-generate emp_code inside a lock to prevent race condition
            $maxId = Employee::withTrashed()->lockForUpdate()->max('id') ?? 0;
            $validated['emp_code'] = 'EMP' . str_pad($maxId + 1, 3, '0', STR_PAD_LEFT);

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

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Employee created successfully.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Employee store failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.'
            ], 500);
        }
    }

    public function show(string $id)
    {
        $employee   = Employee::with(['currentSalary', 'salaries'])->findOrFail($id);
        $operations = Operation::active()->orderBy('operation_name')->get(['id', 'operation_name', 'applicable_for']);
        return view('employees.show', compact('employee', 'operations'));
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
            Log::error('Salary store failed', ['employee_id' => $id, 'error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.'
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
                'aadhar_no'         => 'nullable|regex:/^\d{12}$/|unique:employees,aadhar_no,' . $id,
                'mobile_primary'    => 'required|regex:/^[6-9]\d{9}$/',
                'mobile_secondary'  => 'nullable|regex:/^[6-9]\d{9}$/',
                'whatsapp_no'       => 'nullable|regex:/^[6-9]\d{9}$/',
                'upi_number'        => 'nullable|string|max:50',
                'permanent_address' => 'nullable|string',
                'present_address'   => 'required|string',
                'bank_account_no'   => 'nullable|string|max:20',
                'bank_name'         => 'nullable|string|max:100',
                'ifsc_code'         => 'nullable|string|max:11',
                'employee_type'        => 'required|in:lathe,cnc,both',
                'cnc_payment_type'     => 'nullable|in:day_rate,per_piece',
                'cnc_target_per_shift' => 'nullable|integer|min:1|max:9999',
                'cnc_incentive_rate'   => 'nullable|numeric|min:0',
                'experience_years'     => 'nullable|numeric|min:0|max:99.9',
                'joining_date'         => 'nullable|date',
                'status'               => 'required|in:active,inactive,terminated',
                'aadhar_image'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            if ($request->hasFile('aadhar_image')) {
                $newPath = $request->file('aadhar_image')->store('employees/aadhar', 'public');
                if ($newPath === false) {
                    throw new \RuntimeException('Failed to upload Aadhar image.');
                }
                // Delete old file only after new upload succeeded
                if ($employee->aadhar_image) {
                    Storage::disk('public')->delete($employee->aadhar_image);
                }
                $validated['aadhar_image'] = $newPath;
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
            Log::error('Employee update failed', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.'
            ], 500);
        }
    }

    // ── Operation Rates ────────────────────────────────────────────────────

    /** List all operation rates for an employee (AJAX) */
    public function operationRates(string $id)
    {
        $employee = Employee::findOrFail($id);
        $rates = EmployeeOperationRate::where('employee_id', $id)
            ->with('operation')
            ->orderBy('operation_id')
            ->orderBy('applicable_from', 'desc')
            ->get()
            ->map(fn($r) => [
                'id'             => $r->id,
                'operation_id'   => $r->operation_id,
                'operation_name' => $r->operation->operation_name ?? '—',
                'rate'           => number_format($r->rate, 2),
                'applicable_from'=> $r->applicable_from->format('d M Y'),
                'applicable_from_raw' => $r->applicable_from->format('Y-m-d'),
                'remark'         => $r->remark,
            ]);

        return response()->json(['success' => true, 'data' => $rates]);
    }

    /** Store a new operation rate for an employee */
    public function storeOperationRate(Request $request, string $id)
    {
        try {
            $employee = Employee::findOrFail($id);

            $validated = $request->validate([
                'operation_id'    => 'required|integer|exists:operations,id',
                'rate'            => 'required|numeric|min:0',
                'applicable_from' => 'required|date',
                'remark'          => 'nullable|string|max:255',
            ]);

            $validated['employee_id'] = $employee->id;
            $validated['created_by']  = auth()->id();

            $rate = EmployeeOperationRate::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Operation rate saved successfully.',
                'data'    => $rate,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Employee operation rate store failed', ['employee_id' => $id, 'error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Something went wrong.'], 500);
        }
    }

    /** Delete an operation rate record */
    public function destroyOperationRate(string $id, string $rateId)
    {
        try {
            $rate = EmployeeOperationRate::where('employee_id', $id)->findOrFail($rateId);
            $rate->delete();
            return response()->json(['success' => true, 'message' => 'Rate deleted.']);
        } catch (\Exception $e) {
            Log::error('Employee operation rate delete failed', ['rate_id' => $rateId, 'error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Something went wrong.'], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $employee = Employee::findOrFail($id);
            Log::info('Employee soft-deleted', [
                'employee_id'   => $employee->id,
                'emp_code'      => $employee->emp_code,
                'deleted_by'    => auth()->id(),
                'deleted_at'    => now()->toDateTimeString(),
            ]);
            $employee->delete();
            return response()->json([
                'success' => true,
                'message' => 'Employee deleted successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Employee delete failed', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Unable to delete employee.'
            ], 500);
        }
    }
}
