$(document).ready(function () {
    buildTable();
});

function buildTable() {
    const table = $('#paymentsTable').DataTable({
        ajax: {
            url: '../../process/get_customer_payments.php',
            type: 'GET',
            dataSrc: ''
        },
        columns: [
            { data: null },
            { data: 'payment-date' },
            { data: 'payment-id' },
            { data: 'payment-form' },
            { data: 'booking-id' }
        ],
        lengthMenu: [[7, 15, 25, 50], [7, 15, 25, 50]],
        pageLength: 7,
        responsive: true,
        language: { emptyTable: "You don't have any payment history yet" },
        columnDefs: [
            {
                targets: '_all',
                className: 'align-middle text-center'
            },
            {
                searchable: false,
                orderable: false,
                targets: 0
            },
            {
                targets: 1,
                render: function (data) {
                    return moment(data).format('DD/MM/YYYY HH:mm:ss');
                }
            }
        ],
        order: [[ 1, 'desc' ]]
    });
    table.on( 'order.dt search.dt', function () {
        table.column(0, { search: 'applied', order: 'applied' }).nodes().each( function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();
    new $.fn.dataTable.FixedHeader(table);
}
