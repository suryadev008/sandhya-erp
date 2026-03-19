<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\DataTables\MachineDataTable;
use App\Models\Machine;


class MachineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     return view('machines.index');
    // }


    public function index(MachineDataTable $dataTable)
    {
        // AJAX request → JSON return करो
        if (request()->ajax()) {
            return $dataTable->ajax();
        }

        // Normal request → View return करो
        return view('machines.index');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
    //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
    //
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(string $id)
    // {
    // //
    // }

    public function destroy(string $id)
    {
        Machine::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
