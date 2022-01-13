<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

require_once 'routes.php';

// High priority: allow the Theme and other plug-ins to set the values in advance.
HooksAPIFacade::getInstance()->addAction(
    'popcms:init', 
    'popCategorypostsInitConstants', 
    10000
);
function popCategorypostsInitConstants()
{
    include_once 'constants.php';
}
