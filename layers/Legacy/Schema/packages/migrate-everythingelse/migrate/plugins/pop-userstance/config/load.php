<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

require_once 'routes.php';

// Allow TPPDebate AR to translate the post_type to Spanish
HooksAPIFacade::getInstance()->addAction(
    'popcms:init', 
    function() {
	    include_once 'config.php';
	}, 
    5
);

// High priority: allow the Theme and other plug-ins to set the values in advance.
HooksAPIFacade::getInstance()->addAction(
    'popcms:init', 
    'popUserstanceInitConstants', 
    10000
);
function popUserstanceInitConstants()
{
    include_once 'constants.php';
}
