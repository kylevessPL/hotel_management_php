<?php
include 'helpers/include_all.php';
include 'process/get_customer_id.php';
include 'process/get_paypal_token.php';

get_customer_id($alertMsg, $alertType, $customerId);

if (isset($customerId))
{
    if (isset($_GET['paymentId'], $_GET['token'], $_GET['PayerID']))
    {
        $ch = curl_init("https://api.sandbox.paypal.com/v1/payments/payment/" . rawurlencode($_GET['paymentId']));

        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $json->access_token,
            'Accept: application/json',
            'Content-Type: application/json'
        ));

        $result = curl_exec($ch);

        try
        {
            $data = json_decode($result, true, 512, JSON_THROW_ON_ERROR);
        }
        catch (JsonException $e)
        {
            return;
        }

        curl_close($ch);

        $booking_id = $data['transactions'][0]['item_list']['items'][0]['sku'];

        $sql = "SELECT id from customers_bookings WHERE customer_id = '$customerId' AND booking_id = '$booking_id'";
        $result = query($sql);
        if (mysqli_num_rows($result) > 0) {
            $array[] = array(
                "booking-id" => $booking_id,
                "payment-date" => $data['update_time'],
                "status" => $data['payer']['status']);
            $sql = "SELECT id from payment_forms where name = 'PayPal'";
            $result = query($sql);
            if (mysqli_num_rows($result) > 0)
            {
                $payment_form = mysqli_fetch_assoc($result)['id'];
                autocommit(false);
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

                    $sql = "INSERT INTO payments (booking_id, payment_date, payment_form_id, transaction_id) VALUES ('$booking_id', '".date('Y-m-d H:i:s', strtotime($data['update_time']))."', '".$payment_form."', '".escape_string($_GET['paymentId'])."')";
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
        }
    }

    $sql = "SELECT b.id, r.room_number, r.bed_amount, b.book_date, b.start_date, b.end_date, b.status FROM bookings b " .
    "INNER JOIN bookings_rooms br ON b.id = br.booking_id " .
    "INNER JOIN rooms r on r.id = br.room_id " .
    "INNER JOIN customers_bookings cb on cb.booking_id = b.id " .
    "WHERE cb.customer_id = '$customerId' ORDER BY b.book_date DESC LIMIT 4";
    $result = query($sql);
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
            <?php if (mysqli_num_rows($result) > 0) { ?>
            <div class="row mb-4">
                <?php while($row = mysqli_fetch_array($result)) { echo '
                <div class="col-12 col-md-6 mb-4 mb-lg-0 col-lg-3">
                    <div class="card shadow-sm text-center">
                        <div class="card-header">
                            <h4 class="my-0 font-weight-normal booking-id latest-content">Booking #'.htmlspecialchars($row[0]).'</h4>
                        </div>
                        <div class="card-body">
                            <h1 class="card-title"><span class="room-number">'.htmlspecialchars($row[1]).'</span> <small class="text-muted"> room</small></h1>
                            <ul class="list-unstyled mt-3 mb-4">
                                <li><span class="bed-amount">'.htmlspecialchars($row[2]).'</span> <small class="text-muted"> beds</small></li>
                                <li><span class="book-date">'.date('d-m-Y H:m:s', strtotime(htmlspecialchars($row[3]))).'</span> <small class="text-muted"> book date</small></li>
                                <li><span class="start-date">'.date('d-m-Y', strtotime(htmlspecialchars($row[4]))).'</span> <small class="text-muted"> start date</small></li>
                                <li><span class="end-date">'.date('d-m-Y', strtotime(htmlspecialchars($row[5]))).'</span> <small class="text-muted"> end date</small></li>
                                <li><span class="booking-status" style="color: '; if ($row[6] === "Paid") { echo "#28a745"; } else if ($row[6] === "Unpaid") { echo "orange"; } else if ($row[6] === "Cancelled") { echo "#dc3545"; } else { echo "#007bff"; } echo '">'.htmlspecialchars($row[6]).'</span> <small class="text-muted"> status</small></li>
                            </ul>
                            <button type="button" class="btn btn-outline-success viewBookingDescBtn latest-content">View booking details</button>
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
<script src="https://cdn.datatables.net/v/bs4/dt-1.10.22/fh-3.1.7/r-2.2.6/datatables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.6.0/cleave.min.js"></script>
<script src="/assets/js/form-validation.js"></script>
<script src="/assets/js/my-bookings.js"></script>
<script src="/assets/js/payment-modal.js"></script>

</body>
</html>