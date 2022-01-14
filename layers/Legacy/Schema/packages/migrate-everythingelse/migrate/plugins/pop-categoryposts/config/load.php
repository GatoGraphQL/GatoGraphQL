<?php

require_once 'routes.php';

// High priority: allow the Theme and other plug-ins to set the values in advance.
\PoP\Root\App::addAction(
    'init', // Must migrate this WP hook to one from PoP (which executes before AFTER_BOOT_APPLICATION
    'popCategorypostsInitConstants', 
    10000
);
function popCategorypostsInitConstants()
{
    include_once 'constants.php';
}
