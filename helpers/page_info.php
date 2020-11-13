<?php
$page = $_SERVER['SCRIPT_FILENAME'];
$title = ucfirst(str_replace("_"," ", basename($page, '.'.pathinfo($page)['extension'])));
