<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

\PoP\Root\App::getHookManager()->addAction(
    'popcms:init', 
    'popUseravatarAwsInitConstants', 
    10000
);
function popUseravatarAwsInitConstants()
{
    include_once 'constants.php';
}
