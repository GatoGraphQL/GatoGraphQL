<?php

require_once 'routes.php';

// High priority: allow the Theme and other plug-ins to set the values in advance.
\PoP\Root\App::addAction(
    'popcms:init', 
    'popEventsInitConstants', 
    10000
);
function popEventsInitConstants()
{
    include_once 'constants.php';
}
