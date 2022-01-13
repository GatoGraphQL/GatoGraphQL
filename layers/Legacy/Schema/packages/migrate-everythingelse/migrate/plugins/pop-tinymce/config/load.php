<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

// High priority: allow the Theme and other plug-ins to set the values in advance.
HooksAPIFacade::getInstance()->addAction(
    'popcms:init', 
    'popTinymceInitConstants', 
    10000
);
function popTinymceInitConstants()
{
    include_once 'constants.php';
}
