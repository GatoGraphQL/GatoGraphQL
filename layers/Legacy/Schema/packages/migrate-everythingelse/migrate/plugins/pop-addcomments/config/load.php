<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

require_once 'routes.php';

// High priority: allow the Theme and other plug-ins to set the values in advance.
HooksAPIFacade::getInstance()->addAction(
    'popcms:init', 
    'popAddcommentsInitConstants', 
    10000
);
function popAddcommentsInitConstants()
{
    include_once 'constants.php';
}
