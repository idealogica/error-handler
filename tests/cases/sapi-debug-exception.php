<?php
require('../bootstrap.php');

testErrorHandler(
    true,
    true,
    false,
    'sapi-debug-exception',
    function ($c) {
        return
            preg_match('#<h1>Error</h1>#i', $c)
            && preg_match('#<p>Message: !FAULT!</p>#i', $c)
            ;
    },
    function () {
        throw new \Exception('!FAULT!');
    },
    true
);
