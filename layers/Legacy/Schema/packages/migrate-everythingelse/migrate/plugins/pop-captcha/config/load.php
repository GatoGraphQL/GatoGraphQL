<?php
use PoP\Hooks\Facades\HooksAPIFacade;

// High priority: allow the Theme and other plug-ins to set the values in advance.
HooksAPIFacade::getInstance()->addAction(
    'popcms:init', 
    'popCaptchaInitConstants', 
    10000
);
function popCaptchaInitConstants()
{
    include_once 'constants.php';
}
