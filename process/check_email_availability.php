<?php
include_once dirname(__DIR__).'/helpers/conn.php';

if (!empty($_REQUEST['email']))
{
    $email = mysqli_real_escape_string($con, $_REQUEST['email']);
    $sql = "SELECT id FROM users WHERE email = '$email'";
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
