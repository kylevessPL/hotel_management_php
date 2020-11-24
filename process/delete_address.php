<?php
include_once dirname(__DIR__).'/helpers/conn.php';

if (isset($_GET['address_id']))
{
    $id = escape_string($_GET['address_id']);
    $sql = "SELECT customer_id FROM customers_addresses where address_id = '$id'";
    $result = query($sql);
    if (mysqli_num_rows($result) > 0)
    {
        $customerId = mysqli_fetch_assoc($result)['customer_id'];
        $sql = "SELECT id FROM users where customer_id = '$customerId'";
        $result = query($sql);
        $userId = mysqli_fetch_assoc($result)['id'];
        if ($userId == $_SESSION['user_id'])
        {
            $sql = "DELETE FROM addresses where id = '$id'";
            query($sql);
        }
    }
}
header("location:/account/my-addresses");
