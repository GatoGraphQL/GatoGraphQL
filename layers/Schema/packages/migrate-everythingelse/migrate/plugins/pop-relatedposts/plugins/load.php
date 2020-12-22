<?php

if (defined('POP_NOTIFICATIONS_INITIALIZED')) {
    include_once 'pop-notifications/load.php';
}

if (defined('POP_APPLICATION_INITIALIZED')) {
    include_once 'pop-application/load.php';
}

if (defined('ACF_POP_INITIALIZED')) {
    include_once 'advanced-custom-fields-pop/load.php';
}
