<?php
use PoP\Hooks\Facades\HooksAPIFacade;

// High priority: allow the Theme and other plug-ins to set the values in advance.
HooksAPIFacade::getInstance()->addAction(
    'popcms:init', 
    'avatarPopInitConstants', 
    10000
);
function avatarPopInitConstants()
{
    include_once 'constants.php';
}
