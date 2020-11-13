<?php
global $con;
$con = mysqli_connect("localhost","root","","hotel");

if(mysqli_connect_errno()) {
    die(mysqli_connect_error());
}

function escape_string($str) {
    return mysqli_real_escape_string($GLOBALS['con'], $str);
}

function query($sql) {
    $result = mysqli_query($GLOBALS['con'], $sql);

    if (!$result) {
        die(mysqli_error($GLOBALS['con']));
    }

    return $result;
}
