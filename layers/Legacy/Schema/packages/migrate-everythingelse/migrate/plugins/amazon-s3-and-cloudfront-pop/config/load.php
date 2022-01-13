<?php

// High priority: allow the Theme and other plug-ins to set the values in advance.
\PoP\Root\App::getHookManager()->addAction(
    'popcms:init', 
    'awss3cfpop_init_constants', 
    10000
);
function awss3cfpop_init_constants()
{
    include_once 'constants.php';
}
