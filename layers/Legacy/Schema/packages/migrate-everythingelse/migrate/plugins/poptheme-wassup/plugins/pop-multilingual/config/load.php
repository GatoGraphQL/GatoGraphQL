<?php

// High priority: allow the Theme and other plug-ins to set the values in advance.
\PoP\Root\App::getHookManager()->addAction(
    'popcms:init', 
    'popthemeWassupQtransInitConstants', 
    10000
);
function popthemeWassupQtransInitConstants()
{
    include_once 'constants.php';
}
