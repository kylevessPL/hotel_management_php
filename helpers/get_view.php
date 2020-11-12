<?php

function view($file) {
    $file = __DIR__.'/../views/'.$file;
    include $file;
}

