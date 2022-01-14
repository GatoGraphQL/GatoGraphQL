<?php

include_once 'routes.php';

// High priority: allow the Theme and other plug-ins to set the values in advance.
\PoP\Root\App::addAction(
    'init', // Must migrate this WP hook to one from PoP (which executes before AFTER_BOOT_APPLICATION
    'popthemeWassupInitConstants', 
    10000
);
function popthemeWassupInitConstants()
{
    include_once 'constants.php';
}
