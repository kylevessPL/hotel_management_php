<?php

include_once dirname(__DIR__).'/helpers/conn.php';
include_once dirname(__DIR__).'/process/get_customer_id.php';

get_customer_id($alertMsg, $alertType, $customerId);

if (!isset($_GET['id']))
{
    http_response_code(400);
    return;
}
if (!isset($customerId))
{
    http_response_code(401);
    return;
}
$sql = "SELECT customer_id from customers_bookings WHERE booking_id = '".escape_string($_GET['id'])."'";
$result = query($sql);
if (mysqli_num_rows($result) == 0)
{
    http_response_code(400);
    return;
}
if (mysqli_fetch_assoc($result)['customer_id'] != $customerId)
{
    http_response_code(403);
    return;
}

$sql = "SELECT final_price from bookings WHERE id = '".escape_string($_GET['id'])."'";
$result = query($sql);
$total = mysqli_fetch_assoc($result)['final_price'];

$services = [];
$sql = "SELECT name from additional_services WHERE id IN (SELECT service_id from bookings_services WHERE booking_id = '".escape_string($_GET['id'])."')";
$result = query($sql);
while($row = mysqli_fetch_array($result))
{
    $services[] = array("name" => $row['name']);
}

$people = [];
$sql = "SELECT first_name, last_name, document_type, document_id from customers WHERE id IN (SELECT customer_id from customers_bookings WHERE booking_id = '".escape_string($_GET['id'])."')";
$result = query($sql);
while($row = mysqli_fetch_array($result))
{
    $people[] = array(
        "first-name" => $row['first_name'],
        "last-name" => $row['last_name'],
        "document-type" => $row['document_type'],
        "document-id" => $row['document_id']
    );
}

$payment_form = null;
$sql = "SELECT name from payment_forms WHERE id = (SELECT payment_form_id from payments WHERE booking_id = '".escape_string($_GET['id'])."')";
$result = query($sql);
if (mysqli_num_rows($result) > 0)
{
    $payment_form = mysqli_fetch_assoc($result)['name'];
}

$data[] = array(
    "services" => $services,
    "people" => $people,
    "total" => $total,
    "payment-form" => $payment_form
);

try
{
    header('Content-type: application/json');
    echo json_encode($data, JSON_THROW_ON_ERROR);
}
catch (JsonException $e)
{
    http_response_code(500);
}
