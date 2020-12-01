<?php
include_once dirname(__DIR__).'/helpers/conn.php';

if (!empty($_GET['username']))
{
    $username = escape_string($_GET['username']);
    $sql = "SELECT id FROM users WHERE username = '$username'";
    $result = query($sql);
    if(mysqli_num_rows($result) > 0)
    {
        echo 'false';
    }
    else
    {
        echo 'true';
    }
}
else
{
    echo 'false';
}