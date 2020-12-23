<?php

function validate_reg_fields(array $data, &$alertMsg, &$alertType)
{
    if(!required_fields($data))
    {
        $alertMsg = "All fields are required";
        $alertType = "danger";
    }
    $username = $data["username"];
    $password = $data["password"];
    $password2 = $data["password2"];
    $email = $data["email"];
    if(!valid_len(6, 16, $username))
    {
        $alertMsg = "Username must be between 6 and 16 characters long";
        $alertType = "danger";
    }
    if(!valid_regex("/^[a-zA-Z0-9.-_]*$/", $username))
    {
        $alertMsg = "Username must may contain only letters, digits or - _ . characters";
        $alertType = "danger";
    }
    if(!valid_len(8, 15, $password))
    {
        $alertMsg = "Password must be between 8 and 15 characters long";
        $alertType = "danger";
    }
    if(!valid_regex("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/", $password))
    {
        $alertMsg = "Password must contain at least 1 capital letter and 1 digit";
        $alertType = "danger";
    }
    if(!valid_equality($password, $password2))
    {
        $alertMsg = "Passwords do not match";
        $alertType = "danger";
    }
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
