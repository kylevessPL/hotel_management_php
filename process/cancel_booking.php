<?php
include_once dirname(__DIR__).'/helpers/conn.php';
include_once dirname(__DIR__).'/helpers/error_handler.php';
include_once dirname(__DIR__).'/helpers/init_session.php';
include_once dirname(__DIR__).'/process/get_customer_id.php';
include_once dirname(__DIR__).'/process/get_paypal_token.php';

get_customer_id($alertMsg, $alertType, $customerId);

if (!isset($_GET['id']))
{
    http_response_code(400);
    return;
}

$id = escape_string($_GET['id']);
$sql = "SELECT customer_id FROM customers_bookings where booking_id = '$id'";
$result = query($sql);
if (mysqli_num_rows($result) == 0)
{
    http_response_code(400);
    return;
}

$customer_array = [];
while($row = mysqli_fetch_array($result))
{
    $customer_array[] = $row['customer_id'];
}
if (!in_array($customerId, $customer_array, true))
{
    http_response_code(403);
    return;
}

$sql = "SELECT status from bookings where id = '$id'";
$result = query($sql);
if (mysqli_num_rows($result) == 0)
{
    http_response_code(400);
    return;
}

$status = mysqli_fetch_assoc($result)['status'];
if ($status == 'Cancelled' || $status == 'Completed')
{
    http_response_code(400);
    return;
}

$sql = "UPDATE bookings SET status = 'Cancelled' where id = '$id'";
query($sql);
if (!query($sql))
{
    http_response_code(500);
}

