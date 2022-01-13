<?php

// High priority: allow the Theme and other plug-ins to set the values in advance.
\PoP\Root\App::addAction(
    'popcms:init', 
    'genericformsContentcreationGfInitConstants', 
    10000
);
function genericformsContentcreationGfInitConstants()
{
    include_once 'constants.php';
}
