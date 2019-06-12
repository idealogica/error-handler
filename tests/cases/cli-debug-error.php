<?php
require('../bootstrap.php');

testErrorHandler(
    false,
    true,
    false,
    'cli-debug-error',
    function ($c) {
        return
            preg_match('#\| FATAL ERROR \|#i', $c)
            && preg_match('#\!FATAL\!#i', $c)
            ;
    },
    function () {
        trigger_error('!FATAL!', E_USER_ERROR);
    }
);
