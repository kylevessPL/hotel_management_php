<?php
include_once dirname(__DIR__).'/helpers/conn.php';

if (empty($_GET['email']))
{
    echo 'false';
    return;
}

$email = escape_string($_GET['email']);
$sql = "SELECT id FROM users WHERE email = '$email'";
$result = query($sql);
if (mysqli_num_rows($result) > 0)
{
    echo 'false';
}
else
{
    echo 'true';
}
