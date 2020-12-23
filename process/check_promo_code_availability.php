<?php
include_once dirname(__DIR__).'/helpers/conn.php';

if (empty($_GET['promo-code']))
{
    echo 'false';
    return;
}

$code = escape_string($_GET['promo-code']);
$sql = "SELECT id FROM discounts WHERE code = '$code'";
$result = query($sql);
if(mysqli_num_rows($result) > 0)
{
    echo 'true';
}
else
{
    echo 'false';
}
