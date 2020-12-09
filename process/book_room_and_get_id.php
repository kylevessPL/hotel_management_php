<?php

include_once dirname(__DIR__).'/helpers/conn.php';

try
{
    $data = file_get_contents(json_decode("php://input", true, 512, JSON_THROW_ON_ERROR));

}
catch (JsonException $e)
{
    http_response_code(400);
}
