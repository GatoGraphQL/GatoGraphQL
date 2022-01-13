<?php

\PoP\Root\App::addAction(
    'popcms:init', 
    'popMailerAwsInitConstants', 
    10000
);
function popMailerAwsInitConstants()
{
    include_once 'constants.php';
}
