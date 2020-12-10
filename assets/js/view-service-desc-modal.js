$(document).ready(function() {
    $('.viewServiceBtn').on('click', function(event) {
        event.preventDefault();
        $('.itQKmP, .hZAwTR, .iZQJIb, .muNJM').hide();
        const modal = getServiceDescModal();
        $('.main-container').after(modal);
        $.ajax({
            url: '../../process/get_service_desc.php',
            type: "GET",
            data: { id: $(this).closest("tr").find("th.service-id").text() },
            dataType: 'JSON',
            success: function (response) {
                $('#viewServiceDescDesc').html('<br>' + response[0]['desc']);
            }
        });
        $('#viewServiceDescName').html($(this).closest("tr").find("td.service-name").text());
        $('#viewServiceDescPrice').html('Price: ' + $(this).closest("tr").find("td.service-price").text() + ' PLN');
        let selector = $('#viewServiceDescModal');
        selector.modal();
        selector.on('hide.bs.modal', function () {
            setTimeout(function() {
                $('#viewServiceDescModal').remove();
                $('.itQKmP, .hZAwTR, .iZQJIb, .muNJM').show()
            }, 400);
        });
    });
});

function getServiceDescModal() {
    return `
        <div aria-hidden="true" aria-labelledby="viewServiceDescName" class="modal fade" id="viewServiceDescModal" role="dialog" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewServiceDescName"></h5><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div id="viewServiceDescPrice"></div>
                        <div id="viewServiceDescDesc"></div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal" type="button">Close</button>
                    </div>
                </div>
            </div>
        </div>
    `;
}
