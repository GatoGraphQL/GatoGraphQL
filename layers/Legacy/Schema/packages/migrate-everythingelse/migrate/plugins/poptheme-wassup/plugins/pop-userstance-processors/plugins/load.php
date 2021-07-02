<?php

//-------------------------------------------------------------------------------------
// Load Plugin-specific Libraries
//-------------------------------------------------------------------------------------

if (defined('POP_COMMONUSERROLESPROCESSORS_INITIALIZED')) {
    include_once 'pop-commonuserroles-processors/load.php';
}

if (defined('POP_BOOTSTRAPPROCESSORS_INITIALIZED')) {
    include_once 'pop-bootstrap-processors/load.php';
}

if (defined('POP_ENGINEWEBPLATFORM_INITIALIZED')) {
    include_once 'pop-engine-webplatform/load.php';
}
