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
            ->editColumn('working', function ($machine) {
                if (!$machine->working) return '—';
                return '<span style="white-space:pre-wrap;word-break:break-word;">' . e($machine->working) . '</span>';
            })
            ->addColumn('action', function ($machine) {
                $html = '<button type="button" class="btn btn-info btn-sm view-btn" data-id="' . $machine->id . '" data-toggle="modal" data-target="#view-module-popup" title="View"><i class="fas fa-eye"></i></button> ';
                if (auth()->user()->can('edit machines')) {
                    $html .= '<button type="button" class="btn btn-warning btn-sm edit-btn" data-id="' . $machine->id . '" data-toggle="modal" data-target="#edit-module-popup" title="Edit"><i class="fas fa-edit"></i></button> ';
                }
                if (auth()->user()->can('delete machines')) {
                    $html .= '<button class="btn btn-sm btn-danger delete-btn" data-id="' . $machine->id . '" title="Delete"><i class="fas fa-trash"></i></button>';
                }
                return $html;
            })
            ->rawColumns(['is_active', 'machine_type_id', 'working', 'action'])
            ->toJson();
    }
}
