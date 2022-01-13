<?php

// High priority: allow the Theme and other plug-ins to set the values in advance.
\PoP\Root\App::addAction(
    'popcms:init', 
    'popCommonuserrolesprocessorsInitConstants', 
    10000
);
function popCommonuserrolesprocessorsInitConstants()
{
    include_once 'constants.php';
}
