<?php
include_once dirname(__DIR__).'/helpers/conn.php';
include_once dirname(__DIR__).'/process/get_customer_id.php';
include_once dirname(__DIR__).'/process/get_paypal_token.php';

get_customer_id($alert_msg, $alert_type, $customer_id);

if (!isset($_GET['booking-id']))
{
    http_response_code(400);
    return;
}

if (!isset($customer_id))
{
    http_response_code(401);
    return;
}

$row = check_booking_validity();

$result = get_paypal_payment_status($row, $json->access_token);

try
{
    $data = json_decode($result, true, 512, JSON_THROW_ON_ERROR);
}
catch (JsonException $e)
{
    http_response_code(500);
    return;
}

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

function check_booking_validity()
{
    $sql = "SELECT final_price, status from bookings where id = '" . $_GET['booking-id'] . "'";
    $booking_result = query($sql);
    if (mysqli_num_rows($booking_result) == 0)
    {
        http_response_code(400);
        die();
    }

    $row = mysqli_fetch_array($booking_result);

    if ($row['status'] === 'Payed')
    {
        http_response_code(400);
        die();
    }
    return $row;
}

function get_paypal_payment_status($row, $access_token)
{
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
                    "sku": "' . $_GET['booking-id'] . '",
                    "currency": "PLN"
                }]
            }
        }],
        "redirect_urls": {
            "return_url": "http://localhost/dashboard/my-bookings",
            "cancel_url": "http://localhost/dashboard/my-bookings"
        }
    }';

    $ch = curl_init('https://api.sandbox.paypal.com/v1/payments/payment');

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer ' . $access_token,
        'Accept: application/json',
        'Content-Type: application/json'
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

    $result = curl_exec($ch);

    curl_close($ch);

    return $result;
}
