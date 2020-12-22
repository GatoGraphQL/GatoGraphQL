<?php

if (defined('POP_APPLICATIONPROCESSORS_INITIALIZED')) {
    include_once 'pop-application-processors/load.php';
}

if (defined('POP_MASTERCOLLECTIONPROCESSORS_INITIALIZED')) {
    include_once 'pop-mastercollection-processors/load.php';
}

if (defined('POP_NOTIFICATIONSPROCESSORS_INITIALIZED')) {
    include_once 'pop-notifications-processors/load.php';
}

if (defined('POP_EVENTSPROCESSORS_INITIALIZED')) {
    include_once 'pop-events-processors/load.php';
}

if (defined('POP_BOOTSTRAPPROCESSORS_INITIALIZED')) {
    include_once 'pop-bootstrap-processors/load.php';
}
