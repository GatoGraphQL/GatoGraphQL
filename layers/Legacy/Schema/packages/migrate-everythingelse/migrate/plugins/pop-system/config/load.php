<?php

require_once 'routes.php';

// High priority: allow the Theme and other plug-ins to set the values in advance.
\PoP\Root\App::addAction(
    'popcms:init', 
    'popSystemInitConstants', 
    10000
);
function popSystemInitConstants()
{
    include_once 'constants.php';
}
