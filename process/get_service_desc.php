<?php

include_once dirname(__DIR__).'/helpers/conn.php';

if (isset($_GET['id']))
{
    $id = escape_string($_GET['id']);
    $sql = "SELECT `desc`, price FROM additional_services where id = '$id'";
    $result = query($sql);
    if (mysqli_num_rows($result) > 0)
    {
        while($row = mysqli_fetch_array($result))
        {
            $services[] = array(
                "desc" => $row['desc'],
                "price" => $row['price']
            );
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
        echo '[]';
    }
}
else
{
    http_response_code(400);
}
