<?php
require('../bootstrap.php');

testErrorHandler(
    true,
    true,
    true,
    'sapi-prod-warning-json',
    function ($c) {
        return
            preg_match('#"status": "error"#i', $c)
            && preg_match('#"result": "!WARNING!"#i', $c)
            && preg_match('#"trace": "\#0#i', $c)
            ;
    },
    function () {
        trigger_error('!WARNING!', E_USER_WARNING);
    }
);
