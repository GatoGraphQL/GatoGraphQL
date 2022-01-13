<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

// High priority: allow the Theme and other plug-ins to set the values in advance.
HooksAPIFacade::getInstance()->addAction(
    'popcms:init', 
    'genericformsSocialnetworkGfInitConstants', 
    10000
);
function genericformsSocialnetworkGfInitConstants()
{
    include_once 'constants.php';
}
