<?php

// High priority: allow the Theme and other plug-ins to set the values in advance.
\PoP\Root\App::addAction(
    'popcms:init', 
    'popCaptchaInitConstants', 
    10000
);
function popCaptchaInitConstants()
{
    include_once 'constants.php';
}
