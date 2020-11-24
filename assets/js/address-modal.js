$(document).ready(function() {
    $('.add-address-action').on('click', function(event) {
        event.preventDefault();
        $('.itQKmP, .hZAwTR, .iZQJIb, .muNJM').hide();
        $('label.error').hide();
        $('.error').removeClass('error');
        $('.addressForm #addressModalLabel').html('Add new address');
        $('.addressForm #streetName').val('');
        $('.addressForm #houseNumber').val('');
        $('.addressForm #zipCode').val('');
        $('.addressForm #city').val('');
        $('.addressForm #addressSubmitBtn').attr('value', 'Add');
        $('.addressForm #addressModal').modal();
    });
    $('.edit-address-action').on('click', function(event) {
        event.preventDefault();
        $('.itQKmP, .hZAwTR, .iZQJIb, .muNJM').hide();
        $('label.error').hide();
        $('.error').removeClass('error');
        $('.addressForm #addressModalLabel').html('Edit address');
        $('.addressForm #streetName').val($(this).closest("tr").find("td.address-street-name").text());
        $('.addressForm #houseNumber').val($(this).closest("tr").find("td.address-house-number").text());
        $('.addressForm #zipCode').val($(this).closest("tr").find("td.address-zip-code").text());
        $('.addressForm #city').val($(this).closest("tr").find("td.address-city").text());
        $('.addressForm #addressSubmitBtn').attr('value', 'Submit changes');
        $('.addressForm #addressModal').modal();
    });
    $('#addressModal').on('hide.bs.modal', function () {
        setTimeout(function () { $('.itQKmP, .hZAwTR, .iZQJIb, .muNJM').show(); }, 400);
    });
});