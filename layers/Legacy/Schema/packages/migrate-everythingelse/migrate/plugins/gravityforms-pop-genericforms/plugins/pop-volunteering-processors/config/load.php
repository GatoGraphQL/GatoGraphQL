<?php

// High priority: allow the Theme and other plug-ins to set the values in advance.
\PoP\Root\App::getHookManager()->addAction(
    'popcms:init', 
    'genericformsVolunteeringGfInitConstants', 
    10000
);
function genericformsVolunteeringGfInitConstants()
{
    include_once 'constants.php';
}
