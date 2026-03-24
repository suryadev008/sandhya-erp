<?php

namespace App\DataTables;

use App\Models\MachineType;
use Yajra\DataTables\Facades\DataTables;

class MachineTypeDataTable
{
    public function ajax()
    {
        $query = MachineType::query()->orderBy('id', 'desc');

        $status = request('status');
        if ($status !== null && $status !== '') {
            $query->where('is_active', $status);
        }

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('is_active', function ($type) {
                return $type->is_active ? 'Active' : 'Inactive';
            })
            ->addColumn('action', function ($type) {
                return '
                    <button type="button" class="btn btn-warning btn-sm edit-btn"
                        data-id="' . $type->id . '" data-toggle="modal" data-target="#edit-module-popup">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="' . $type->id . '">
                        <i class="fas fa-trash"></i>
                    </button>
                ';
            })
            ->rawColumns(['action'])
            ->toJson();
    }
}
