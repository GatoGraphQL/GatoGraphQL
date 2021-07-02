<?php
use PoP\Hooks\Facades\HooksAPIFacade;

HooksAPIFacade::getInstance()->addAction(
    'popcms:init', 
    'popMailerAwsInitConstants', 
    10000
);
function popMailerAwsInitConstants()
{
    include_once 'constants.php';
}
