<?php

//-------------------------------------------------------------------------------------
// Load Plugin-specific Libraries
//-------------------------------------------------------------------------------------

if (defined('POP_EVENTS_INITIALIZED')) {
    include_once 'pop-events/load.php';
}

if (defined('POP_NOTIFICATIONSPROCESSORS_INITIALIZED')) {
    include_once 'pop-notifications-processors/load.php';
}
