<?php

// High priority: allow the Theme and other plug-ins to set the values in advance.
\PoP\Root\App::getHookManager()->addAction(
    'popcms:init', 
    'popthemeWassupWebPlatformInitConstants', 
    10000
);
function popthemeWassupWebPlatformInitConstants()
{
    include_once 'constants.php';
}
