<?php
use PoP\Hooks\Facades\HooksAPIFacade;

HooksAPIFacade::getInstance()->addAction(
    'popcms:init', 
    'popAvatarAwsInitConstants', 
    10000
);
function popAvatarAwsInitConstants()
{
    include_once 'constants.php';
    include_once 'config.php';
}
