<?php
require('../bootstrap.php');

testErrorHandler(
    false,
    true,
    false,
    'cli-debug-exception',
    function ($c) {
        return
            preg_match('#\| UNHANDLED EXCEPTION \|#i', $c)
            && preg_match('#Uncaught exception \'Exception\'  with message \'!FAULT!\'#i', $c)
            ;
    },
    function () {
        throw new \Exception('!FAULT!');
    }
);
