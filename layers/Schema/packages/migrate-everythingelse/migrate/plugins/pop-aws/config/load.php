<?php
use PoP\Hooks\Facades\HooksAPIFacade;

HooksAPIFacade::getInstance()->addAction(
    'popcms:init', 
    'popAwsInitConstants', 
    10000
);
function popAwsInitConstants()
{
    include_once 'constants.php';
}
