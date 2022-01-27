<?php

\PoP\Root\App::addAction(
    'init', // Must migrate this WP hook to one from PoP (which executes before AFTER_BOOT_APPLICATION
    'popAvatarAwsInitConstants', 
    10000
);
function popAvatarAwsInitConstants()
{
    include_once 'constants.php';
    include_once 'config.php';
}
