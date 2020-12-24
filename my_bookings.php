<?php
include 'helpers/include_all.php';
include 'process/get_customer_id.php';
include 'process/get_paypal_token.php';

get_customer_id($alert_msg, $alert_type, $customer_id);

if (isset($customer_id))
{
    if (isset($_GET['paymentId'], $_GET['token'], $_GET['PayerID']))
    {
        try
        {
            $data = verify_paypal_payment($json->access_token);

            $booking_id = $data['transactions'][0]['item_list']['items'][0]['sku'];

            $sql = "SELECT id from customers_bookings WHERE customer_id = '$customer_id' AND booking_id = '$booking_id'";
            $result = query($sql);
            if (mysqli_num_rows($result) > 0)
            {
                $sql = "SELECT id from payment_forms where name = 'PayPal'";
                $result1 = query($sql);
                if (mysqli_num_rows($result1) > 0)
                {
                    $payment_form = mysqli_fetch_assoc($result1)['id'];
                    autocommit(false);
                    set_booking_payment($booking_id, $data, $payment_form);
                }
            }
        }
        catch (Exception $e)
        {
            error_log($e->getMessage());
        }
    }

    $sql = "SELECT b.id, r.room_number, r.bed_amount, b.book_date, b.start_date, b.end_date, b.status FROM bookings b " .
        "INNER JOIN bookings_rooms br ON b.id = br.booking_id " .
        "INNER JOIN rooms r on r.id = br.room_id " .
        "INNER JOIN customers_bookings cb on cb.booking_id = b.id " .
        "WHERE cb.customer_id = '$customer_id' ORDER BY b.book_date DESC LIMIT 4";
    $result = query($sql);
}

function verify_paypal_payment($access_token)
{
    $url = "https://api.sandbox.paypal.com/v1/payments/payment/" . rawurlencode($_GET['paymentId']);

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_POST, false);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer ' . $access_token,
        'Accept: application/json',
        'Content-Type: application/json'
    ));

    $result = curl_exec($ch);

    $data = json_decode($result, true, 512, JSON_THROW_ON_ERROR);

    curl_close($ch);
    return $data;
}

function set_booking_payment($booking_id, $data, string $payment_form)
{
    try
    {
        $sql = "SELECT status from bookings where id = '$booking_id'";
        $result = query($sql);
        if (mysqli_num_rows($result) == 0)
        {
            throw new Exception(dbException());
        }

        $status = mysqli_fetch_assoc($result)['status'];
        if ($status != 'Unpaid')
        {
            throw new Exception(dbException());
        }

        $sql = "INSERT INTO payments (booking_id, payment_date, payment_form_id, transaction_id) VALUES ('$booking_id', '" . date('Y-m-d H:i:s', strtotime($data)) . "', '" . $payment_form . "', '" . escape_string($_GET['paymentId']) . "')";
        if (!query($sql))
        {
            throw new Exception(dbException());
        }
        $sql = "UPDATE bookings SET status = 'Paid' where id = '$booking_id'";
        if (!query($sql))
        {
            throw new Exception(dbException());
        }
        commit_transaction();
        autocommit();
    }
    catch (Throwable $e)
    {
        rollback_transaction();
        autocommit();
    }
}

?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<?php view('head.php'); ?>

<body class="min-vh-100 d-flex flex-column">
<?php view('navbar.php'); ?>
<div class="container-fluid main-container flex-grow-1">
    <div class="row">
        <?php view('sidebar.php'); ?>
        <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 py-4">
            <?php view('breadcrumb.php'); ?>
            <p>View your latest room bookings and view bookings history</p>
            <?php if (isset($customer_id) && mysqli_num_rows($result) > 0) { ?>
            <div class="row mb-4">
                <?php while($row = mysqli_fetch_array($result)) { echo '
                <div class="col-12 col-md-6 mb-lg-0 col-lg-3">
                    <div class="card animated-1 shadow-sm text-center">
                        <div class="card-header">
                            <h4 class="my-0 font-weight-normal booking-id latest-content">Booking #' .htmlspecialchars($row[0]).'</h4>
                        </div>
                        <div class="card-body">
                            <h1 class="card-title"><span class="room-number">'.htmlspecialchars($row[1]).'</span> <small class="text-muted"> room</small></h1>
                            <ul class="list-unstyled">
                                <li class="mb-2"><span class="bed-amount">'.htmlspecialchars($row[2]).'</span> <small class="text-muted"> beds</small></li>
                                <li class="mb-2"><span class="book-date">'.date('d-m-Y H:m:s', strtotime(htmlspecialchars($row[3]))).'</span> <small class="text-muted"> book date</small></li>
                                <li class="mb-2"><span class="start-date">'.date('d-m-Y', strtotime(htmlspecialchars($row[4]))).'</span> <small class="text-muted"> start date</small></li>
                                <li class="mb-2"><span class="end-date">'.date('d-m-Y', strtotime(htmlspecialchars($row[5]))).'</span> <small class="text-muted"> end date</small></li>
                                <li class="mb-2"><span class="booking-status" style="color: '; if ($row[6] === "Paid") { echo "#28a745"; } else if ($row[6] === "Unpaid") { echo "orange"; } else if ($row[6] === "Cancelled") { echo "#dc3545"; } else { echo "#007bff"; } echo '">'.htmlspecialchars($row[6]).'</span> <small class="text-muted"> status</small></li>
                            </ul>
                        </div>
                    </div>
                </div>
                '; } ?>
            </div>
            <?php } ?>
            <div class="row">
                <div class="col-12 col-xl-12 mb-lg-0">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5>Booking history</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered w-100" id="bookingsTable">
                                <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Booking id</th>
                                    <th>Room number</th>
                                    <th>Bed amount</th>
                                    <th>Book date</th>
                                    <th>Start date</th>
                                    <th>End date</th>
                                    <th>Status</th>
                                    <th>Booking details</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<?php view('footer.php'); ?>

<?php view('scripts.php'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdn.datatables.net/v/bs4/dt-1.10.23/fh-3.1.7/r-2.2.6/sp-1.2.2/sl-1.3.1/datatables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.6.0/cleave.min.js"></script>
<script src="/assets/js/validation-additional-methods.js"></script>
<script src="/assets/js/my-bookings.js"></script>
<script src="/assets/js/payment-modal.js"></script>

<script>
    function isCustomerIdSet() {
        let value = <?php echo isset($customer_id) ? 'true' : 'false'; ?>;
        return value;
    }
</script>

</body>
</html>