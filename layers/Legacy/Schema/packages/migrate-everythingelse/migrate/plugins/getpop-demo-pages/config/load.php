<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

/**
 * Website Implementations
 */
// High priority: allow the Theme and other plug-ins to set the values in advance.
HooksAPIFacade::getInstance()->addAction(
    'popcms:init', 
    'getpopdemoPopprocessorsInitConstants', 
    10000
);
function getpopdemoPopprocessorsInitConstants()
{
    include_once 'constants.php';
}
