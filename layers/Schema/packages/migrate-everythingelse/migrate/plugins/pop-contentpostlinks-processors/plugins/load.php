<?php

//-------------------------------------------------------------------------------------
// Load Plugin-specific Libraries
//-------------------------------------------------------------------------------------

if (defined('POP_BOOTSTRAPPROCESSORS_INITIALIZED')) {
    include_once 'pop-bootstrap-processors/load.php';
}

// if (defined('POP_APPLICATIONPROCESSORS_INITIALIZED')) {
//     require_once 'pop-application-processors/load.php';
// }

if (defined('POP_CATEGORYPOSTSPROCESSORS_INITIALIZED')) {
    include_once 'pop-categoryposts-processors/load.php';
}
if (defined('POP_NOSEARCHCATEGORYPOSTSPROCESSORS_INITIALIZED')) {
    include_once 'pop-nosearchcategoryposts-processors/load.php';
}
