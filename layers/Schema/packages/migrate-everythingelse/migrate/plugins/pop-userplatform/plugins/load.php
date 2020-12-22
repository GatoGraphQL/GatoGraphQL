<?php

if (defined('POP_USERSTATE_INITIALIZED')) {
    include_once 'pop-userstate/load.php';
}

if (defined('POP_SYSTEM_INITIALIZED')) {
    include_once 'pop-system/load.php';
}

if (defined('POP_EMAILSENDER_INITIALIZED')) {
    include_once 'pop-emailsender/load.php';
}

if (defined('WPSC_POP_INITIALIZED')) {
    include_once 'wp-super-cache-pop/load.php';
}
