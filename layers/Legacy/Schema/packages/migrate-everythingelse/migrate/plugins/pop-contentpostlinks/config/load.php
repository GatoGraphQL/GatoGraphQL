<?php

require_once 'routes.php';

// High priority: allow the Theme and other plug-ins to set the values in advance.
\PoP\Root\App::getHookManager()->addAction(
    'popcms:init', 
    'popContentpostlinksInitConstants', 
    10000
);
function popContentpostlinksInitConstants()
{
    include_once 'constants.php';
}
