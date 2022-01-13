<?php

// Wait until the constants have been set
\PoP\Root\App::addAction(
    'popcms:init', 
    'popCommonpagesAddcommentsNotificationsInitConstants', 
    5100
);
function popCommonpagesAddcommentsNotificationsInitConstants()
{
    include_once 'config.php';
}
