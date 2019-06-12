<?php
require('../bootstrap.php');

testErrorHandler(
    false,
    false,
    false,
    'cli-prod-public',
    function ($c) {
        return
            preg_match('#\| UNHANDLED EXCEPTION \|#i', $c)
            && preg_match('#Uncaught exception \'InvalidArgumentException\'  with message \'!FAULT!\'#i', $c)
            ;
    },
    function () {
        throw new InvalidArgumentException('!FAULT!');
    }
);
