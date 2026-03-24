<?php

namespace App\DataTables;

use App\Models\Machine;
use Yajra\DataTables\Facades\DataTables;

class MachineDataTable
{
    public function ajax()
    {
        $query = Machine::with('machineType')->orderBy('id', 'desc');

        $status = request('status');
        if ($status !== null && $status !== '') {
            $query->where('is_active', $status);
        }

        $typeId = request('type');
        if ($typeId !== null && $typeId !== '') {
            $query->where('machine_type_id', $typeId);
        }

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('machine_type_id', function ($machine) {
                return $machine->machineType ? $machine->machineType->type_name : '—';
            })
            ->editColumn('is_active', function ($machine) {
                return $machine->is_active ? 'Active' : 'Inactive';
            })
            ->addColumn('action', function ($machine) {
                return '
                    <button type="button" class="btn btn-warning btn-sm edit-btn"
                        data-id="' . $machine->id . '" data-toggle="modal" data-target="#edit-module-popup">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="' . $machine->id . '">
                        <i class="fas fa-trash"></i>
                    </button>
                ';
            })
            ->rawColumns(['is_active', 'machine_type_id', 'action'])
            ->toJson();
    }
}
