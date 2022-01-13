<?php

// High priority: allow the Theme and other plug-ins to set the values in advance.
\PoP\Root\App::addAction(
    'popcms:init', 
    'popTinymceInitConstants', 
    10000
);
function popTinymceInitConstants()
{
    include_once 'constants.php';
}
