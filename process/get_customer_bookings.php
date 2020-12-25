<?php
include_once dirname(__DIR__).'/helpers/conn.php';
include_once dirname(__DIR__).'/process/get_customer_id.php';

get_customer_id($alert_msg, $alert_type, $customer_id);

if (!isset($customer_id))
{
    http_response_code(401);
    return;
}

$sql = "SELECT b.id, r.room_number, r.bed_amount, b.book_date, b.start_date, b.end_date, b.status FROM bookings b " .
    "INNER JOIN bookings_rooms br ON b.id = br.booking_id " .
    "INNER JOIN rooms r on r.id = br.room_id " .
    "INNER JOIN customers_bookings cb on cb.booking_id = b.id " .
    "WHERE cb.customer_id = '$customer_id'";

$result = query($sql);

if (mysqli_num_rows($result) == 0)
{
    echo '[]';
    return;
}

while($row = mysqli_fetch_array($result))
{
    $bookings[] = array(
        "booking-id" => $row['id'],
        "room-number" => $row['room_number'],
        "bed-amount" => $row['bed_amount'],
        "book-date" => $row['book_date'],
        "start-date" => $row['start_date'],
        "end-date" => $row['end_date'],
        "status" => $row['status']);
}

try
{
    header('Content-type: application/json');
    echo json_encode($bookings, JSON_THROW_ON_ERROR);
}
catch (JsonException $e)
{
    http_response_code(500);
}
