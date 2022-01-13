<?php

// High priority: allow the Theme and other plug-ins to set the values in advance.
\PoP\Root\App::addAction(
    'popcms:init', 
    'popthemeWassupGfInitConstants', 
    10000
);
function popthemeWassupGfInitConstants()
{
    include_once 'constants.php';
}
