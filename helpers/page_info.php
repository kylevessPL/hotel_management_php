<?php
$page = $_SERVER['SCRIPT_FILENAME'];
$title = ucfirst(str_replace("_"," ", basename($page, '.'.pathinfo($page)['extension'])));
if(strlen($title) < 5)
{
    $title = strtoupper($title);
}
$path = pathinfo($_SERVER['REQUEST_URI'])['dirname'];
