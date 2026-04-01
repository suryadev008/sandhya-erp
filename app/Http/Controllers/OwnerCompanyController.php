<?php

namespace App\Http\Controllers;

use App\Models\OwnerCompany;
use App\Http\Requests\StoreOwnerCompanyRequest;
use App\Http\Requests\UpdateOwnerCompanyRequest;
use App\Services\OwnerCompanyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OwnerCompanyController extends Controller
{
    protected $companyService;

    public function __construct(OwnerCompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    /**
     * Display the owner company settings page (view + edit on same page).
     */
    public function index(Request $request)
    {
        $company = OwnerCompany::with(['bankAccounts', 'contacts'])->first();

        if (!$company) {
            return redirect()->route('my-company.create')->with('info', 'Please set up your company details first.');
        }

        return view('owner_companies.index', compact('company'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('owner_companies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOwnerCompanyRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            
            // Handle logo upload
            if ($request->hasFile('logo_path')) {
                $data['logo_path'] = $this->companyService->handleLogoUpload($request);
            }

            $data['created_by'] = auth()->id();

            // Create Company
            $company = OwnerCompany::create($data);

            // Sync Relationships
            $this->companyService->syncBankAccounts($company, $request->input('bank_accounts', []));
            $this->companyService->syncContacts($company, $request->input('contacts', []));

            DB::commit();

            return redirect()->route('my-company.index')->with('success', 'Company created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('OwnerCompany store failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Something went wrong. Please try again.')->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $company = OwnerCompany::with(['bankAccounts', 'contacts', 'createdBy', 'updatedBy'])->findOrFail($id);
        
        return view('owner_companies.show', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $company = OwnerCompany::with(['bankAccounts', 'contacts'])->findOrFail($id);
        
        return view('owner_companies.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOwnerCompanyRequest $request, string $id)
    {
        try {
            DB::beginTransaction();

            $company = OwnerCompany::findOrFail($id);
            $data = $request->validated();

            // Handle logo replace
            if ($request->hasFile('logo_path')) {
                $data['logo_path'] = $this->companyService->handleLogoUpload($request, $company);
            }

            $data['updated_by'] = auth()->id();
            
            // Check is_active toggle from request
            if (!isset($data['is_active'])) {
                $data['is_active'] = false; // Checkbox not submitted means unchecked
            }

            $company->update($data);

            // Sync Relationships
            $this->companyService->syncBankAccounts($company, $request->input('bank_accounts', []));
            $this->companyService->syncContacts($company, $request->input('contacts', []));            

            DB::commit();

            return redirect()->route('my-company.index')->with('success', 'Company details updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('OwnerCompany update failed', ['id' => $id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Something went wrong. Please try again.')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $company = OwnerCompany::findOrFail($id);

            Log::info('OwnerCompany soft-deleted', [
                'company_id'   => $company->id,
                'company_name' => $company->company_name,
                'deleted_by'   => auth()->id(),
                'deleted_at'   => now()->toDateTimeString(),
            ]);

            // Soft deletes for company
            $company->delete();
            
            // Note: Since bank accounts and contacts don't use soft deletes, but cascaden cascadeOnDelete in migrations, 
            // if we soft delete the company, they remain in DB linked to a soft-deleted company.
            // But we optionally might want to deactivate them, or just let the soft delete handle it.
            // For now, soft-deleted scope on Company handles hiding them.

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Company deleted successfully.']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('OwnerCompany destroy failed', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Something went wrong. Please try again.'], 500);
        }
    }

    /**
     * Toggle company status via AJAX
     */
    public function toggleStatus(Request $request, string $id)
    {
        $company = OwnerCompany::findOrFail($id);
        $company->is_active = !$company->is_active;
        $company->save();

        return response()->json([
            'success' => true,
            'is_active' => $company->is_active,
            'message' => 'Status changed successfully.'
        ]);
    }
}
