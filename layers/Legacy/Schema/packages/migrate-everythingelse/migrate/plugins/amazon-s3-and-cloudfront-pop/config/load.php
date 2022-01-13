<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

// High priority: allow the Theme and other plug-ins to set the values in advance.
HooksAPIFacade::getInstance()->addAction(
    'popcms:init', 
    'awss3cfpop_init_constants', 
    10000
);
function awss3cfpop_init_constants()
{
    include_once 'constants.php';
}
