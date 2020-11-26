$(document).ready(function() {
    let desc = '';
    $.ajax({
        url: '../../process/get_services_desc.php',
        type: "GET",
        dataType: 'JSON',
        success: function (response) {
            desc = response;
        }
    });
    $('.viewServiceBtn').on('click', function(event) {
        event.preventDefault();
        $('.itQKmP, .hZAwTR, .iZQJIb, .muNJM').hide();
        $('#viewServiceDescName').html($(this).closest("tr").find("td.service-name").text());
        $('#viewServiceDescPrice').html('Price: ' + $(this).closest("tr").find("td.service-price").text() + ' PLN');
        $('#viewServiceDescDesc').html('<br>' + desc[$(this).closest("tr").find("th.service-id").text() - 1]['desc']);
        $('#viewServiceDescModal').modal();
    });
    $('#viewServiceDescModal').on('hide.bs.modal', function () {
        setTimeout(function () { $('.itQKmP, .hZAwTR, .iZQJIb, .muNJM').show(); }, 400);
    });
});
