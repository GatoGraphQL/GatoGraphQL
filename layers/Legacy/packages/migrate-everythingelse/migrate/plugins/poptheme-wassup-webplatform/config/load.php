<?php
use PoP\Hooks\Facades\HooksAPIFacade;

// High priority: allow the Theme and other plug-ins to set the values in advance.
HooksAPIFacade::getInstance()->addAction(
    'popcms:init', 
    'popthemeWassupWebPlatformInitConstants', 
    10000
);
function popthemeWassupWebPlatformInitConstants()
{
    include_once 'constants.php';
}
