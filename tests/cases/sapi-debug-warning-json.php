<?php
require('../bootstrap.php');

testErrorHandler(
    true,
    false,
    true,
    'sapi-debug-warning-json',
    function ($c) {
        return
            preg_match('#"status": "error"#i', $c)
            && preg_match('#"result": "A critical error occurred. Please contact support"#i', $c)
            ;
    },
    function () {
        trigger_error('!WARNING!', E_USER_WARNING);
    }
);
