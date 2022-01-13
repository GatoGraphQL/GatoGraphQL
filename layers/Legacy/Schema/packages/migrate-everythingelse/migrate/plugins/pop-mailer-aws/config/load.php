<?php

\PoP\Root\App::getHookManager()->addAction(
    'popcms:init', 
    'popMailerAwsInitConstants', 
    10000
);
function popMailerAwsInitConstants()
{
    include_once 'constants.php';
}
