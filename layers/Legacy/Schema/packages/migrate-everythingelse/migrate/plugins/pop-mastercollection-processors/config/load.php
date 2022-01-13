<?php

// High priority: allow the Theme and other plug-ins to set the values in advance.
\PoP\Root\App::addAction(
    'popcms:init', 
    'popMastercollectionprocessorsInitConstants', 
    10000
);
function popMastercollectionprocessorsInitConstants()
{
    include_once 'constants.php';
}
