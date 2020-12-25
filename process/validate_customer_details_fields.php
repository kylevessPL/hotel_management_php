<?php

function validate_customer_details_fields(array $data, &$alert_msg, &$alert_type)
{
    if (!required_fields($data))
    {
        $alert_msg = "All fields are required";
        $alert_type = "danger";
        return;
    }
    $first_name = $data["first-name"];
    $last_name = $data["last-name"];
    $document_type = $data["document-type"];
    $document_id = $data["document-id"];
    if (!valid_len(2, 30, $first_name))
    {
        $alert_msg = "First name must be between 2 and 30 characters long";
        $alert_type = "danger";
        return;
    }
    if (!valid_len(2, 30, $last_name))
    {
        $alert_msg = "Last name must be between 2 and 30 characters long";
        $alert_type = "danger";
        return;
    }
    if (!valid_document_type($document_type))
    {
        $alert_msg = "Document is not a valid type";
        $alert_type = "danger";
        return;
    }
    if (!valid_len(7, 14, $document_id))
    {
        $alert_msg = "Document ID must be between 7 and 14 characters long";
        $alert_type = "danger";
        return;
    }
    if (!valid_regex("/^[A-Z0-9 -]*$/", $document_id))
    {
        $alert_msg = "Document ID must contain only capital letters, digits or - character";
        $alert_type = "danger";
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
