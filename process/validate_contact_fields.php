<?php

function validate_contact_fields(array $data, &$alert_msg, &$alert_type)
{
    if (!required_fields($data))
    {
        $alert_msg = "All fields are required";
        $alert_type = "danger";
        return;
    }
    $email = $data["email"];
    if (!valid_email($email))
    {
        $alert_msg = "E-mail not valid";
        $alert_type = "danger";
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
