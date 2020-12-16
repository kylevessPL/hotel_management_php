<?php
include_once dirname(__DIR__).'/helpers/conn.php';
include_once dirname(__DIR__).'/helpers/error_handler.php';
include_once dirname(__DIR__).'/helpers/init_session.php';

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
if (!in_array($customer_array, $customerId, true))
{
    http_response_code(403);
    return;
}

$sql = "UPDATE bookings SET status = 'Cancelled' where id = '$id'";
query($sql);
if (!query($sql))
{
    http_response_code(500);
}
