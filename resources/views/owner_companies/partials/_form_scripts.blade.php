<script>
$(document).ready(function() {
    
    // Capitalize PAN and IFSC
    $(document).on('input', '#pan_number, .ifsc-input, #gstin', function() {
        var pos = this.selectionStart;
        $(this).val($(this).val().toUpperCase());
        this.setSelectionRange(pos, pos);
    });

    // Image preview
    $('#logo_path').change(function() {
        const file = this.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function(event) {
                $('#logo-preview').attr('src', event.target.result).show();
            }
            reader.readAsDataURL(file);
        } else {
            $('#logo-preview').hide();
        }
    });

    // Dynamic Banks
    let bankIndex = {{ isset($company) && $company->bankAccounts ? $company->bankAccounts->count() : (old('bank_accounts') ? count(old('bank_accounts')) : 1) }};
    
    $('#add-bank-btn').click(function() {
        let template = $('#bank-row-template').html().replace(/__INDEX__/g, bankIndex);
        $('#banks-tbl-body').append(template);
        bankIndex++;
    });

    $(document).on('click', '.remove-bank-row', function() {
        if ($('#banks-tbl-body tr.bank-row').length > 1) {
            $(this).closest('tr').remove();
        } else {
            alert('At least one bank account is required.');
        }
    });

    $(document).on('change', '.primary-bank-radio', function() {
        $('.primary-bank-hidden').val('false');
        $(this).closest('td').find('.primary-bank-hidden').val('true');
    });

    // Dynamic Contacts
    let contactIndex = {{ isset($company) && $company->contacts ? $company->contacts->count() : (old('contacts') ? count(old('contacts')) : 1) }};
    
    $('#add-contact-btn').click(function() {
        let template = $('#contact-row-template').html().replace(/__INDEX__/g, contactIndex);
        $('#contacts-tbl-body').append(template);
        contactIndex++;
    });

    $(document).on('click', '.remove-contact-row', function() {
        if ($('#contacts-tbl-body tr.contact-row').length > 1) {
            $(this).closest('tr').remove();
        } else {
            alert('At least one contact is required.');
        }
    });

    $(document).on('change', '.primary-contact-radio', function() {
        $('.primary-contact-hidden').val('false');
        $(this).closest('td').find('.primary-contact-hidden').val('true');
    });

    // Initialize radios if newly added without primary set
    $('form').submit(function(e) {
        if ($('.primary-bank-radio:checked').length === 0) {
            $('.primary-bank-radio').first().prop('checked', true).trigger('change');
        }
        if ($('.primary-contact-radio:checked').length === 0) {
            $('.primary-contact-radio').first().prop('checked', true).trigger('change');
        }
    });
});
</script>
