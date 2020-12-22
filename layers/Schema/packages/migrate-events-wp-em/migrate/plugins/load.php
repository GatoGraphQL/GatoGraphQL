<?php

if (defined('POP_EVENTS_INITIALIZED')) {
    include_once 'pop-events/load.php';
}

if (defined('POP_USERPLATFORM_INITIALIZED')) {
    include_once 'pop-userplatform/load.php';
}
    
if (defined('POP_SOCIALNETWORK_INITIALIZED')) {
    include_once 'pop-socialnetwork/load.php';
}

if (defined('POP_COAUTHORS_INITIALIZED')) {
    include_once 'pop-coauthors/load.php';
}

if (defined('QTX_POP_INITIALIZED')) {
    include_once 'qtranslate-x-pop/load.php';
}
