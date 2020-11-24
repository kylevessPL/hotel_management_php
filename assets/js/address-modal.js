$(document).ready(function() {
    $('.add-address-action').on('click', function(event) {
        event.preventDefault();
        $('.itQKmP, .hZAwTR, .iZQJIb, .muNJM').hide();
        $('label.error').hide();
        $('.error').removeClass('error');
        $('.addressRequest #addressModalLabel').html('Add new address');
        $('.addressRequest #streetName').val('');
        $('.addressRequest #houseNumber').val('');
        $('.addressRequest #zipCode').val('');
        $('.addressRequest #city').val('');
        $('.addressRequest #addressSubmitBtn').attr('value', 'Add');
        $('.addressRequest #addressModal').modal();
    });
    $('.edit-address-action').on('click', function(event) {
        event.preventDefault();
        $('.itQKmP, .hZAwTR, .iZQJIb, .muNJM').hide();
        $('label.error').hide();
        $('.error').removeClass('error');
        $('.addressRequest #addressModalLabel').html('Edit address');
        $('.addressRequest #streetName').val($(this).closest("tr").find("td.address-street-name").text());
        $('.addressRequest #houseNumber').val($(this).closest("tr").find("td.address-house-number").text());
        $('.addressRequest #zipCode').val($(this).closest("tr").find("td.address-zip-code").text());
        $('.addressRequest #city').val($(this).closest("tr").find("td.address-city").text());
        $('.addressRequest #addressSubmitBtn').attr('value', 'Submit changes');
        $('.addressRequest #addressModal').modal();
    });
    $('#addressModal').on('hide.bs.modal', function () {
        setTimeout(function () { $('.itQKmP, .hZAwTR, .iZQJIb, .muNJM').show(); }, 400);
    });
});