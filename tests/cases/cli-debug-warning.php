<?php
require('../bootstrap.php');

testErrorHandler(
    false,
    true,
    false,
    'cli-debug-warning',
    function ($c) {
        return
            preg_match('#\| WARNING \|#i', $c)
            && preg_match('#\!WARNING\!#i', $c)
            ;
    },
    function () {
        trigger_error('!WARNING!', E_USER_WARNING);
    }
);
