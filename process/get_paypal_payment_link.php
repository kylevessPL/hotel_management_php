<?php

include_once dirname(__DIR__).'/helpers/conn.php';
include_once dirname(__DIR__).'/process/get_customer_id.php';
include_once dirname(__DIR__).'/process/get_paypal_token.php';

get_customer_id($alertMsg, $alertType, $customerId);

if (!isset($_GET['booking-id'])) {
    http_response_code(400);
    return;
}

if (!isset($customerId)) {
    http_response_code(401);
    return;
}

$ch = curl_init('https://api.sandbox.paypal.com/v1/payments/payment');
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: Bearer ' . $json->access_token,
    'Accept: application/json',
    'Content-Type: application/json'
));

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$sql = "SELECT final_price, status from bookings where id = '".$_GET['booking-id']."'";
$booking_result = query($sql);
if (mysqli_num_rows($booking_result) > 0)
{
    $row = mysqli_fetch_array($booking_result);
}
else
{
    http_response_code(400);
    return;
}

if ($row['status'] === 'Payed')
{
    http_response_code(400);
    return;
}

$payload = '{
    "intent": "sale",
    "payer": {
        "payment_method": "paypal"
    },
    "transactions": [{
        "amount": {
            "currency": "PLN",
            "total": ' . $row['final_price'] . '
        },
        "payee": {
            "email": "bookings@hotela.pl"
        },
        "description": "HoteLA - Booking #' . $_GET['booking-id'] . '",
        "item_list": {
            "items": [{
                "name": "Booking #' . $_GET['booking-id'] . '",
                "quantity": "1",
                "price": "' . $row['final_price'] . '",
                "sku": "booking' . $_GET['booking-id'] . '",
                "currency": "PLN"
            }]
        }
    }],
    "redirect_urls": {
        "return_url": "http://localhost/dashboard/my-bookings",
        "cancel_url": "http://localhost/dashboard/my-bookings"
    }
}';

curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

$result = curl_exec($ch);

try
{
    $data = json_decode($result, true, 512, JSON_THROW_ON_ERROR);
}
catch (JsonException $e)
{
    http_response_code(500);
    return;
}

curl_close($ch);

$array[] = array("payment-link" => $data['links'][1]['href']);
try
{
    header('Content-type: application/json');
    echo json_encode($array, JSON_THROW_ON_ERROR);
}
catch (JsonException $e)
{
    http_response_code(500);
}
