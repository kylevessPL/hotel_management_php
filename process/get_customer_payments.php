<?php

include_once dirname(__DIR__).'/helpers/conn.php';
include_once dirname(__DIR__).'/process/get_customer_id.php';

get_customer_id($alertMsg, $alertType, $customerId);

if (!isset($customerId))
{
    http_response_code(401);
    return;
}
$sql = "SELECT p.transaction_id, p.payment_date, pf.name, p.booking_id FROM payments p " .
    "INNER JOIN customers_bookings cb ON p.booking_id = cb.booking_id " .
    "INNER JOIN payment_forms pf ON pf.id = p.payment_form_id " .
    "WHERE cb.customer_id = '$customerId'";
$result = query($sql);
if (mysqli_num_rows($result) == 0)
{
    echo '[]';
    return;
}
while($row = mysqli_fetch_array($result))
{
    $array[] = array(
        "payment-id" => $row['transaction_id'],
        "payment-date" => $row['payment_date'],
        "payment-form" => $row['name'],
        "booking-id" => $row['booking-id']);
}
try
{
    header('Content-type: application/json');
    echo json_encode($arrray, JSON_THROW_ON_ERROR);
}
catch (JsonException $e)
{
    http_response_code(500);
}
