<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

// Wait until the constants have been set
HooksAPIFacade::getInstance()->addAction(
    'popcms:init', 
    'popCommonpagesContentcreationNotificationsInitConstants', 
    5100
);
function popCommonpagesContentcreationNotificationsInitConstants()
{
    include_once 'config.php';
}
