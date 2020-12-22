<?php

if (defined('POP_CDN_INITIALIZED')) {
    include_once 'pop-cdn/load.php';
}

if (defined('POP_SERVICEWORKERS_INITIALIZED')) {
    include_once 'pop-serviceworkers/load.php';
}

if (defined('POP_MULTILINGUAL_INITIALIZED')) {
    include_once 'pop-multilingual/load.php';
}

if (defined('POP_SSR_INITIALIZED')) {
    include_once 'pop-ssr/load.php';
}

if (defined('POP_RESOURCELOADER_INITIALIZED')) {
    include_once 'pop-resourceloader/load.php';
}
