<?php

namespace App\DataTables;

use App\Models\Contact;
use Yajra\DataTables\Facades\DataTables;

class ContactDataTable
{
    public function ajax()
    {
        $query = Contact::query()->orderBy('id', 'desc');

return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('is_active', fn($c) => $c->is_active ? 'Active' : 'Inactive')
            ->editColumn('person_name', fn($c) => '<a href="' . route('contacts.show', $c->id) . '">' . e($c->person_name) . '</a>')
            ->addColumn('action', function ($contact) {
                return '
                    <button type="button" class="btn btn-warning btn-sm edit-btn"
                        data-id="' . $contact->id . '" data-toggle="modal" data-target="#edit-module-popup">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="' . $contact->id . '">
                        <i class="fas fa-trash"></i>
                    </button>
                ';
            })
            ->rawColumns(['person_name', 'action'])
            ->toJson();
    }
}
