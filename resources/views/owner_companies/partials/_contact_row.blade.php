@php
    $index = $index ?? '__INDEX__';
    $contact = $contact ?? null;
@endphp
<tr class="contact-row">
    <td>
        <input type="text" name="contacts[{{ $index }}][contact_person]" class="form-control" value="{{ old('contacts.'.$index.'.contact_person', $contact->contact_person ?? '') }}" placeholder="Name" required>
    </td>
    <td>
        <input type="text" name="contacts[{{ $index }}][designation]" class="form-control" value="{{ old('contacts.'.$index.'.designation', $contact->designation ?? '') }}" placeholder="Designation">
    </td>
    <td>
        <input type="text" name="contacts[{{ $index }}][phone]" class="form-control" value="{{ old('contacts.'.$index.'.phone', $contact->phone ?? '') }}" placeholder="Phone" required>
    </td>
    <td>
        <input type="text" name="contacts[{{ $index }}][alternate_phone]" class="form-control" value="{{ old('contacts.'.$index.'.alternate_phone', $contact->alternate_phone ?? '') }}" placeholder="Alt Phone">
    </td>
    <td>
        <input type="email" name="contacts[{{ $index }}][email]" class="form-control" value="{{ old('contacts.'.$index.'.email', $contact->email ?? '') }}" placeholder="Email" required>
    </td>
    <td>
        <input type="email" name="contacts[{{ $index }}][support_email]" class="form-control" value="{{ old('contacts.'.$index.'.support_email', $contact->support_email ?? '') }}" placeholder="Support Email">
    </td>
    <td class="text-center align-middle">
        <input type="radio" name="contacts_primary" class="primary-contact-radio" value="{{ $index }}" {{ old('contacts_primary', ($contact && $contact->is_primary) ? $index : '') == $index ? 'checked' : '' }}>
        <input type="hidden" name="contacts[{{ $index }}][is_primary]" class="primary-contact-hidden" value="{{ old('contacts.'.$index.'.is_primary', ($contact && $contact->is_primary) ? 'true' : 'false') }}">
    </td>
    <td class="text-center align-middle">
        <button type="button" class="btn btn-sm btn-danger remove-contact-row"><i class="fas fa-trash"></i></button>
    </td>
</tr>
