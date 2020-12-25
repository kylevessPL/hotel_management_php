<?php
include_once dirname(__DIR__).'/helpers/conn.php';

if (empty($_GET['id']))
{
    http_response_code(400);
    return;
}

$amenities_list = get_amenities_list();

$sql = "SELECT name from amenities where id IN (".implode(',', $amenities_list).")";
$result = query($sql);

while($row = mysqli_fetch_array($result))
{
    $amenities[] = array("name" => $row['name']);
}

try
{
    header('Content-type: application/json');
    echo json_encode($amenities, JSON_THROW_ON_ERROR);
}
catch (JsonException $e)
{
    http_response_code(500);
}

function get_amenities_list()
{
    $room_id = escape_string($_GET['id']);
    $sql = "SELECT amenity_id from rooms_amenities where room_id = '$room_id'";
    $result = query($sql);
    if (mysqli_num_rows($result) == 0)
    {
        echo '[]';
        die();
    }

    while ($row = mysqli_fetch_array($result))
    {
        $amenities_list[] = $row[0];
    }

    return $amenities_list;
}
