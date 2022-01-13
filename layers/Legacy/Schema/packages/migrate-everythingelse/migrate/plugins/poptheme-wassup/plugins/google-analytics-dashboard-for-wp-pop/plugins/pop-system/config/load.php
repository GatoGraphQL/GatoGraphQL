<?php

// High priority: allow the Theme and other plug-ins to set the values in advance.
\PoP\Root\App::addAction(
    'popcms:init', 
    'popthemeWassupGadwpInitConstants', 
    10000
);
function popthemeWassupGadwpInitConstants()
{
    include_once 'constants.php';
}
