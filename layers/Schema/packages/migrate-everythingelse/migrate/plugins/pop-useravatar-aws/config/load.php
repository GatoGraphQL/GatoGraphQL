<?php
use PoP\Hooks\Facades\HooksAPIFacade;

HooksAPIFacade::getInstance()->addAction(
    'popcms:init', 
    'popUseravatarAwsInitConstants', 
    10000
);
function popUseravatarAwsInitConstants()
{
    include_once 'constants.php';
}
