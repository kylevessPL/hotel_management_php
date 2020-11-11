<?php

function subview($file) {
    $file = __DIR__.'/../views/sub-views/'.$file;
    include $file;
}