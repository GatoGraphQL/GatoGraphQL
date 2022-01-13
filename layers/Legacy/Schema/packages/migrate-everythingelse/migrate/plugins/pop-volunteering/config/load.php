<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

require_once 'routes.php';

// High priority: allow the Theme and other plug-ins to set the values in advance.
\PoP\Root\App::getHookManager()->addAction(
    'popcms:init', 
    'popVolunteeringInitConstants', 
    10000
);
function popVolunteeringInitConstants()
{
    include_once 'constants.php';
}
