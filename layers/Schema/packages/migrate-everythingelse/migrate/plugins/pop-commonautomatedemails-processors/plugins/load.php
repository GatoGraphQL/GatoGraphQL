<?php

//-------------------------------------------------------------------------------------
// Load Plugin-specific Libraries
//-------------------------------------------------------------------------------------

if (defined('POP_EVENTS_INITIALIZED')) {
    include_once 'pop-events/load.php';
}

if (defined('POP_USERPLATFORM_INITIALIZED')) {
    include_once 'pop-userplatform/load.php';
}
if (defined('POP_GFPOPGENERICFORMS_INITIALIZED')) {
    include_once 'gravityforms-pop-genericforms/load.php';
}
