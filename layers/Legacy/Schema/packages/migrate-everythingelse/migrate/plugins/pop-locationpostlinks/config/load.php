<?php

// High priority: allow the Theme and other plug-ins to set the values in advance.
\PoP\Root\App::getHookManager()->addAction(
    'popcms:init', 
    'popLocationpostlinksInitConstants', 
    10000
);
function popLocationpostlinksInitConstants()
{
    include_once 'constants.php';
}
