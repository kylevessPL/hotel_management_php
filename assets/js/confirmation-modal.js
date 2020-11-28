$(document).ready(function() {
    $('.logout-action').on('click', function(event) {
        event.preventDefault();
        $('.itQKmP, .hZAwTR, .iZQJIb, .muNJM').hide();
        const href = $(this).attr('href');
        $('#confirmationModal #confirmationModalTitle').html('Sign out');
        $('#confirmationModal #modalMsg').html('Are you sure you want to sign out?');
        $('#confirmationModal #abortBtn').html('Stay');
        $('#confirmationModal #actionRef').html('Log out').attr('href', href);
        $('#confirmationModal').modal();
    });
    $('.delete-address-action').on('click', function(event) {
        event.preventDefault();
        $('.itQKmP, .hZAwTR, .iZQJIb, .muNJM').hide();
        const href = $(this).attr('href');
        $('#confirmationModal #confirmationModalTitle').html('Delete address');
        $('#confirmationModal #modalMsg').html('Are you sure you want to delete this address?');
        $('#confirmationModal #abortBtn').html('Close');
        $('#confirmationModal #actionRef').html('Delete').attr('href', href);
        $('#confirmationModal').modal();
    });
    $('#confirmationModal').on('hide.bs.modal', function () {
        setTimeout(() => $('.itQKmP, .hZAwTR, .iZQJIb, .muNJM').show(), 400);
    });
});
