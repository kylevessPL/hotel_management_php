$(document).ready(function () {
    $('#startDate, #endDate').datepicker({
        clearBtn: true,
        format: "dd/mm/yyyy",
        orientation: 'right bottom',
        weekStart: 1
    });
    $('#services').multiselect({
        includeSelectAllOption: true,
        buttonWidth: '100%',
        numberDisplayed: 2
    });
    $('#bedAmount, #startDate, #endDate').on('change', function () {
        fetchRooms();
    });
});

function fetchRooms() {
    const startDate = $('#startDate')
    const endDate = $('#endDate')
    const bedAmount = $('#bedAmount')
    if (startDate.val() !== '' && startDate.valid() === true && endDate.val() !== '' && endDate.valid() === true && bedAmount.val() !== '' && bedAmount.valid() === true) {
        $.ajax({
            url: '../../process/get_available_rooms.php',
            type: "GET",
            data: {
                'start-date': startDate.val(),
                'end-date': endDate.val(),
                'bed-amount': bedAmount.val()
            },
            dataType: 'JSON',
            success: function (response) {
                let list = '<option value="">None selected</option>';
                $.each(response, function(key, val) {
                    list += '<option value="'+val['id']+'">Room number: '+val['room-number']+', Price: '+val['standard-price']+'</option>';
                });
                $('#room').html(list);
                if (typeof setChoice === 'function') {
                    setChoice();
                }
            }
        });
    }
    else
    {
        $('#room').html('<option value="">Choose dates and bed amount first</option>');
    }
}
