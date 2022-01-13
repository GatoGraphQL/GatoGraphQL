<?php

require_once 'routes.php';

// High priority: allow the Theme and other plug-ins to set the values in advance.
\PoP\Root\App::getHookManager()->addAction(
    'popcms:init', 
    'popContactusInitConstants', 
    10000
);
function popContactusInitConstants()
{
    include_once 'constants.php';
}
