<?php

namespace App\DataTables;

use App\Models\Contact;
use Yajra\DataTables\Facades\DataTables;

class ContactDataTable
{
    public function ajax()
    {
        $categoryId = request('category_id');
        $area       = request('area_filter');

        $query = Contact::with(['category', 'phones'])
            ->when($categoryId, fn($q) => $q->where('contact_category_id', $categoryId))
            ->when($area,       fn($q) => $q->where('area', 'like', '%' . $area . '%'))
            ->orderBy('id', 'desc');

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('person_name', fn($c) =>
                '<a href="' . route('contacts.show', $c->id) . '" class="font-weight-bold">' . e($c->person_name) . '</a>'
            )
            ->addColumn('category', fn($c) =>
                $c->category
                    ? '<span class="badge badge-info">' . e($c->category->name) . '</span>'
                    : '<span class="text-muted">—</span>'
            )
            ->addColumn('area', fn($c) =>
                $c->area
                    ? '<i class="fas fa-map-marker-alt text-muted mr-1"></i>' . e($c->area)
                    : '<span class="text-muted">—</span>'
            )
            ->addColumn('phones', function ($c) {
                if ($c->phones->isEmpty()) return '<span class="text-muted">—</span>';
                $html = '';
                foreach ($c->phones as $p) {
                    $icon = match($p->label) {
                        'WhatsApp' => '<i class="fab fa-whatsapp text-success mr-1"></i>',
                        'Office'   => '<i class="fas fa-phone-alt text-primary mr-1"></i>',
                        default    => '<i class="fas fa-mobile-alt text-secondary mr-1"></i>',
                    };
                    $html .= '<span class="d-block text-nowrap">' . $icon . e($p->phone_number)
                        . ' <small class="text-muted">(' . e($p->label) . ')</small></span>';
                }
                return $html;
            })
            ->addColumn('action', function ($c) {
                return '
                    <button type="button" class="btn btn-warning btn-sm edit-btn"
                        data-id="' . $c->id . '" data-toggle="modal" data-target="#edit-module-popup" title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn ml-1" data-id="' . $c->id . '" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>';
            })
            ->rawColumns(['person_name', 'category', 'area', 'phones', 'action'])
            ->toJson();
    }
}
