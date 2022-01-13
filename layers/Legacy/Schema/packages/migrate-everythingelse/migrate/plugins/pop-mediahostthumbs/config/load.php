<?php

// High priority: allow the Theme and other plug-ins to set the values in advance.
\PoP\Root\App::addAction(
    'popcms:init', 
    'popMediahosthumbsInitConstants', 
    10000
);
function popMediahosthumbsInitConstants()
{
    include_once 'constants.php';
}
