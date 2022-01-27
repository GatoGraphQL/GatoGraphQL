<?php

\PoP\Root\App::addAction(
    'init', // Must migrate this WP hook to one from PoP (which executes before AFTER_BOOT_APPLICATION
    'popAwsInitConstants', 
    10000
);
function popAwsInitConstants()
{
    include_once 'constants.php';
}
