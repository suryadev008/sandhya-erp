@php
    $index = $index ?? '__INDEX__';
    $bank = $bank ?? null;
@endphp
<tr class="bank-row">
    <td>
        <input type="text" name="bank_accounts[{{ $index }}][bank_name]" class="form-control" value="{{ old('bank_accounts.'.$index.'.bank_name', $bank->bank_name ?? '') }}" placeholder="Bank Name">
    </td>
    <td>
        <input type="text" name="bank_accounts[{{ $index }}][account_holder_name]" class="form-control" value="{{ old('bank_accounts.'.$index.'.account_holder_name', $bank->account_holder_name ?? '') }}" placeholder="Account Holder Name" required>
    </td>
    <td>
        <input type="text" name="bank_accounts[{{ $index }}][account_number]" class="form-control" value="{{ old('bank_accounts.'.$index.'.account_number', $bank->account_number ?? '') }}" placeholder="Account Number">
    </td>
    <td>
        <input type="text" name="bank_accounts[{{ $index }}][ifsc_code]" class="form-control ifsc-input text-uppercase" value="{{ old('bank_accounts.'.$index.'.ifsc_code', $bank->ifsc_code ?? '') }}" placeholder="IFSC Code">
    </td>
    <td>
        <select name="bank_accounts[{{ $index }}][account_type]" class="form-control">
            <option value="current" {{ old('bank_accounts.'.$index.'.account_type', $bank->account_type ?? '') == 'current' ? 'selected' : '' }}>Current</option>
            <option value="savings" {{ old('bank_accounts.'.$index.'.account_type', $bank->account_type ?? '') == 'savings' ? 'selected' : '' }}>Savings</option>
        </select>
    </td>
    <td>
        <input type="text" name="bank_accounts[{{ $index }}][branch_name]" class="form-control" value="{{ old('bank_accounts.'.$index.'.branch_name', $bank->branch_name ?? '') }}" placeholder="Branch">
    </td>
    <td>
        <input type="text" name="bank_accounts[{{ $index }}][swift_code]" class="form-control" value="{{ old('bank_accounts.'.$index.'.swift_code', $bank->swift_code ?? '') }}" placeholder="SWIFT">
    </td>
    <td class="text-center align-middle">
        <input type="radio" name="bank_accounts_primary" class="primary-bank-radio" value="{{ $index }}" {{ old('bank_accounts_primary', ($bank && $bank->is_primary) ? $index : '') == $index ? 'checked' : '' }}>
        <input type="hidden" name="bank_accounts[{{ $index }}][is_primary]" class="primary-bank-hidden" value="{{ old('bank_accounts.'.$index.'.is_primary', ($bank && $bank->is_primary) ? 'true' : 'false') }}">
    </td>
    <td class="text-center align-middle">
        <button type="button" class="btn btn-sm btn-danger remove-bank-row"><i class="fas fa-trash"></i></button>
    </td>
</tr>
