<?php

// Wait until the constants have been set
\PoP\Root\App::addAction(
    'popcms:init', 
    'popCommonpagesContentcreationNotificationsInitConstants', 
    5100
);
function popCommonpagesContentcreationNotificationsInitConstants()
{
    include_once 'config.php';
}
