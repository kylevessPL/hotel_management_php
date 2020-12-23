<?php

function validate_contact_fields(array $data, &$alertMsg, &$alertType)
{
    if(!required_fields($data))
    {
        $alertMsg = "All fields are required";
        $alertType = "danger";
        return;
    }
    $email = $data["email"];
    if(!valid_email($email))
    {
        $alertMsg = "E-mail not valid";
        $alertType = "danger";
    }
}

function required_fields(array $data)
{
    return count($data) == count(array_filter($data)) + 1;
}

function valid_email($field)
{
    return filter_var($field, FILTER_VALIDATE_EMAIL);
}
