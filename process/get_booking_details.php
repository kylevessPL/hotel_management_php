<?php
include_once dirname(__DIR__).'/helpers/conn.php';
include_once dirname(__DIR__).'/process/get_customer_id.php';

get_customer_id($alert_msg, $alert_type, $customer_id);

if (!isset($_GET['id']))
{
    http_response_code(400);
    return;
}
if (!isset($customer_id))
{
    http_response_code(401);
    return;
}

$id = $_GET['id'];

check_customer_booking_ownership($id, $customer_id);

$total = get_total($id);
$room_id = get_room_id($id);
$services = get_services($id);
$people = get_people($id);
$payment_form = get_payment_form($id);

$data[] = array(
    "room-id" => $room_id,
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

function check_customer_booking_ownership($id, $customer_id)
{
    $sql = "SELECT customer_id from customers_bookings WHERE booking_id = '$id'";
    $result = query($sql);
    if (mysqli_num_rows($result) == 0)
    {
        http_response_code(400);
        die();
    }

    while ($row = mysqli_fetch_array($result))
    {
        $customer_array[] = $row['customer_id'];
    }

    if (!in_array($customer_id, $customer_array, true))
    {
        http_response_code(403);
        die();
    }
}

function get_total($id)
{
    $sql = "SELECT final_price from bookings WHERE id = '$id'";
    $result = query($sql);
    return mysqli_fetch_assoc($result)['final_price'];
}

function get_room_id($id)
{
    $sql = "SELECT room_id from bookings_rooms WHERE booking_id = '$id'";
    $result = query($sql);
    return mysqli_fetch_assoc($result)['room_id'];
}

function get_services($id)
{
    $services = [];
    $sql = "SELECT name from additional_services WHERE id IN (SELECT service_id from bookings_services WHERE booking_id = '$id')";
    $result = query($sql);

    while ($row = mysqli_fetch_array($result))
    {
        $services[] = array("name" => $row['name']);
    }
    return $services;
}

function get_people($id)
{
    $people = [];
    $sql = "SELECT first_name, last_name, document_type, document_id from customers WHERE id IN (SELECT customer_id from customers_bookings WHERE booking_id = '$id')";
    $result = query($sql);

    while ($row = mysqli_fetch_array($result))
    {
        $people[] = array(
            "first-name" => $row['first_name'],
            "last-name" => $row['last_name'],
            "document-type" => $row['document_type'],
            "document-id" => $row['document_id']
        );
    }
    return $people;
}

function get_payment_form($id)
{
    $payment_form = null;
    $sql = "SELECT name from payment_forms WHERE id = (SELECT payment_form_id from payments WHERE booking_id = '$id')";
    $result = query($sql);

    if (mysqli_num_rows($result) > 0)
    {
        $payment_form = mysqli_fetch_assoc($result)['name'];
    }
    return $payment_form;
}
