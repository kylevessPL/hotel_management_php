<?php

function validate_customer_details_fields(array $data, &$alertMsg, &$alertType)
{
    if(!required_fields($data))
    {
        $alertMsg = "All fields are required";
        $alertType = "danger";
    }
    $first_name = $data["first-name"];
    $last_name = $data["last-name"];
    $document_type = $data["document-type"];
    $document_id = $data["document-id"];
    if(!valid_len(2, 30, $first_name))
    {
        $alertMsg = "First name must be between 2 and 30 characters long";
        $alertType = "danger";
    }
    if(!valid_len(2, 30, $last_name))
    {
        $alertMsg = "Last name must be between 2 and 30 characters long";
        $alertType = "danger";
    }
    if(!valid_document_type($document_type))
    {
        $alertMsg = "Document is not a valid type";
        $alertType = "danger";
    }
    if(!valid_len(7, 14, $document_id))
    {
        $alertMsg = "Document ID must be between 7 and 14 characters long";
        $alertType = "danger";
    }
    if(!valid_regex("/^[A-Z0-9 -]*$/", $document_id))
    {
        $alertMsg = "Document ID must contain only capital letters, digits or - character";
        $alertType = "danger";
    }
}

function required_fields(array $data)
{
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
