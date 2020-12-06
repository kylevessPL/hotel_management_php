<?php

include_once dirname(__DIR__).'/helpers/conn.php';

if (isset($_GET['promo-code']))
{
    $promo_code = escape_string($_GET['promo-code']);
    $sql = "SELECT discount FROM discounts where code = '$promo_code'";
    $result = query($sql);
    if (mysqli_num_rows($result) > 0)
    {
        $row = mysqli_fetch_array($result);
        $promo_codes[] = array("discount" => $row['discount']);
        try
        {
            header('Content-type: application/json');
            echo json_encode($promo_codes, JSON_THROW_ON_ERROR);
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
