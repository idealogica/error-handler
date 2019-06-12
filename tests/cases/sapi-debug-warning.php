<?php
require('../bootstrap.php');

testErrorHandler(
    true,
    true,
    false,
    'sapi-debug-warning',
    function ($c) {
        return
            preg_match('#<h1>Error</h1>#i', $c)
            && preg_match('#Message type: ErrorException#i', $c)
            && preg_match('#Trace: \#0#i', $c)
            ;
    },
    function () {
        trigger_error('!WARNING!', E_USER_WARNING);
    }
);
