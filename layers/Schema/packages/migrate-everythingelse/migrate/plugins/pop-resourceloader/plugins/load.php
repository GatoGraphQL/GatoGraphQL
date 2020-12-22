<?php

//-------------------------------------------------------------------------------------
// Load Plugin-specific Libraries
//-------------------------------------------------------------------------------------

require_once 'pop-engine-webplatform/load.php';

if (defined('POP_SSR_INITIALIZED')) {
    include_once 'pop-ssr/load.php';
}

if (defined('POP_SYSTEM_INITIALIZED')) {
    include_once 'pop-system/load.php';
}

if (defined('POP_CSSCONVERTER_INITIALIZED')) {
    include_once 'pop-cssconverter/load.php';
}

if (defined('POP_CLUSTER_INITIALIZED')) {
    include_once 'pop-cluster/load.php';
}
