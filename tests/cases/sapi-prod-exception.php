<?php
require('../bootstrap.php');

testErrorHandler(
    true,
    false,
    false,
    'sapi-prod-exception',
    function ($c) {
        return
            preg_match('#<h1>Error</h1>#i', $c)
            && preg_match('#<p>Message: A critical error occurred. Please contact support</p>#i', $c)
            ;
    },
    function () {
        throw new Exception('!FAULT!');
    }
);
