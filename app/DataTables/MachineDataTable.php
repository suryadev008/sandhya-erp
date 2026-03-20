<?php

namespace App\DataTables;

use App\Models\Machine;
use Yajra\DataTables\Facades\DataTables;

class MachineDataTable
{
    public function ajax()
    {
        $query = Machine::query()->orderBy('id', 'desc');

        $status = request('status');
        if ($status !== null && $status !== '') {
            $query->where('is_active', $status);
        }

        $type = request('type');
        if ($type !== null && $type !== '') {
            $query->where('machine_type', $type);
        }

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('is_active', function ($machine) {
            return $machine->is_active
                ? 'Active'
                : 'Inactive';
        })
            ->editColumn('machine_type', function ($machine) {
            return Machine::getMachineTypes()[$machine->machine_type] ?? ucfirst($machine->machine_type);
        })
            ->addColumn('action', function ($machine) {
            return '
                    <button type="button" class="btn btn-warning btn-sm edit-btn" data-id="' . $machine->id . '" data-toggle="modal" data-target="#edit-module-popup">
                        <i class="fas fa-edit"></i>
                    </button>
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