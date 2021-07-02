<?php

if (defined('POP_USERSTATE_INITIALIZED')) {
    include_once 'pop-userstate/load.php';
}

if (defined('POP_EVENTS_INITIALIZED')) {
    include_once 'pop-events/load.php';
}

if (defined('EMPOPEVENTS_INITIALIZED')) {
    include_once 'events-manager-pop-events/load.php';
}

if (defined('POP_USERPLATFORM_INITIALIZED')) {
    include_once 'pop-userplatform/load.php';
}
