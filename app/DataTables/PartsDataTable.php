<?php

namespace App\DataTables;

use App\Models\Part;
use Yajra\DataTables\Facades\DataTables;

class PartsDataTable
{
    public function ajax()
    {
        $query = Part::with('company')->orderBy('id', 'desc');

        $status = request('status');
        if ($status !== null && $status !== '') {
            $query->where('is_active', $status);
        }

        $companyId = request('company_id');
        if ($companyId !== null && $companyId !== '') {
            $query->where('company_id', $companyId);
        }

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('is_active', function ($part) {
                return $part->is_active ? 'Active' : 'Inactive';
            })
            ->addColumn('company_name', function ($part) {
                return $part->company ? $part->company->company_name : 'N/A';
            })
            ->addColumn('action', function ($part) {
                return '
                    <button type="button" class="btn btn-warning btn-sm edit-btn" data-id="' . $part->id . '" data-toggle="modal" data-target="#edit-module-popup">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="' . $part->id . '">
                        <i class="fas fa-trash"></i>
                    </button>
                ';
            })
            ->rawColumns(['action'])
            ->toJson();
    }
}
