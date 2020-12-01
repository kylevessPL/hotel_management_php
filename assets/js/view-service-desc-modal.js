$(document).ready(function() {
    $('.viewServiceBtn').on('click', function(event) {
        event.preventDefault();
        $('.itQKmP, .hZAwTR, .iZQJIb, .muNJM').hide();
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
        $('#viewServiceDescModal').modal();
    });
    $('#viewServiceDescModal').on('hide.bs.modal', function () {
        setTimeout(() => $('.itQKmP, .hZAwTR, .iZQJIb, .muNJM').show(), 400);
    });
});
