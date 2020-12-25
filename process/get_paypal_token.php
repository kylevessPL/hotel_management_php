<?php

$client_id = "AZGlapLmFWkASH7ClvRamfciV36NZehDL3nEgjSJ3ipKQGBWAWB9rwj1OWL_6uSvv6tKMW3656nCzAhy";
$secret = "ENip6UB15n3XgN2pAzP5RrhYRCrHweC0YLX3PDvYmz47Loeiw_HXFrtEh1MsbemAbvwMfaYfubcrtu9S";

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.sandbox.paypal.com/v1/oauth2/token");
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, $client_id.":".$secret);
curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");

$result = curl_exec($ch);

try
{
    $json = json_decode($result, false, 512, JSON_THROW_ON_ERROR);
}
catch (JsonException $e)
{
    http_response_code(500);
}

curl_close($ch);
