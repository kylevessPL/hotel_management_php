<?php

include_once dirname(__DIR__).'/helpers/conn.php';

$sql = "SELECT id, `desc` FROM additional_services";
$result = query($sql);
if (mysqli_num_rows($result) > 0)
{
    while($row = mysqli_fetch_array($result))
    {
        $services[] = array(
            "id" => $row['id'],
            "desc" => $row['desc']);
    }
    try
    {
        header('Content-type: application/json');
        echo json_encode($services, JSON_THROW_ON_ERROR);
    }
    catch (JsonException $e)
    {
        http_response_code(500);
    }
}
else
{
    http_response_code(204);
}
