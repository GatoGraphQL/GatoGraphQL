<?php

if (defined('POP_USERSTATE_INITIALIZED')) {
    include_once 'pop-userstate/load.php';
}

if (defined('POP_SOCIALNETWORK_INITIALIZED')) {
    include_once 'pop-socialnetwork/load.php';
}

if (defined('POP_APPLICATION_INITIALIZED')) {
    include_once 'pop-application/load.php';
}

if (defined('POP_NOTIFICATIONS_INITIALIZED')) {
    include_once 'pop-notifications/load.php';
}

if (defined('POP_USERROLES_INITIALIZED')) {
    include_once 'pop-userroles/load.php';
}
