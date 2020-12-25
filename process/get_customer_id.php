<?php
include_once dirname(__DIR__).'/helpers/conn.php';
include_once dirname(__DIR__).'/helpers/init_session.php';

function get_customer_id(&$alert_msg, &$alert_type, &$customer_id)
{
    $sql = "SELECT customer_id FROM users WHERE id = '".$_SESSION['user_id']."'";
    $result = query($sql);
    $customer_id = mysqli_fetch_assoc($result)['customer_id'];
    if (is_null($customer_id))
    {
        $alert_msg = "You have to complete your personal data first to unlock all site features";
        $alert_type = "info";
    }
}
