<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

// Wait until the constants have been set
HooksAPIFacade::getInstance()->addAction(
    'popcms:init', 
    'popCommonpagesAddcommentsNotificationsInitConstants', 
    5100
);
function popCommonpagesAddcommentsNotificationsInitConstants()
{
    include_once 'config.php';
}
