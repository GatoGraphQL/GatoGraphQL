<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

require_once 'routes.php';

// High priority: allow the Theme and other plug-ins to set the values in advance.
\PoP\Root\App::getHookManager()->addAction(
    'popcms:init',
    'commonpagesInitConstants',
    5000
);
function commonpagesInitConstants()
{
    include_once 'constants.php';
}
