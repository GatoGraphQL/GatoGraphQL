<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

require_once 'routes.php';

// Allow TPPDebate AR to translate the post_type to Spanish
\PoP\Root\App::getHookManager()->addAction(
    'popcms:init', 
    function() {
	    include_once 'config.php';
	}, 
    5
);

// High priority: allow the Theme and other plug-ins to set the values in advance.
\PoP\Root\App::getHookManager()->addAction(
    'popcms:init', 
    'popUserstanceInitConstants', 
    10000
);
function popUserstanceInitConstants()
{
    include_once 'constants.php';
}
