<?php

\PoP\Root\App::getHookManager()->addAction(
    'popcms:init', 
    'popAwsInitConstants', 
    10000
);
function popAwsInitConstants()
{
    include_once 'constants.php';
}
