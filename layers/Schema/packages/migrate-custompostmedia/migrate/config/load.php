<?php
use PoP\Hooks\Facades\HooksAPIFacade;

// High priority: allow the Theme and other plug-ins to set the values in advance.
HooksAPIFacade::getInstance()->addAction(
    'popcms:init', 
    'popApplicationInitConstants', 
    10000
);
function popApplicationInitConstants()
{
    include_once 'constants.php';
}
