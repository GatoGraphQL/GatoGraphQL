<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

/**
 * Website Implementations
 */
// High priority: allow the Theme and other plug-ins to set the values in advance.
\PoP\Root\App::getHookManager()->addAction(
    'popcms:init', 
    'getpopdemoPopprocessorsInitConstants', 
    10000
);
function getpopdemoPopprocessorsInitConstants()
{
    include_once 'constants.php';
}
