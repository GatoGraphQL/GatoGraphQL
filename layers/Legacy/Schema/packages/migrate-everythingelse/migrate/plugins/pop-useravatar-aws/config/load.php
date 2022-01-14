<?php

\PoP\Root\App::addAction(
    'init', // Must migrate this WP hook to one from PoP (which executes before AFTER_BOOT_APPLICATION
    'popUseravatarAwsInitConstants', 
    10000
);
function popUseravatarAwsInitConstants()
{
    include_once 'constants.php';
}
