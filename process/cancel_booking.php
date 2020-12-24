<?php
include_once dirname(__DIR__).'/helpers/conn.php';
include_once dirname(__DIR__).'/helpers/error_handler.php';
include_once dirname(__DIR__).'/helpers/init_session.php';
include_once dirname(__DIR__).'/process/get_customer_id.php';
include_once dirname(__DIR__).'/process/get_paypal_token.php';

get_customer_id($alert_msg, $alert_type, $customer_id);

if (!isset($_GET['id']))
{
    http_response_code(400);
    return;
}

$id = escape_string($_GET['id']);

$result = get_customer_list($id);

check_customer_booking_ownership($result, $customer_id);

check_booking_status_validity($id);

set_booking_status($id);

function get_customer_list($id)
{
    $sql = "SELECT customer_id FROM customers_bookings where booking_id = '$id'";
    $result = query($sql);
    if (mysqli_num_rows($result) == 0)
    {
        http_response_code(400);
        die();
    }
    return $result;
}

function check_customer_booking_ownership(mysqli_result $result, $customer_id): void
{
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

function check_booking_status_validity(string $id)
{
    $sql = "SELECT status from bookings where id = '$id'";
    $result = query($sql);
    if (mysqli_num_rows($result) == 0)
    {
        http_response_code(400);
        die();
    }

    $status = mysqli_fetch_assoc($result)['status'];
    if ($status == 'Cancelled' || $status == 'Completed')
    {
        http_response_code(400);
        die();
    }
}

function set_booking_status(string $id)
{
    $sql = "UPDATE bookings SET status = 'Cancelled' where id = '$id'";
    query($sql);
    if (!query($sql))
    {
        http_response_code(500);
    }
}
