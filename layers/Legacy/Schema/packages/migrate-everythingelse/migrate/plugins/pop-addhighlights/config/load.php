<?php

require_once 'routes.php';

// Allow TPPDebate AR to translate the post_type to Spanish
\PoP\Root\App::getHookManager()->addAction(
    'popcms:init', 
    function() {
	    include_once 'config.php';
	}, 
    5
);