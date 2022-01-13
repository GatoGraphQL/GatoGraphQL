<?php

// High priority: allow the Theme and other plug-ins to set the values in advance.
\PoP\Root\App::addAction(
    'popcms:init', 
    'genericformsNewsletterGfInitConstants', 
    10000
);
function genericformsNewsletterGfInitConstants()
{
    include_once 'constants.php';
}
