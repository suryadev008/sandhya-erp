<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOwnerCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // $this->route('my_company') could be an ID or Model instance depending on Route param name
        // The resource route is /my-company, so the param is 'my_company'
        $id = is_object($this->route('my_company')) ? $this->route('my_company')->id : $this->route('my_company');

        return [
            // Company Info
            'company_name' => 'required|string|max:255|unique:owner_companies,company_name,' . $id,
            'pan_number' => ['required', 'regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/', 'unique:owner_companies,pan_number,' . $id],
            'gstin' => ['required', 'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/', 'unique:owner_companies,gstin,' . $id],
            'incorporation_date' => 'required|date|before:today',
            'financial_year_start' => 'required|in:april,january',
            'invoice_prefix' => 'required|string|max:20',
            
            'reg_address_line1' => 'required|string',
            'reg_city' => 'required|string',
            'reg_state' => 'required|string',
            'reg_pincode' => 'required|digits:6',
            'reg_country' => 'nullable|string',

            // Optional Company Info
            'company_code' => 'nullable|string|max:50',
            'company_type' => 'nullable|in:pvt_ltd,llp,partnership,sole_prop,public_ltd',
            'base_currency' => 'nullable|string',
            'timezone' => 'nullable|string',
            'tax_regime' => 'nullable|in:old,new',
            'logo_path' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'website' => 'nullable|url',
            'cin_number' => 'nullable|string|max:40',
            'tan_number' => 'nullable|string|max:40',
            'msme_reg_no'=> 'nullable|string|max:40',
            'roc'        => 'nullable|string|max:100',
            'industry_type'      => 'nullable|string',
            'corp_address_line1' => 'nullable|string',
            'corp_address_line2' => 'nullable|string',
            'corp_city'          => 'nullable|string',
            'corp_state'         => 'nullable|string',
            'corp_pincode'       => 'nullable|digits:6',

            'is_multi_branch'    => 'nullable|boolean',
            'authorized_capital' => 'nullable|numeric|min:0',
            'paid_up_capital'    => 'nullable|numeric|min:0',
            'num_directors'      => 'nullable|integer|min:1',
            'auditor_name'       => 'nullable|string|max:255',
            'auditor_firm'       => 'nullable|string|max:255',
            'cs_name'            => 'nullable|string|max:255',

            // Banks
            'bank_accounts' => 'required|array|min:1',
            'bank_accounts.*.bank_name' => 'required|string',
            'bank_accounts.*.account_holder_name' => 'required|string|max:255',
            'bank_accounts.*.account_number' => 'required|string',
            'bank_accounts.*.ifsc_code' => ['required', 'regex:/^[A-Z]{4}0[A-Z0-9]{6}$/'],
            'bank_accounts.*.account_type' => 'required|in:current,savings',
            'bank_accounts.*.branch_name' => 'nullable|string',
            'bank_accounts.*.swift_code' => 'nullable|string',
            'bank_accounts.*.is_primary' => 'nullable|boolean',

            // Contacts
            'contacts' => 'required|array|min:1',
            'contacts.*.contact_person' => 'required|string',
            'contacts.*.designation' => 'nullable|string',
            'contacts.*.phone' => 'required|digits_between:10,12',
            'contacts.*.alternate_phone' => 'nullable|digits_between:10,12',
            'contacts.*.email' => 'required|email',
            'contacts.*.support_email' => 'nullable|email',
            'contacts.*.is_primary' => 'nullable|boolean',
        ];
    }
}
