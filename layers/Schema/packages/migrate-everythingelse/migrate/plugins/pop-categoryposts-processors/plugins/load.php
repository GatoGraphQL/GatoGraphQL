<?php

//-------------------------------------------------------------------------------------
// Load Plugin-specific Libraries
//-------------------------------------------------------------------------------------

if (defined('POP_BOOTSTRAPPROCESSORS_INITIALIZED')) {
    include_once 'pop-bootstrap-processors/load.php';
}

if (defined('POP_BLOGPROCESSORS_INITIALIZED')) {
    include_once 'pop-blog-processors/load.php';
}

// if (defined('POP_APPLICATIONPROCESSORS_INITIALIZED')) {
//     require_once 'pop-application-processors/load.php';
// }
