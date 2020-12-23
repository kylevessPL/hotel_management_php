<?php
include_once dirname(__DIR__).'/helpers/conn.php';

if (!empty($_GET['id']) && !empty($_GET['start-date']) && !empty($_GET['end-date']))
{
    $id = escape_string($_GET['id']);
    $start_date = date('Y-m-d', (strtotime(str_replace('/', '-', escape_string($_GET['start-date'])))));
    $end_date = date('Y-m-d', (strtotime(str_replace('/', '-', escape_string($_GET['end-date'])))));
    $sql = "SELECT id from bookings_rooms where room_id = '$id' AND booking_id IN " .
        "(SELECT id from bookings where status != 'Cancelled' AND '$start_date' <= end_date AND '$end_date' >= start_date)";
    $result = query($sql);
    if(mysqli_num_rows($result) > 0)
    {
        echo 'false';
    }
    else
    {
        echo 'true';
    }
}
else
{
    echo 'false';
}
