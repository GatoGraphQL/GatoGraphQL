<?php

require_once 'routes.php';

// Allow TPPDebate AR to translate the post_type to Spanish
\PoP\Root\App::addAction(
    'popcms:init', 
    function() {
	    include_once 'config.php';
	}, 
    5
);

// High priority: allow the Theme and other plug-ins to set the values in advance.
\PoP\Root\App::addAction(
    'init', // Must migrate this WP hook to one from PoP (which executes before AFTER_BOOT_APPLICATION
    'popUserstanceInitConstants', 
    10000
);
function popUserstanceInitConstants()
{
    include_once 'constants.php';
}
