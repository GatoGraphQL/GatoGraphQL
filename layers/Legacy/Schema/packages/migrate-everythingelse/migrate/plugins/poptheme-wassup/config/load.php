<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

include_once 'routes.php';

// High priority: allow the Theme and other plug-ins to set the values in advance.
HooksAPIFacade::getInstance()->addAction(
    'popcms:init', 
    'popthemeWassupInitConstants', 
    10000
);
function popthemeWassupInitConstants()
{
    include_once 'constants.php';
}
