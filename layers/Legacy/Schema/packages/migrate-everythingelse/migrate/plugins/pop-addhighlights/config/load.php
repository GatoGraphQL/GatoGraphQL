<?php

require_once 'routes.php';

// Allow TPPDebate AR to translate the post_type to Spanish
\PoP\Root\App::addAction(
    'init', // Must migrate this WP hook to one from PoP (which executes before AFTER_BOOT_APPLICATION
    function() {
	    include_once 'config.php';
	}, 
    5
);