<?php

include_once dirname(__DIR__).'/helpers/conn.php';

$sql = "SELECT name FROM payment_forms";
$result = query($sql);

if (mysqli_num_rows($result) > 0)
{
    while($row = mysqli_fetch_array($result))
    {
        $array[] = array("name" => $row['name']);
    }
    try
    {
        header('Content-type: application/json');
        echo json_encode($array, JSON_THROW_ON_ERROR);
    }
    catch (JsonException $e)
    {
        http_response_code(500);
    }
}
else
{
    return '[]';
}
