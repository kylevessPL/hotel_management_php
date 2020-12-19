$(document).ready(function () {
    $.ajax({
        url: '../../process/get_payment_forms.php',
        type: "GET",
        dataType: 'JSON',
        success: function (response) {
            let paymentFormOptions = [];
            $.each(response, function (key, val) {
                let iconType = getIconType(val['name']);
                paymentFormOptions.push({
                    "label": '<span title="'+val['name']+'"><i class="'+iconType+' la-lg mr-2" style="color: #007bff;"></i>'+val['name']+'</span>',
                    "value": function(rowData, rowIdx) {
                        return rowData['payment-form'] === val['name'];
                    }
                });
            });
            buildTable(paymentFormOptions);
        }
    });
});

function getIconType(paymentForm) {
    let icon = '';
    switch (paymentForm) {
        case 'Credit Card':
            icon = 'las la-credit-card';
            break
        case 'PayPal':
            icon = 'lab la-paypal';
            break;
        case 'Bitcoin':
            icon = 'lab la-bitcoin';
            break;
        case 'Bank transfer':
            icon = 'las la-university';
            break;
        default:
            icon = 'las la-money-check-alt';
            break;
    }
    return icon;
}

function buildTable(paymentFormOptions) {
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
        searchPane: true,
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
                searchPanes: {
                    show: true,
                    options: [
                        {
                            label: 'Today',
                            value: function(rowData, rowIdx) {
                                return moment(rowData['payment-date'], 'YYYY/MM/DD HH:mm:ss').isSame(moment(), 'day');
                            }
                        },
                        {
                            label: 'This week',
                            value: function(rowData, rowIdx) {
                                return moment(rowData['payment-date'], 'YYYY/MM/DD HH:mm:ss').isSame(moment(), 'week');
                            }
                        },
                        {
                            label: 'This month',
                            value: function(rowData, rowIdx) {
                                return moment(rowData['payment-date'], 'YYYY/MM/DD HH:mm:ss').isSame(moment(), 'month');
                            }
                        },
                        {
                            label: 'This year',
                            value: function(rowData, rowIdx) {
                                return moment(rowData['payment-date'], 'YYYY/MM/DD HH:mm:ss').isSame(moment(), 'year');
                            }
                        }
                    ],
                    orderable: false,
                    dtOpts: {
                        ordering: false,
                        searching: false
                    }
                },
                render: function (data) {
                    return moment(data).format('DD/MM/YYYY HH:mm:ss');
                }
            },
            {
                targets: 3,
                orderable: false,
                searchPanes: {
                    show: true,
                    options: paymentFormOptions,
                    dtOpts: { ordering: false }
                },
                render: function (data) {
                    const iconType = getIconType(data);
                    return '<i class="'+iconType+' la-lg mr-2" style="color: #007bff;"></i>' + data;
                }
            }
        ],
        order: [[ 1, 'desc' ]],
        searchPanes: { layout: 'columns-1' },
        dom: '<"dtsp-verticalContainer"<"dtsp-verticalPanes"P><"dtsp-dataTable"frtip>>',
        initComplete: function () {
            const column = this.api().column(3);
            const select = $('<select class="selectpicker" data-width="170px"><optgroup label="Default"><option value="" class="font-weight-bold" title="Payment form" selected>Payment form</option></optgroup></select>')
                .appendTo($(column.header()).empty())
                .on('change', function () {
                    const val = $.fn.dataTable.util.escapeRegex($(this).val());
                    column
                        .search(val ? '^' + val + '$' : '', true, false)
                        .draw();
                });
            column.data().unique().sort().each(function (d, j) {
                const iconType = getIconType(d);
                const dataContent = "<i class='"+iconType+" la-lg mr-2' style='color: #007bff;'></i>" + d;
                select.append('<option value="'+d+'" data-content="'+dataContent+'">'+d+'</option>')
            });
            select.selectpicker({
                style: '',
                styleBase: 'form-control',
            });
            $.fn.selectpicker.Constructor.BootstrapVersion = '4';
        }
    });
    table.on( 'order.dt search.dt', function () {
        table.column(0, { search: 'applied', order: 'applied' }).nodes().each( function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();
    table.searchPanes.container().prependTo('#searchPaneContainer');
    new $.fn.dataTable.FixedHeader(table);
}
