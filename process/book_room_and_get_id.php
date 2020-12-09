<?php

include_once dirname(__DIR__).'/helpers/conn.php';
include_once dirname(__DIR__).'/process/get_customer_id.php';

get_customer_id($alertMsg, $alertType, $customerId);

try
{
    $data = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);

}
catch (JsonException $e)
{
    http_response_code(400);
    return;
}
if (!isset($data['start-date'], $data['end-date']) || empty($data['start-date']) || empty($data['end-date'])) {
    http_response_code(400);
    return;
}
if (!isset($customerId)) {
    http_response_code(401);
    return;
}
$customer_list = [];
$customer_list[] = $customerId;
if (isset($data['people']) && !empty($data['people']))
{
    foreach ($data['people'] as &$value) {
        $first_name = escape_string($value[0]['first-name']);
        $last_name = escape_string($value[0]['last-name']);
        $document_type = escape_string($value[0]['document-type']);
        $document_id = escape_string($value[0]['document-id']);
        $sql = "INSERT INTO customers (first_name, last_name, document_type, document_id) VALUES ('$first_name', '$last_name', '$document_type', '$document_id')";
        if (!query($sql))
        {
            http_response_code(500);
            return;
        }
        $customer_list[] = insert_id();
    }
    unset($value);
}

$room_id = escape_string($data['room-id']);
$sql = "SELECT standard_price FROM rooms where id = '$room_id'";
$result = query($sql);
if (mysqli_num_rows($result) == 0)
{
    http_response_code(400);
    return;
}
$room_price = mysqli_fetch_assoc($result)['standard_price'];

$services_price = 0;
if (isset($data['services']) && !empty($data['services']))
{
    $service_list = [];
    foreach ($data['services'] as &$value) {
        $service_list[] = $value['id'];
    }
    unset($value);
    $sql = "SELECT price FROM additional_services where id IN (".implode(',', $service_list).")";
    $result = query($sql);
    if (mysqli_num_rows($result) == 0)
    {
        http_response_code(400);
        return;
    }

    while($row = mysqli_fetch_array($result))
    {
        $services_price += $row[0];
    }
}
$discount_id = null;
if (isset($data['promo-code']) && !empty($data['promo-code']))
{
    $promo_code = escape_string($data['promo-code']);
    $sql = "SELECT id, discount from discounts where code = '$promo_code'";
    $result = query($sql);
    if (mysqli_num_rows($result) == 0)
    {
        http_response_code(400);
        return;
    }
    $row = mysqli_fetch_assoc($result);
    $discount_id = $row['id'];
    $discount = $row['discount'];
}

$start_date = date('Y-m-d', (strtotime(str_replace('/', '-', escape_string($data['start-date'])))));
$end_date = date('Y-m-d', (strtotime(str_replace('/', '-', escape_string($data['end-date'])))));

$total = calculateTotal($start_date, $end_date, $room_price, $services_price, $discount);

$sql = "INSERT INTO bookings (start_date, end_date, discount_id, final_price) VALUES ('$start_date', '$end_date', '$discount_id', '$total')";
if (!query($sql))
{
    http_response_code(500);
    return;
}
$booking_id = insert_id();

$sql = "INSERT INTO bookings_rooms (booking_id, room_id) VALUES ('$booking_id', '$room_id')";
if (!query($sql))
{
    http_response_code(500);
    return;
}

if (count($service_list) > 0)
{
    $sql_join = [];
    foreach ($service_list as &$value) {
        $sql_join[] = "(" . $booking_id . ", " . $value . ")";
    }
    unset($value);
    $sql = "INSERT INTO bookings_services (booking_id, service_id) VALUES " . implode(',', $sql_join);
    if (!query($sql))
    {
        http_response_code(500);
        return;
    }
}

if (count($customer_list) > 0)
{
    $sql_join = [];
    foreach ($customer_list as &$value) {
        $sql_join[] = "(" . $value . ", " . $booking_id . ")";
    }
    unset($value);
    $sql = "INSERT INTO customers_bookings (customer_id, booking_id) VALUES " . implode(',', $sql_join);
    if (!query($sql))
    {
        http_response_code(500);
        return;
    }
}

$json[] = array(
    "id" => $booking_id,
    "total" => $total
);

try
{
    header('Content-type: application/json');
    echo json_encode($json, JSON_THROW_ON_ERROR);
}
catch (JsonException $e)
{
    http_response_code(500);
}

function calculateTotal($start_date, $end_date, $room_price, $services_price, $discount)
{
    $diff = date_diff(date_create($start_date), date_create($end_date));
    return number_format(($room_price + $services_price) * $diff->days * (1 - $discount / 100), 2, '.', '');
}
