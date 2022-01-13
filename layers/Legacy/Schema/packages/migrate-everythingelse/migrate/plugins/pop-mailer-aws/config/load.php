<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

HooksAPIFacade::getInstance()->addAction(
    'popcms:init', 
    'popMailerAwsInitConstants', 
    10000
);
function popMailerAwsInitConstants()
{
    include_once 'constants.php';
}
