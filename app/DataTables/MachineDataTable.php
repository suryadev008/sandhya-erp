<?php

namespace App\DataTables;

use App\Models\Machine;
use Yajra\DataTables\Facades\DataTables;

class MachineDataTable
{
    public function ajax()
    {
        return DataTables::eloquent(Machine::query())
            ->editColumn('is_active', function ($machine) {
            return $machine->is_active
                ? '<span class="badge badge-success">Active</span>'
                : '<span class="badge badge-danger">Inactive</span>';
        })
            ->editColumn('machine_type', function ($machine) {
            $colors = [
                'lathe' => 'primary',
                'cnc' => 'success',
                'drill' => 'warning',
                'tap' => 'danger',
            ];
            $color = $colors[$machine->machine_type] ?? 'secondary';
            return '<span class="badge badge-' . $color . '">'
                . ucfirst($machine->machine_type) . '</span>';
        })
            ->addColumn('action', function ($machine) {
            return '
                    <a href="' . route('machines.edit', $machine->id) . '"
                        class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button class="btn btn-sm btn-danger delete-btn"
                        data-id="' . $machine->id . '">
                        <i class="fas fa-trash"></i>
                    </button>
                ';
        })
            ->rawColumns(['is_active', 'machine_type', 'action'])
            ->toJson();
    }
}