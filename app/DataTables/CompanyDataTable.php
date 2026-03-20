<?php

namespace App\DataTables;

use App\Models\Company;
use Yajra\DataTables\Facades\DataTables;

class CompanyDataTable
{
    public function ajax()
    {
        $query = Company::query()->orderBy('id', 'desc');

        $status = request('status');
        if ($status !== null && $status !== '') {
            $query->where('is_active', $status);
        }

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('company_name', function ($company) {
                return '<a href="' . route('companies.show', $company->id) . '">' . e($company->company_name) . '</a>';
            })
            ->editColumn('is_active', function ($company) {
                return $company->is_active ? 'Active' : 'Inactive';
            })
            ->addColumn('action', function ($company) {
                return '
                    <button type="button" class="btn btn-warning btn-sm edit-btn" data-id="' . $company->id . '" data-toggle="modal" data-target="#edit-module-popup">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="' . $company->id . '">
                        <i class="fas fa-trash"></i>
                    </button>
                ';
            })
            ->rawColumns(['company_name', 'action'])
            ->toJson();
    }
}
