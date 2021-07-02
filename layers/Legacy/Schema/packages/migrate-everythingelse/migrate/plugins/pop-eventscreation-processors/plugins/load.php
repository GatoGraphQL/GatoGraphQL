<?php

//-------------------------------------------------------------------------------------
// Load Plugin-specific Libraries
//-------------------------------------------------------------------------------------

if (defined('POP_APPLICATIONPROCESSORS_INITIALIZED')) {
    include_once 'pop-application-processors/load.php';
}

if (defined('POP_USERPLATFORMPROCESSORS_INITIALIZED')) {
    include_once 'pop-userplatform-processors/load.php';
}

if (defined('POP_ADDRELATEDPOSTSPROCESSORS_INITIALIZED')) {
    include_once 'pop-addrelatedposts-processors/load.php';
}
