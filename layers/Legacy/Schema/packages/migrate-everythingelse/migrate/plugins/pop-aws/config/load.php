<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

HooksAPIFacade::getInstance()->addAction(
    'popcms:init', 
    'popAwsInitConstants', 
    10000
);
function popAwsInitConstants()
{
    include_once 'constants.php';
}
