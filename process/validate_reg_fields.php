<?php

function validate_reg_fields(array $data, &$alert_msg, &$alert_type)
{
    if (!required_fields($data))
    {
        $alert_msg = "All fields are required";
        $alert_type = "danger";
        return;
    }
    $username = $data["username"];
    $password = $data["password"];
    $password2 = $data["password2"];
    $email = $data["email"];
    if (!valid_len(6, 16, $username))
    {
        $alert_msg = "Username must be between 6 and 16 characters long";
        $alert_type = "danger";
        return;
    }
    if (!valid_regex("/^[a-zA-Z0-9.-_]*$/", $username))
    {
        $alert_msg = "Username must may contain only letters, digits or - _ . characters";
        $alert_type = "danger";
        return;
    }
    if (!valid_len(8, 15, $password))
    {
        $alert_msg = "Password must be between 8 and 15 characters long";
        $alert_type = "danger";
        return;
    }
    if (!valid_regex("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/", $password))
    {
        $alert_msg = "Password must contain at least 1 capital letter and 1 digit";
        $alert_type = "danger";
        return;
    }
    if (!valid_equality($password, $password2))
    {
        $alert_msg = "Passwords do not match";
        $alert_type = "danger";
        return;
    }
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

function valid_equality($field1, $field2)
{
    return $field1 == $field2;
}

function valid_regex($pattern, $field)
{
    return preg_match($pattern, $field);
}

function valid_len($min, $max, $field)
{
    return (strlen($field) >= $min) && (strlen($field) <= $max);
}
