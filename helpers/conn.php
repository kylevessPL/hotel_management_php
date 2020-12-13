<?php
global $con;
$con = mysqli_connect("localhost","root","","hotel");

if(mysqli_connect_errno())
{
    die(mysqli_connect_error());
}

function escape_string($str)
{
    return mysqli_real_escape_string($GLOBALS['con'], $str);
}

function query($sql)
{
    $result = mysqli_query($GLOBALS['con'], $sql);

    if (!$result) {
        die(mysqli_error($GLOBALS['con']));
    }

    return $result;
}

function insert_id()
{
    return mysqli_insert_id($GLOBALS['con']);
}

function dbException()
{
    return mysqli_error($GLOBALS['con']);
}

function autocommit($state = true)
{
    mysqli_autocommit($GLOBALS['con'], $state);
}

function commit_transaction()
{
    mysqli_commit($GLOBALS['con']);
}

function rollback_transaction()
{
    mysqli_rollback($GLOBALS['con']);
}
