<?php
function validate_address_fields(array $data, &$alertMsg, &$alertType)
{
    if(!required_fields($data))
    {
        $alertMsg = "All fields are required";
        $alertType = "danger";
    }
    $street_name = $data["streetName"];
    $house_number = $data["houseNumber"];
    $zip_code = $data["zipCode"];
    $city = $data["city"];
    if(!valid_len(2, 30, $street_name))
    {
        $alertMsg = "Street name must be between 2 and 30 characters long";
        $alertType = "danger";
    }
    if(!valid_regex("/^[0-9a-zA-Z .\/]*$/", $house_number))
    {
        $alertMsg = "House number must contain only letters and numbers";
        $alertType = "danger";
    }
    if(!valid_len(1, 10, $house_number))
    {
        $alertMsg = "House number must be maximum 10 characters long";
        $alertType = "danger";
    }
    if(!valid_regex("/^[0-9 \-]*$/", $zip_code))
    {
        $alertMsg = "Zip code must contain only numbers, spaces or a dash";
        $alertType = "danger";
    }
    if(!valid_len(2, 10, $zip_code))
    {
        $alertMsg = "Zip code must be between 2 and 10 characters long";
        $alertType = "danger";
    }
    if(!valid_len(2, 30, $city))
    {
        $alertMsg = "City must be between 2 and 30 characters long";
        $alertType = "danger";
    }
}

function required_fields(array $data)
{
    if (!isset($data['addressNum']) || empty($data['addressNum']))
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