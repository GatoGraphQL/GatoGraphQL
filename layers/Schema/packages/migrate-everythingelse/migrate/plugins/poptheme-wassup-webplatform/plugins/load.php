<?php

//-------------------------------------------------------------------------------------
// Load Plugin-specific Libraries
//-------------------------------------------------------------------------------------
    
// if (defined('POP_THEME_INITIALIZED')) {
require_once 'pop-theme/load.php';
// }

if (defined('POP_SSR_INITIALIZED')) {
    include_once 'pop-ssr/load.php';
}

if (defined('POP_EVENTSWEBPLATFORM_INITIALIZED')) {
    include_once 'pop-events-webplatform/load.php';
}

if (defined('POP_LOCATIONSWEBPLATFORM_INITIALIZED')) {
    include_once 'pop-locations-webplatform/load.php';
}

if (defined('POP_BOOTSTRAPWEBPLATFORM_INITIALIZED')) {
    include_once 'pop-bootstrap-webplatform/load.php';
}

if (defined('POP_MULTIDOMAIN_INITIALIZED')) {
    include_once 'pop-multidomain/load.php';
}

if (defined('POP_NOTIFICATIONSWEBPLATFORM_INITIALIZED')) {
    include_once 'pop-notifications-webplatform/load.php';
}

if (defined('POP_RESOURCELOADER_INITIALIZED')) {
    include_once 'pop-resourceloader/load.php';
}

if (defined('POP_CSSCONVERTER_INITIALIZED')) {
    include_once 'pop-cssconverter/load.php';
}

if (defined('POP_SYSTEM_INITIALIZED')) {
    include_once 'pop-system/load.php';
}

if (defined('POP_CLUSTERCOMMONPAGES_INITIALIZED')) {
    include_once 'pop-clustercommonpages/load.php';
}

if (defined('POP_CONTENTPOSTLINKSWEBPLATFORM_INITIALIZED')) {
    include_once 'pop-contentpostlinks-webplatform/load.php';
}
