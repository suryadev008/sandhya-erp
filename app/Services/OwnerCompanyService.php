<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use App\Models\OwnerCompanyBankAccount;
use App\Models\OwnerCompanyContact;

class OwnerCompanyService
{
    /**
     * Handle Logo Upload
     */
    public function handleLogoUpload($request, $company = null)
    {
        if ($request->hasFile('logo_path')) {
            // Upload new file first
            $newPath = $request->file('logo_path')->store('owner_companies/logos', 'public');

            if ($newPath === false) {
                throw new \RuntimeException('Failed to upload company logo. Please try again.');
            }

            // Delete old logo only after new upload succeeds
            if ($company && $company->logo_path) {
                Storage::disk('public')->delete($company->logo_path);
            }

            return $newPath;
        }

        return $company ? $company->logo_path : null;
    }

    /**
     * Sync Bank Accounts
     */
    public function syncBankAccounts($company, $bankAccounts)
    {
        // For simplicity in updating, we'll delete the existing and recreate
        // Since id's might change or rows might be deleted, this is a clean approach.
        $company->bankAccounts()->delete();

        $bankAccounts = $this->ensureSinglePrimary($bankAccounts);

        foreach ($bankAccounts as $bankData) {
            $company->bankAccounts()->create([
                'bank_name' => $bankData['bank_name'],
                'account_number' => $bankData['account_number'],
                'ifsc_code' => $bankData['ifsc_code'],
                'account_type' => $bankData['account_type'],
                'branch_name' => $bankData['branch_name'] ?? null,
                'swift_code' => $bankData['swift_code'] ?? null,
                'is_primary' => $bankData['is_primary'] ?? false,
            ]);
        }
    }

    /**
     * Sync Contacts
     */
    public function syncContacts($company, $contacts)
    {
        $company->contacts()->delete();

        $contacts = $this->ensureSinglePrimary($contacts);

        foreach ($contacts as $contactData) {
            $company->contacts()->create([
                'contact_person' => $contactData['contact_person'],
                'designation' => $contactData['designation'] ?? null,
                'phone' => $contactData['phone'],
                'alternate_phone' => $contactData['alternate_phone'] ?? null,
                'email' => $contactData['email'],
                'support_email' => $contactData['support_email'] ?? null,
                'is_primary' => $contactData['is_primary'] ?? false,
            ]);
        }
    }

    /**
     * Ensure only one item in collection/array is set as primary
     */
    public function ensureSinglePrimary(array $items)
    {
        $primarySet = false;

        foreach ($items as &$item) {
            $isPrimary = isset($item['is_primary']) && filter_var($item['is_primary'], FILTER_VALIDATE_BOOLEAN);
            
            if ($isPrimary) {
                if ($primarySet) {
                    $item['is_primary'] = false; // Only allow one
                } else {
                    $item['is_primary'] = true;
                    $primarySet = true;
                }
            } else {
                $item['is_primary'] = false;
            }
        }

        // If no primary was set, default the first one to primary
        if (!$primarySet && count($items) > 0) {
            $items[array_key_first($items)]['is_primary'] = true;
        }

        return $items;
    }
}
