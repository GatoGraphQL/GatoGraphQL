<?php
use PoP\Hooks\Facades\HooksAPIFacade;

// High priority: allow the Theme and other plug-ins to set the values in advance.
HooksAPIFacade::getInstance()->addAction(
    'popcms:init', 
    'popEventlinksInitConstants', 
    10000
);
function popEventlinksInitConstants()
{
    include_once 'constants.php';
}
