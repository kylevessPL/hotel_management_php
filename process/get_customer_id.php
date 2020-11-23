<?php
include_once dirname(__DIR__).'/helpers/conn.php';

function get_customer_id(&$alertMsg, &$alertType, &$customerId)
{
    $sql = "SELECT customer_id FROM users WHERE id = '".$_SESSION['user_id']."'";
    $result = query($sql);
    $customerId = mysqli_fetch_assoc($result)['customer_id'];
    if (is_null($customerId))
    {
        $alertMsg = "You have to complete your personal data first to unlock all site features";
        $alertType = "info";
    }
}
