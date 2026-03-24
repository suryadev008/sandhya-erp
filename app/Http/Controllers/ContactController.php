<?php

namespace App\Http\Controllers;

use App\DataTables\ContactDataTable;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(ContactDataTable $dataTable)
    {
        if (request()->ajax()) {
            return $dataTable->ajax();
        }
        return view('contacts.index');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'person_name'         => 'required|string|max:255',
                'contact_no'          => 'required|string|max:20',
                'whatsapp_no'         => 'nullable|string|max:20',
                'upi_no'              => 'nullable|string|max:100',
                'account_holder_name' => 'nullable|string|max:255',
                'account_no'          => 'nullable|string|max:50',
                'ifsc_code'           => 'nullable|string|max:20',
                'bank_name'           => 'nullable|string|max:100',
                'branch'              => 'nullable|string|max:100',
                'remarks'             => 'nullable|string',
            ]);

            $validated['is_active'] = $request->has('is_active');
            Contact::create($validated);

            return response()->json(['success' => true, 'message' => 'Contact created successfully.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Validation error', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }

    public function show(string $id)
    {
        $contact = Contact::findOrFail($id);
        return view('contacts.show', compact('contact'));
    }

    public function edit(string $id)
    {
        $contact = Contact::findOrFail($id);
        return response()->json(['success' => true, 'data' => $contact]);
    }

    public function update(Request $request, string $id)
    {
        try {
            $contact   = Contact::findOrFail($id);
            $validated = $request->validate([
                'person_name'         => 'required|string|max:255',
                'contact_no'          => 'required|string|max:20',
                'whatsapp_no'         => 'nullable|string|max:20',
                'upi_no'              => 'nullable|string|max:100',
                'account_holder_name' => 'nullable|string|max:255',
                'account_no'          => 'nullable|string|max:50',
                'ifsc_code'           => 'nullable|string|max:20',
                'bank_name'           => 'nullable|string|max:100',
                'branch'              => 'nullable|string|max:100',
                'remarks'             => 'nullable|string',
            ]);

            $validated['is_active'] = $request->has('is_active');
            $contact->update($validated);

            return response()->json(['success' => true, 'message' => 'Contact updated successfully.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Validation error', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Something went wrong.'], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            Contact::findOrFail($id)->delete();
            return response()->json(['success' => true, 'message' => 'Contact deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Unable to delete contact.'], 500);
        }
    }
}
