$(document).ready(function() {
    $('.logout-action').on('click', function(event) {
        event.preventDefault();
        $('.itQKmP, .hZAwTR, .iZQJIb, .muNJM').hide();
        const href = $(this).attr('href');
        const modal = getConfirmationModal();
        $('.main-container').after(modal);
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
        const modal = getConfirmationModal();
        $('.main-container').after(modal);
        $('#confirmationModal #confirmationModalTitle').html('Delete address');
        $('#confirmationModal #modalMsg').html('Are you sure you want to delete this address?');
        $('#confirmationModal #abortBtn').html('Close');
        $('#confirmationModal #actionRef').html('Delete').attr('href', href);
        $('#confirmationModal').modal();
    });
    $('body').on('hide.bs.modal', '#confirmationModal', function() {
        setTimeout(function() {
            $('#confirmationModal').remove();
            $('.itQKmP, .hZAwTR, .iZQJIb, .muNJM').show()
        }, 400);
    });
});

function getConfirmationModal() {
    return `
        <div aria-hidden="true" aria-labelledby="confirmationModalTitle" class="modal fade" id="confirmationModal" role="dialog" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmationModalTitle"></h5><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <p class="alert alert-danger" id="modalMsg"></p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal" type="button" id="abortBtn"></button>
                        <a class="btn btn-danger" href="" id="actionRef"></a>
                    </div>
                </div>
            </div>
        </div>
    `;
}
