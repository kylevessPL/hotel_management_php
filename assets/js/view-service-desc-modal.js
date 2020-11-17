$(document).ready(function() {
    $('.viewServiceBtn').on('click', function(event) {
        event.preventDefault();
        $('.itQKmP, .hZAwTR, .iZQJIb, .muNJM').hide();
        const desc = getDesc($(this).closest("tr").find("th.service-id").text())
        $('#viewServiceDescName').html($(this).closest("tr").find("td.service-name").text());
        $('#viewServiceDescPrice').html('Price: ' + $(this).closest("tr").find("td.service-price").text() + ' PLN');
        $('#viewServiceDescDesc').html('<br>' + desc);
        $('#viewServiceDescModal').modal();
    });
});

$(document).ready(function() {
    $('#viewServiceDescModal').on('hide.bs.modal', function () {
        $('.itQKmP, .hZAwTR, .iZQJIb, .muNJM').show();
    });
});

function getDesc(id) {
    let $desc = '';
    $.ajax({
        url: '../../process/get_service_desc.php',
        type: "GET",
        data: {id: id},
        async: false,
        cache: false,
        success: function (response) {
            $desc = response;
        }
    });
    return $desc;
}
