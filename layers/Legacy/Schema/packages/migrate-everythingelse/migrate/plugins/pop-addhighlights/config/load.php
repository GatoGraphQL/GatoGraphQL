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