<?php
require('../bootstrap.php');

testErrorHandler(
    true,
    true,
    false,
    'sapi-debug-error',
    function ($c) {
        return
            preg_match('#<h1>Error</h1>#i', $c)
            && preg_match('#<p>Message: !FATAL!</p>#i', $c)
            && preg_match('#<p>Message type: ErrorException</p>#i', $c)
            ;
    },
    function () {
        trigger_error('!FATAL!', E_USER_ERROR);
    }
);
