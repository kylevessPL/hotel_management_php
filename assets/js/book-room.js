$(document).ready(function () {
    $('#startDate, #endDate').datepicker({
        clearBtn: true,
        format: "dd/mm/yyyy",
        orientation: 'right bottom',
        weekStart: 1
    });
    $('#services').selectpicker({
        noneSelectedText: 'None selected',
        actionsBox: true,
        style: '',
        styleBase: 'form-control',
        tickIcon: 'mt-1 las la-check'
    });
    $('#myAddress, #bedAmount, #room').selectpicker({
        style: '',
        styleBase: 'form-control',
        size: 5
    });
    $.fn.selectpicker.Constructor.BootstrapVersion = '4';
    $('#bedAmount, #startDate, #endDate').on('change', function () {
        const startDate = $('#startDate');
        const endDate = $('#endDate');
        const bedAmount = $('#bedAmount');
        if (startDate.val() !== '' && startDate.valid() === true && endDate.val() !== '' && endDate.valid() === true && bedAmount.val() !== '' && bedAmount.valid() === true) {
            fetchRooms(startDate, endDate, bedAmount);
        } else {
            $('#room').html('<option value="">Choose dates and bed amount first</option>');
            $('#booking-form-people').remove();
        }
    });
});

function fetchRooms(startDate, endDate, bedAmount) {
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
                list += '<option value="'+val['id']+'">Room number: '+val['room-number']+', Price: '+val['standard-price']+' PLN</option>';
            });
            const room = $('#room');
            room.html(list);
            room.selectpicker('refresh');
            if (typeof setChoice === 'function') {
                setChoice();
            }
            showPeopleOption(bedAmount);
        }
    });
}

function showPeopleOption(bedAmount) {
    const selector = "#booking-form-people";
    let peopleSection = $(selector);
    peopleSection.remove();
    if (bedAmount.val() > 1) {
        $('#booking-form-main').after('<div id="booking-form-people" class="pt-2"></div>');
        peopleSection = $(selector);
        peopleSection.html('<button class="btn btn-success text-right add-person-action" type="button"><i class="las la-plus-circle la-lg mr-2"></i>Add person</button>');
    }
}
