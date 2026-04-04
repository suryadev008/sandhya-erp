<?php

namespace App\Http\Controllers;

use App\DataTables\ContactDataTable;
use App\Models\Contact;
use App\Models\ContactCategory;
use App\Models\ContactPhone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    public function index(ContactDataTable $dataTable)
    {
        if (request()->ajax()) {
            return $dataTable->ajax();
        }
        $categories = ContactCategory::active()->orderBy('name')->get(['id', 'name']);
        return view('contacts.index', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'person_name'         => 'required|string|max:255',
                'area'                => 'nullable|string|max:150',
                'contact_category_id' => 'nullable|exists:contact_categories,id',
                'upi_no'              => 'nullable|string|max:100',
                'account_holder_name' => 'nullable|string|max:255',
                'account_no'          => 'nullable|string|max:50',
                'ifsc_code'           => 'nullable|string|max:20',
                'bank_name'           => 'nullable|string|max:100',
                'branch'              => 'nullable|string|max:100',
                'remarks'             => 'nullable|string',
                'phones'              => 'required|array|min:1',
                'phones.*.number'     => 'required|string|max:30',
                'phones.*.label'      => 'required|string|max:50',
            ]);

            DB::transaction(function () use ($request, $validated) {
                $validated['is_active'] = $request->has('is_active');
                $contact = Contact::create($validated);

                foreach ($request->phones as $i => $phone) {
                    if (empty(trim($phone['number']))) continue;
                    ContactPhone::create([
                        'contact_id'   => $contact->id,
                        'phone_number' => trim($phone['number']),
                        'label'        => $phone['label'] ?? 'Mobile',
                        'is_primary'   => $i === 0,
                    ]);
                }
            });

            return response()->json(['success' => true, 'message' => 'Contact created successfully.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Validation error', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }

    public function show(string $id)
    {
        $contact = Contact::with(['category', 'phones'])->findOrFail($id);
        return view('contacts.show', compact('contact'));
    }

    public function edit(string $id)
    {
        $contact = Contact::with('phones')->findOrFail($id);
        return response()->json(['success' => true, 'data' => $contact]);
    }

    public function update(Request $request, string $id)
    {
        try {
            $contact = Contact::findOrFail($id);

            $validated = $request->validate([
                'person_name'         => 'required|string|max:255',
                'area'                => 'nullable|string|max:150',
                'contact_category_id' => 'nullable|exists:contact_categories,id',
                'upi_no'              => 'nullable|string|max:100',
                'account_holder_name' => 'nullable|string|max:255',
                'account_no'          => 'nullable|string|max:50',
                'ifsc_code'           => 'nullable|string|max:20',
                'bank_name'           => 'nullable|string|max:100',
                'branch'              => 'nullable|string|max:100',
                'remarks'             => 'nullable|string',
                'phones'              => 'required|array|min:1',
                'phones.*.number'     => 'required|string|max:30',
                'phones.*.label'      => 'required|string|max:50',
            ]);

            DB::transaction(function () use ($request, $contact, $validated) {
                $validated['is_active'] = $request->has('is_active');
                $contact->update($validated);

                // Sync phone numbers: delete all and re-insert
                $contact->phones()->delete();
                foreach ($request->phones as $i => $phone) {
                    if (empty(trim($phone['number']))) continue;
                    ContactPhone::create([
                        'contact_id'   => $contact->id,
                        'phone_number' => trim($phone['number']),
                        'label'        => $phone['label'] ?? 'Mobile',
                        'is_primary'   => $i === 0,
                    ]);
                }
            });

            return response()->json(['success' => true, 'message' => 'Contact updated successfully.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Validation error', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            Contact::findOrFail($id)->delete(); // phones cascade-deleted via FK
            return response()->json(['success' => true, 'message' => 'Contact deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Unable to delete contact.'], 500);
        }
    }
}
