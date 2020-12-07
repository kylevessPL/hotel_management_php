<?php

include_once dirname(__DIR__).'/helpers/conn.php';

if (!empty($_GET['id']))
{
    $id = escape_string($_GET['id']);
    $sql = "SELECT first_name, last_name, document_type, document_id FROM customers WHERE id = '$id'";
    $result = query($sql);
    if (mysqli_num_rows($result) > 0)
    {
        $customer_details = mysqli_fetch_assoc($result);
        $data[] = array(
            "first-name" => $customer_details['first_name'],
            "last-name" => $customer_details['last_name'],
            "document-type" => $customer_details['document_type'],
            "document-id" => $customer_details['document_id']
        );
        try
        {
            header('Content-type: application/json');
            echo json_encode($data, JSON_THROW_ON_ERROR);
        }
        catch (JsonException $e)
        {
            http_response_code(500);
        }
    }
    else
    {
        http_response_code(400);
    }
}
else
{
    http_response_code(400);
}
