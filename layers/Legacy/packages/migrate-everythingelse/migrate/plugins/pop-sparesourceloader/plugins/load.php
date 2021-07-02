<?php

//-------------------------------------------------------------------------------------
// Load Plugin-specific Libraries
//-------------------------------------------------------------------------------------

require_once 'pop-engine-webplatform/load.php';
require_once 'pop-resourceloader/load.php';

if (defined('POP_SSR_INITIALIZED')) {
    include_once 'pop-ssr/load.php';
}
