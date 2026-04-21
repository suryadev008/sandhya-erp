<?php

namespace App\DataTables;

use App\Models\Operation;
use Yajra\DataTables\Facades\DataTables;

class OperationDataTable
{
    public function ajax()
    {
        $query = Operation::with(['company', 'currentPrice'])->orderBy('id', 'desc');

        $status = request('status');
        if ($status !== null && $status !== '') {
            $query->where('is_active', $status);
        }

        $applicable = request('applicable_for');
        if ($applicable !== null && $applicable !== '') {
            $query->where('applicable_for', $applicable);
        }

        $companyId = request('company_id');
        if ($companyId !== null && $companyId !== '') {
            $query->where('company_id', $companyId);
        }

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->addColumn('company_name', fn($op) => $op->company ? $op->company->company_name : '—')
            ->addColumn('current_price', function ($op) {
                return $op->currentPrice
                    ? '₹ ' . number_format($op->currentPrice->price, 2)
                    : '<span class="text-warning">Not set</span>';
            })
            ->editColumn('operation_name', function ($op) {
                return '<a href="' . route('operations.show', $op->id) . '">' . e($op->operation_name) . '</a>';
            })
            ->editColumn('is_active', fn($op) => $op->is_active ? 'Active' : 'Inactive')
            ->addColumn('action', function ($op) {
                $html = '<a href="' . route('operations.show', $op->id) . '" class="btn btn-info btn-sm" title="View"><i class="fas fa-eye"></i></a> ';
                if (auth()->user()->can('edit operations')) {
                    $html .= '<button type="button" class="btn btn-warning btn-sm edit-btn" data-id="' . $op->id . '" data-toggle="modal" data-target="#edit-module-popup" title="Edit"><i class="fas fa-edit"></i></button> ';
                }
                if (auth()->user()->can('delete operations')) {
                    $html .= '<button class="btn btn-sm btn-danger delete-btn" data-id="' . $op->id . '" title="Delete"><i class="fas fa-trash"></i></button>';
                }
                return $html;
            })
            ->rawColumns(['operation_name', 'current_price', 'action'])
            ->toJson();
    }
}
