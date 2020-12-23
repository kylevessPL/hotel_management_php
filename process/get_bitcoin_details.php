<?php
include_once dirname(__DIR__).'/helpers/conn.php';
include_once dirname(__DIR__).'/process/get_customer_id.php';

get_customer_id($alertMsg, $alertType, $customerId);

if (!isset($_GET['id'], $_GET['value']))
{
    http_response_code(400);
    return;
}

if (!isset($customerId))
{
    http_response_code(401);
    return;
}

try
{
    $data1 = file_get_contents("https://blockchain.info/tobtc?currency=PLN&value=" . rawurlencode($_GET['value']));
    $content = @file_get_contents(get_url("get_new_address"));
    if ($content === FALSE)
    {
        $content = file_get_contents(get_url("get_address_by_label"));
    }
    $data2 = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
}
catch (JsonException $e) {
    http_response_code(500);
    return;
}

$data[] = array(
    "total" => $data1,
    "address" => $data2['data']['address']
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

function get_url($method) {
    return "https://block.io/api/v2/" .$method. "/?api_key=" . rawurlencode("ab29-882b-09ce-9792") . "&label=booking" . rawurlencode($_GET['id']);
}
