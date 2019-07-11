<?php
require('../bootstrap.php');

testErrorHandler(
    true,
    false,
    false,
    'sapi-prod-notice',
    function ($c) {
        return $c === '';
    },
    function () {
        trigger_error('!NOTICE!', E_USER_NOTICE);
    },
    false
);
