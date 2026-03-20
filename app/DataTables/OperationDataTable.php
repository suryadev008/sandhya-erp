<?php

namespace App\DataTables;

use App\Models\Operation;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Facades\DataTables;

class OperationDataTable
{
    public function ajax()
    {
        $query = Operation::query()->orderBy('id', 'desc');

        $status = request('status');
        if ($status !== null && $status !== '') {
            $query->where('is_active', $status);
        }

        $applicable = request('applicable_for');
        if ($applicable !== null && $applicable !== '') {
            $query->where('applicable_for', $applicable);
        }

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('is_active', function ($operation) {
                return $operation->is_active ? 'Active' : 'Inactive';
            })
            ->addColumn('action', function ($operation) {
                return '
                    <button type="button" class="btn btn-warning btn-sm edit-btn" data-id="' . $operation->id . '" data-toggle="modal" data-target="#edit-module-popup">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="' . $operation->id . '">
                        <i class="fas fa-trash"></i>
                    </button>
                ';
            })
            ->rawColumns(['action'])
            ->toJson();
    }
}
