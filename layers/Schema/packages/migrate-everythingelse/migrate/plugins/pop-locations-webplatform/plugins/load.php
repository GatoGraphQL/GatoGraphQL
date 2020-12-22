<?php

if (defined('POP_CDN_INITIALIZED')) {
    include_once 'pop-cdn/load.php';
}

if (defined('POP_BOOTSTRAPWEBPLATFORM_INITIALIZED')) {
    include_once 'pop-bootstrap-webplatform/load.php';
}

if (defined('POP_SSR_INITIALIZED')) {
    include_once 'pop-ssr/load.php';
}

if (defined('POP_RESOURCELOADER_INITIALIZED')) {
    include_once 'pop-resourceloader/load.php';
}
