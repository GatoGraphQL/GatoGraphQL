<?php

//-------------------------------------------------------------------------------------
// Load Plugin-specific Libraries
//-------------------------------------------------------------------------------------
    
if (defined('POP_SSR_INITIALIZED')) {
    include_once 'pop-ssr/load.php';
}

if (defined('POP_RESOURCELOADER_INITIALIZED')) {
    include_once 'pop-resourceloader/load.php';
}

if (defined('POP_CDN_INITIALIZED')) {
    include_once 'pop-cdn/load.php';
}

if (defined('POP_CSSCONVERTER_INITIALIZED')) {
    include_once 'pop-cssconverter/load.php';
}
