<?php

//-------------------------------------------------------------------------------------
// Load Plugin-specific Libraries
//-------------------------------------------------------------------------------------

if (defined('POP_THEME_INITIALIZED')) {
    include_once 'pop-theme/load.php';
}

if (defined('POP_USERSTATE_INITIALIZED')) {
    include_once 'pop-userstate/load.php';
}

if (defined('POP_RESOURCELOADER_INITIALIZED')) {
    include_once 'pop-resourceloader/load.php';
}

if (defined('POP_ENGINEWEBPLATFORMOPTIMIZATIONS_INITIALIZED')) {
    include_once 'pop-engine-webplatform-optimizations/load.php';
}

if (defined('POP_MULTILINGUAL_INITIALIZED')) {
    include_once 'pop-multilingual/load.php';
}

if (defined('POP_CLUSTER_INITIALIZED')) {
    include_once 'pop-cluster/load.php';
}

if (defined('POP_CDNFOUNDATION_INITIALIZED')) {
    include_once 'pop-cdn-foundation/load.php';
}

if (defined('POP_CDN_INITIALIZED')) {
    include_once 'pop-cdn/load.php';
}

if (defined('POP_MULTIDOMAIN_INITIALIZED')) {
    include_once 'pop-multidomain/load.php';
}

if (defined('POP_SYSTEM_INITIALIZED')) {
    include_once 'pop-system/load.php';
}
