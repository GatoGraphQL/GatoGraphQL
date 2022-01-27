<?php

// Wait until the constants have been set
\PoP\Root\App::addAction(
    'init', // Must migrate this WP hook to one from PoP (which executes before AFTER_BOOT_APPLICATION
    'popCommonpagesContentcreationNotificationsInitConstants', 
    5100
);
function popCommonpagesContentcreationNotificationsInitConstants()
{
    include_once 'config.php';
}
