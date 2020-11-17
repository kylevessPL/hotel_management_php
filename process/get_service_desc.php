<?php

include_once dirname(__DIR__).'/helpers/conn.php';


if (isset($_GET['id']))
{
    $id = escape_string($_GET['id']);
    $sql = "SELECT `desc` FROM additional_services WHERE id = '$id'";
    $result = query($sql);
    echo mysqli_fetch_row($result)['0'];
}
