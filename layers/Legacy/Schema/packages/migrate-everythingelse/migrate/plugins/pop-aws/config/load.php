<?php

\PoP\Root\App::addAction(
    'popcms:init', 
    'popAwsInitConstants', 
    10000
);
function popAwsInitConstants()
{
    include_once 'constants.php';
}
