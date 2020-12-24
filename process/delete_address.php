<?php
include_once dirname(__DIR__).'/helpers/conn.php';
include_once dirname(__DIR__).'/helpers/error_handler.php';
include_once dirname(__DIR__).'/helpers/init_session.php';
include_once dirname(__DIR__).'/process/get_customer_id.php';

get_customer_id($alert_msg, $alert_type, $customer_id);

if (!isset($_GET['id']))
{
    return;
}

$id = escape_string($_GET['id']);

check_customer_address_ownership($id, $customer_id);

autocommit(false);

try
{
    delete_address($id);
    commit_transaction();
    autocommit();
}
catch (Throwable $e)
{
    rollback_transaction();
    autocommit();
}

header("location:/account/my-addresses");

function check_customer_address_ownership(string $id, $customer_id)
{
    $sql = "SELECT id FROM customers_addresses where address_id = '$id' AND customer_id = '$customer_id'";
    $result = query($sql);

    if (mysqli_num_rows($result) == 0)
    {
        http_response_code(403);
        die();
    }
}

function delete_address(string $id)
{
    $sql = "DELETE FROM customers_addresses where address_id = '$id'";
    query($sql);
    if (!query($sql))
    {
        throw new Exception(dbException());
    }
    $sql = "DELETE FROM addresses where id = '$id'";
    if (!query($sql))
    {
        throw new Exception(dbException());
    }
}
