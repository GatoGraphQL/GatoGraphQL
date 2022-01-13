<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

// High priority: allow the Theme and other plug-ins to set the values in advance.
HooksAPIFacade::getInstance()->addAction(
    'popcms:init', 
    'genericformsContentcreationGfInitConstants', 
    10000
);
function genericformsContentcreationGfInitConstants()
{
    include_once 'constants.php';
}
