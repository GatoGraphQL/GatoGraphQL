<?php

// High priority: allow the Theme and other plug-ins to set the values in advance.
\PoP\Root\App::addAction(
    'popcms:init', 
    'genericformsSocialnetworkGfInitConstants', 
    10000
);
function genericformsSocialnetworkGfInitConstants()
{
    include_once 'constants.php';
}
