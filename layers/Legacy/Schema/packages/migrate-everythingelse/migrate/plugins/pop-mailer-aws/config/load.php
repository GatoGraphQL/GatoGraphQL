<?php

\PoP\Root\App::addAction(
    'init', // Must migrate this WP hook to one from PoP (which executes before AFTER_BOOT_APPLICATION
    'popMailerAwsInitConstants', 
    10000
);
function popMailerAwsInitConstants()
{
    include_once 'constants.php';
}
