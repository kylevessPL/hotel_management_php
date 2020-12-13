<?php
include 'helpers/include_all.php';
include 'process/get_customer_id.php';
include 'process/get_paypal_token.php';

get_customer_id($alertMsg, $alertType, $customerId);

if (isset($customerId, $_GET['paymentId'], $_GET['token'], $_GET['PayerID']))
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
            $payment_form = mysqli_fetch_assoc($result);
            $sql = "INSERT INTO payments (booking_id, payment_date, payment_form_id, transaction_id) VALUES ('$booking_id', '".date('Y-m-d H:i:s', strtotime($data['update_time']))."', '".$payment_form['id']."', '".escape_string($_GET['paymentId'])."')";
            if (query($sql))
            {
                $sql = "UPDATE bookings SET status = 'Payed' where id = '$booking_id'";
                query($sql);
            }
        }
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
            <p>View your incoming bookings as well as the ones you made in the past</p>
        </main>
    </div>
</div>
<?php view('footer.php'); ?>

<?php view('scripts.php'); ?>

</body>
</html>