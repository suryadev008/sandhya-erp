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

        $designationId = request('designation_id');
        if ($designationId !== null && $designationId !== '') {
            $query->where('designation_id', $designationId);
        }

        return DataTables::eloquent($query->with('designation'))
            ->addIndexColumn()
            ->editColumn('company_name', fn ($c) => '<a href="' . route('companies.show', $c->id) . '">' . e($c->company_name) . '</a>')
            ->addColumn('designation_name', fn ($c) => $c->designation ? e($c->designation->name) : '—')
            ->editColumn('is_active', fn ($c) => $c->is_active
                ? '<span class="badge badge-success">Active</span>'
                : '<span class="badge badge-secondary">Inactive</span>')
            ->addColumn('action', function ($c) {
                $id  = $c->id;
                $html = '';
                if (auth()->user()->can('edit companies')) {
                    $html .= "<button type=\"button\" class=\"btn btn-warning btn-sm edit-btn\" data-id=\"{$id}\" data-toggle=\"modal\" data-target=\"#edit-module-popup\" title=\"Edit\"><i class=\"fas fa-edit\"></i></button> ";
                }
                if (auth()->user()->can('delete companies')) {
                    $html .= "<button class=\"btn btn-sm btn-danger delete-btn\" data-id=\"{$id}\" title=\"Delete\"><i class=\"fas fa-trash\"></i></button>";
                }
                return $html ?: '—';
            })
            ->rawColumns(['company_name', 'designation_name', 'is_active', 'action'])
            ->toJson();
    }
}
