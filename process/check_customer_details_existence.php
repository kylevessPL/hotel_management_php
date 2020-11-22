<?php
include_once dirname(__DIR__).'/helpers/conn.php';

$sql = "SELECT customer_id FROM users WHERE email = '" . $_SESSION['user_id'] . "'";
$result = query($sql);
if (mysqli_num_rows($result) == 0)
{
    $alertMsg = "You have to complete your personal data first to be able to book rooms";
    $alertType = "info";
}
