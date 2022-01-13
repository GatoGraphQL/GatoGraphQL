<?php

// High priority: allow the Theme and other plug-ins to set the values in advance.
\PoP\Root\App::addAction(
    'popcms:init', 
    'avatarPopInitConstants', 
    10000
);
function avatarPopInitConstants()
{
    include_once 'constants.php';
}
