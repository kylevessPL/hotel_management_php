<?php

function validate_address_fields(array $data, &$alert_msg, &$alert_type)
{
    if (!required_fields($data))
    {
        $alert_msg = "All fields are required";
        $alert_type = "danger";
        return;
    }
    $street_name = $data["streetName"];
    $house_number = $data["houseNumber"];
    $zip_code = $data["zipCode"];
    $city = $data["city"];
    if (!valid_len(2, 30, $street_name))
    {
        $alert_msg = "Street name must be between 2 and 30 characters long";
        $alert_type = "danger";
        return;
    }
    if (!valid_regex("/^[0-9a-zA-Z .\/]*$/", $house_number))
    {
        $alert_msg = "House number must contain only letters and numbers";
        $alert_type = "danger";
        return;
    }
    if (!valid_len(1, 10, $house_number))
    {
        $alert_msg = "House number must be maximum 10 characters long";
        $alert_type = "danger";
        return;
    }
    if (!valid_regex("/^[0-9 \-]*$/", $zip_code))
    {
        $alert_msg = "Zip code must contain only numbers, spaces or a dash";
        $alert_type = "danger";
        return;
    }
    if (!valid_len(2, 10, $zip_code))
    {
        $alert_msg = "Zip code must be between 2 and 10 characters long";
        $alert_type = "danger";
        return;
    }
    if (!valid_len(2, 30, $city))
    {
        $alert_msg = "City must be between 2 and 30 characters long";
        $alert_type = "danger";
        return;
    }
}

function required_fields(array $data)
{
    if (empty($data['addressNum']))
    {
        return count($data) == count(array_filter($data)) + 1;
    }
    return count($data) == count(array_filter($data));
}

function valid_regex($pattern, $field)
{
    return preg_match($pattern, $field);
}

function valid_len($min, $max, $field)
{
    return (strlen($field) >= $min) && (strlen($field) <= $max);
}

function valid_document_type($field)
{
    return ($field == 'ID card') || ($field == 'Passport');
}
