<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\DataTables\CompanyDataTable;

class CompanyController extends Controller
{
    public function index(CompanyDataTable $dataTable)
    {
        if (request()->ajax()) {
            return $dataTable->ajax();
        }
        return view('companies.index');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'company_name'   => 'required|string|max:255',
                'plant_name'     => 'nullable|string|max:255',
                'contact_person' => 'nullable|string|max:255',
                'contact_phone'  => 'nullable|string|max:50',
                'address'        => 'nullable|string',
                'remark'         => 'nullable|string',
                'gst_no'         => 'nullable|string|size:15|unique:companies,gst_no|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/',
            ]);

            $validated['is_active'] = $request->has('is_active') ? true : false;
            if (!empty($validated['gst_no'])) {
                $validated['gst_no'] = strtoupper($validated['gst_no']);
            }

            // Merge any verified GST fields sent from frontend
            foreach (['gst_trade_name','gst_legal_name','gst_status','gst_state','gst_pan','gst_registration_date','gst_business_type','gst_verified_at'] as $f) {
                if ($request->filled($f)) $validated[$f] = $request->input($f);
            }

            Company::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Vendor created successfully.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Company operation failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.'
            ], 500);
        }
    }

    public function show(string $id)
    {
        $company = Company::findOrFail($id);
        return view('companies.show', compact('company'));
    }

    public function edit(string $id)
    {
        $company = Company::findOrFail($id);
        return response()->json([
            'success' => true,
            'data'    => $company
        ]);
    }

    public function update(Request $request, string $id)
    {
        try {
            $company = Company::findOrFail($id);
            $validated = $request->validate([
                'company_name'   => 'required|string|max:255',
                'plant_name'     => 'nullable|string|max:255',
                'contact_person' => 'nullable|string|max:255',
                'contact_phone'  => 'nullable|string|max:50',
                'address'        => 'nullable|string',
                'remark'         => 'nullable|string',
                'gst_no'         => 'nullable|string|size:15|unique:companies,gst_no,' . $id . '|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/',
            ]);

            $validated['is_active'] = $request->has('is_active') ? true : false;
            if (!empty($validated['gst_no'])) {
                $validated['gst_no'] = strtoupper($validated['gst_no']);
            }

            // Clear GST details if GST number removed
            if (empty($validated['gst_no'])) {
                foreach (['gst_trade_name','gst_legal_name','gst_status','gst_state','gst_pan','gst_registration_date','gst_business_type','gst_verified_at'] as $f) {
                    $validated[$f] = null;
                }
            } else {
                foreach (['gst_trade_name','gst_legal_name','gst_status','gst_state','gst_pan','gst_registration_date','gst_business_type','gst_verified_at'] as $f) {
                    if ($request->filled($f)) $validated[$f] = $request->input($f);
                }
            }

            $company->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Vendor updated successfully.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Company operation failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.'
            ], 500);
        }
    }

    public function verifyGst(Request $request)
    {
        $gstin = strtoupper(trim($request->gstin ?? ''));

        // 1. Format check
        if (!preg_match('/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/', $gstin)) {
            return response()->json(['valid' => false, 'message' => 'Invalid GSTIN format. Must be 15 characters (e.g. 27AAAAA9999A1Z5).']);
        }

        // 2. Checksum (Modulus-36)
        $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $factor = 2; $sum = 0;
        for ($i = 13; $i >= 0; $i--) {
            $addend = $factor * strpos($chars, $gstin[$i]);
            $factor = ($factor === 2) ? 1 : 2;
            $sum   += intdiv($addend, 36) + ($addend % 36);
        }
        if ($gstin[14] !== $chars[(36 - ($sum % 36)) % 36]) {
            return response()->json(['valid' => false, 'message' => 'Invalid GSTIN — checksum mismatch.']);
        }

        // 3. Derive state & PAN from GSTIN
        $stateCodes = [
            '01'=>'Jammu & Kashmir','02'=>'Himachal Pradesh','03'=>'Punjab','04'=>'Chandigarh',
            '05'=>'Uttarakhand','06'=>'Haryana','07'=>'Delhi','08'=>'Rajasthan',
            '09'=>'Uttar Pradesh','10'=>'Bihar','11'=>'Sikkim','12'=>'Arunachal Pradesh',
            '13'=>'Nagaland','14'=>'Manipur','15'=>'Mizoram','16'=>'Tripura',
            '17'=>'Meghalaya','18'=>'Assam','19'=>'West Bengal','20'=>'Jharkhand',
            '21'=>'Odisha','22'=>'Chhattisgarh','23'=>'Madhya Pradesh','24'=>'Gujarat',
            '25'=>'Daman & Diu','26'=>'Dadra & NH','27'=>'Maharashtra','28'=>'Andhra Pradesh',
            '29'=>'Karnataka','30'=>'Goa','31'=>'Lakshadweep','32'=>'Kerala',
            '33'=>'Tamil Nadu','34'=>'Puducherry','35'=>'Andaman & Nicobar',
            '36'=>'Telangana','37'=>'Andhra Pradesh (New)',
        ];
        $stateCode = substr($gstin, 0, 2);
        $state     = $stateCodes[$stateCode] ?? 'Unknown';
        $pan       = substr($gstin, 2, 10);

        $result = [
            'valid'              => true,
            'gstin'              => $gstin,
            'gst_no'             => $gstin,
            'gst_state'          => $state,
            'gst_pan'            => $pan,
            'gst_trade_name'     => null,
            'gst_legal_name'     => null,
            'gst_status'         => null,
            'gst_registration_date' => null,
            'gst_business_type'  => null,
            'gst_verified_at'    => now()->toDateTimeString(),
            'message'            => 'GSTIN format & checksum verified. State: ' . $state,
            'source'             => 'local',
        ];

        // 4. Call external API if configured in .env
        // GST_API_URL=https://your-gst-api.com/v1/gstin
        // GST_API_KEY=your_api_key
        $apiUrl = config('services.gst.url');
        $apiKey = config('services.gst.key');

        if ($apiUrl && $apiKey) {
            try {
                $response = Http::timeout(6)
                    ->withHeaders([
                        'x-api-key'    => $apiKey,
                        'Authorization' => 'Bearer ' . $apiKey,
                        'Accept'        => 'application/json',
                    ])
                    ->get(rtrim($apiUrl, '/') . '/' . $gstin);

                if ($response->successful()) {
                    $data = $response->json();

                    $result['gst_trade_name']      = $data['tradeNam'] ?? $data['trade_name'] ?? $data['tradeName'] ?? null;
                    $result['gst_legal_name']      = $data['lgnm']     ?? $data['legal_name'] ?? $data['legalName'] ?? null;
                    $result['gst_status']          = $data['sts']      ?? $data['status']     ?? $data['gstStatus'] ?? null;
                    $result['gst_registration_date'] = $data['rgdt']   ?? $data['registration_date'] ?? null;
                    $result['gst_business_type']   = $data['ctb']      ?? $data['business_type'] ?? null;
                    $result['message']             = 'GSTIN verified via API.';
                    $result['source']              = 'api';
                }
            } catch (\Exception $e) {
                // API unavailable — local validation result still returned
            }
        }

        // 5. Save verified details to DB if company_id provided
        if ($request->filled('company_id')) {
            $company = Company::find($request->company_id);
            if ($company) {
                $company->update([
                    'gst_no'               => $result['gst_no'],
                    'gst_trade_name'       => $result['gst_trade_name'],
                    'gst_legal_name'       => $result['gst_legal_name'],
                    'gst_status'           => $result['gst_status'],
                    'gst_state'            => $result['gst_state'],
                    'gst_pan'              => $result['gst_pan'],
                    'gst_registration_date'=> $result['gst_registration_date'],
                    'gst_business_type'    => $result['gst_business_type'],
                    'gst_verified_at'      => now(),
                ]);
            }
        }

        return response()->json($result);
    }

    public function destroy(string $id)
    {
        try {
            $company = Company::findOrFail($id);
            Log::info('Company soft-deleted', [
                'company_id'   => $company->id,
                'company_name' => $company->company_name,
                'deleted_by'   => auth()->id(),
                'deleted_at'   => now()->toDateTimeString(),
            ]);
            $company->delete();
            return response()->json([
                'success' => true,
                'message' => 'Vendor deleted successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Company delete failed', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.'
            ], 500);
        }
    }
}
