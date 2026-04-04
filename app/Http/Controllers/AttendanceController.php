<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\LatheProduction;
use App\Models\CncProduction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        // ── Filters ────────────────────────────────────────────────────────
        $fromDate   = $request->filled('from_date')
            ? Carbon::parse($request->from_date)->startOfDay()
            : Carbon::now()->startOfMonth();

        $toDate     = $request->filled('to_date')
            ? Carbon::parse($request->to_date)->endOfDay()
            : Carbon::now()->endOfDay();

        // Safety: max 92-day window to avoid huge queries
        if ($toDate->diffInDays($fromDate) > 92) {
            $toDate = $fromDate->copy()->addDays(92)->endOfDay();
        }

        $employeeId = $request->filled('employee_id') ? (int) $request->employee_id : null;
        $shift      = $request->filled('shift') ? $request->shift : null;
        $machineType = $request->filled('machine_type') ? $request->machine_type : 'all'; // all|lathe|cnc
        $viewMode   = $request->input('view_mode', 'summary'); // summary|detail

        // ── Fetch Lathe Entries ────────────────────────────────────────────
        $latheRows = [];
        if ($machineType !== 'cnc') {
            $latheQuery = LatheProduction::with('employee')
                ->whereBetween('date', [$fromDate->toDateString(), $toDate->toDateString()])
                ->when($employeeId, fn($q) => $q->where('employee_id', $employeeId))
                ->when($shift, fn($q) => $q->where('shift', $shift))
                ->orderBy('date')
                ->orderBy('employee_id')
                ->get();

            foreach ($latheQuery as $row) {
                $latheRows[] = [
                    'employee_id'      => $row->employee_id,
                    'employee_name'    => $row->employee?->name ?? '—',
                    'emp_code'         => $row->employee?->emp_code ?? '—',
                    'date'             => $row->date->toDateString(),
                    'shift'            => $row->shift,
                    'machine_type'     => 'Lathe',
                    'qty'              => (int) $row->qty,
                    'amount'           => (float) $row->amount,
                    'is_half_day'      => (bool) $row->is_half_day,
                    'downtime_type'    => $row->downtime_type,
                    'downtime_minutes' => $row->downtime_minutes,
                ];
            }
        }

        // ── Fetch CNC Entries ─────────────────────────────────────────────
        $cncRows = [];
        if ($machineType !== 'lathe') {
            $cncQuery = CncProduction::with('employee')
                ->whereBetween('date', [$fromDate->toDateString(), $toDate->toDateString()])
                ->when($employeeId, fn($q) => $q->where('employee_id', $employeeId))
                ->when($shift, fn($q) => $q->where('shift', $shift))
                ->orderBy('date')
                ->orderBy('employee_id')
                ->get();

            foreach ($cncQuery as $row) {
                $cncRows[] = [
                    'employee_id'      => $row->employee_id,
                    'employee_name'    => $row->employee?->name ?? '—',
                    'emp_code'         => $row->employee?->emp_code ?? '—',
                    'date'             => $row->date->toDateString(),
                    'shift'            => $row->shift,
                    'machine_type'     => 'CNC',
                    'qty'              => (int) $row->production_qty,
                    'amount'           => (float) $row->amount,
                    'is_half_day'      => (bool) $row->is_half_day,
                    'downtime_type'    => $row->downtime_type,
                    'downtime_minutes' => $row->downtime_minutes,
                ];
            }
        }

        $allRows = array_merge($latheRows, $cncRows);

        // ── Aggregate: one record per employee+date (for daily view) ───────
        // Key: employee_id|date  → merged data
        $dailyMap = [];
        foreach ($allRows as $row) {
            $key = $row['employee_id'] . '|' . $row['date'];
            if (!isset($dailyMap[$key])) {
                $dailyMap[$key] = [
                    'employee_id'      => $row['employee_id'],
                    'employee_name'    => $row['employee_name'],
                    'emp_code'         => $row['emp_code'],
                    'date'             => $row['date'],
                    'shifts'           => [],
                    'machine_types'    => [],
                    'total_qty'        => 0,
                    'total_amount'     => 0.0,
                    'is_half_day'      => false,
                    'has_downtime'     => false,
                    'downtime_minutes' => 0,
                ];
            }
            $dailyMap[$key]['total_qty']     += $row['qty'];
            $dailyMap[$key]['total_amount']  += $row['amount'];
            if ($row['is_half_day'])   $dailyMap[$key]['is_half_day']  = true;
            if ($row['downtime_type']) {
                $dailyMap[$key]['has_downtime']     = true;
                $dailyMap[$key]['downtime_minutes'] += (int)($row['downtime_minutes'] ?? 0);
            }
            if (!in_array($row['shift'], $dailyMap[$key]['shifts'])) {
                $dailyMap[$key]['shifts'][] = $row['shift'];
            }
            if (!in_array($row['machine_type'], $dailyMap[$key]['machine_types'])) {
                $dailyMap[$key]['machine_types'][] = $row['machine_type'];
            }
        }

        // Sort by date then employee
        uasort($dailyMap, fn($a, $b) => $a['date'] !== $b['date']
            ? strcmp($a['date'], $b['date'])
            : strcmp($a['emp_code'], $b['emp_code'])
        );

        $dailyRecords = array_values($dailyMap);

        // ── Summary: grouped by employee ──────────────────────────────────
        $summaryMap = [];
        foreach ($dailyMap as $record) {
            $empId = $record['employee_id'];
            if (!isset($summaryMap[$empId])) {
                $summaryMap[$empId] = [
                    'employee_id'   => $empId,
                    'employee_name' => $record['employee_name'],
                    'emp_code'      => $record['emp_code'],
                    'total_days'    => 0,
                    'full_days'     => 0,
                    'half_days'     => 0,
                    'downtime_days' => 0,
                    'lathe_days'    => 0,
                    'cnc_days'      => 0,
                    'total_qty'     => 0,
                    'total_amount'  => 0.0,
                ];
            }
            $s = &$summaryMap[$empId];
            $s['total_days']++;
            $s['total_qty']    += $record['total_qty'];
            $s['total_amount'] += $record['total_amount'];
            if ($record['is_half_day']) $s['half_days']++;
            else                        $s['full_days']++;
            if ($record['has_downtime']) $s['downtime_days']++;
            if (in_array('Lathe', $record['machine_types'])) $s['lathe_days']++;
            if (in_array('CNC',   $record['machine_types'])) $s['cnc_days']++;
        }

        // Sort summary by emp_code
        uasort($summaryMap, fn($a, $b) => strcmp($a['emp_code'], $b['emp_code']));
        $summaryRecords = array_values($summaryMap);

        // ── Totals ─────────────────────────────────────────────────────────
        $totalPresent  = count($dailyMap);
        $totalHalf     = count(array_filter($dailyMap, fn($r) => $r['is_half_day']));
        $totalFull     = $totalPresent - $totalHalf;
        $totalQty      = array_sum(array_column($dailyMap, 'total_qty'));
        $totalAmount   = array_sum(array_column($dailyMap, 'total_amount'));
        $totalDowntime = count(array_filter($dailyMap, fn($r) => $r['has_downtime']));

        // ── Employees list for filter ──────────────────────────────────────
        $employees = Employee::whereIn('employee_type', ['lathe', 'cnc', 'both'])
            ->orderBy('emp_code')
            ->get(['id', 'emp_code', 'name', 'employee_type']);

        return view('attendance.index', compact(
            'fromDate', 'toDate', 'employeeId', 'shift', 'machineType', 'viewMode',
            'dailyRecords', 'summaryRecords',
            'totalPresent', 'totalHalf', 'totalFull', 'totalQty', 'totalAmount', 'totalDowntime',
            'employees'
        ));
    }
}
