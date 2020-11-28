<?php

include_once dirname(__DIR__).'/helpers/conn.php';

if (isset($_GET['start-date'], $_GET['end-date']))
{
    $start_date = date('Y-m-d', (strtotime(str_replace('/', '-', escape_string($_GET['start-date'])))));
    $end_date = date('Y-m-d', (strtotime(str_replace('/', '-', escape_string($_GET['end-date'])))));
    $sql = "SELECT * FROM rooms where id NOT IN " .
        "(SELECT room_id from bookings_rooms where booking_id IN " .
        "(SELECT id from bookings where status = 'Confirmed' AND '$start_date' < end_date AND '$end_date' > start_date";
    $sql .= "))";
    if (!empty($_GET['bed-amount']))
    {
        $bed_amount = escape_string($_GET['bed-amount']);
        $sql .= " AND bed_amount = '$bed_amount'";
    }
    if (!empty($_GET['min-price']))
    {
        $min_price = escape_string($_GET['min-price']);
        $sql .= " AND standard_price >= '$min_price'";
    }
    if (!empty($_GET['max-price']))
    {
        $max_price = escape_string($_GET['max-price']);
        $sql .= " AND standard_price <= '$max_price'";
    }
    if (!empty($_GET['amenities']))
    {
        foreach ($_GET['amenities'] as $amenity)
        {
            $amenities[] = escape_string($amenity);
        }
        $sql .= " AND id IN " .
            "(SELECT room_id from rooms_amenities where amenity_id IN (".implode(',', $amenities)."))";
    }
    $result = query($sql);
    if (mysqli_num_rows($result) > 0)
    {
        while($row = mysqli_fetch_array($result))
        {
            $rooms[] = array(
                "id" => $row['id'],
                "room-number" => $row['room_number'],
                "bed-amount" => $row['bed_amount'],
                "standard-price" => $row['standard_price']);
        }
        try
        {
            header('Content-type: application/json');
            echo json_encode($rooms, JSON_THROW_ON_ERROR);
        }
        catch (JsonException $e)
        {
            http_response_code(500);
        }
    }
    else
    {
        echo '[]';
    }
}
else
{
    http_response_code(400);
}
