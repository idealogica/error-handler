<?php
require('../bootstrap.php');

testErrorHandler(
    true,
    false,
    false,
    'sapi-prod-warning',
    function ($c) {
        return
            preg_match('#<h1>Error</h1>#i', $c)
            && preg_match('#<p>Message: A critical error occurred. Please contact support</p>#i', $c)
            ;
    },
    function () {
        trigger_error('!WARNING!', E_USER_WARNING);
    },
    true
);
