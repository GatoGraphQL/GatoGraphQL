<?php

//-------------------------------------------------------------------------------------
// Load Plugin-specific Libraries
//-------------------------------------------------------------------------------------

// if (defined('POP_USERAVATAR_INITIALIZED')) {
//     require_once 'pop-useravatar/load.php';
// }
if (defined('POP_USERPLATFORM_INITIALIZED')) {
    include_once 'pop-userplatform/load.php';
}
if (defined('POP_USERPLATFORMPROCESSORS_INITIALIZED')) {
    include_once 'pop-userplatform-processors/load.php';
}
