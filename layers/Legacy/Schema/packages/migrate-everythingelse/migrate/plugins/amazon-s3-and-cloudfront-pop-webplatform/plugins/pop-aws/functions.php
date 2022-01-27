<?php

//-------------------------------------------------------------------------------------
// Load Plugin-specific Libraries
//-------------------------------------------------------------------------------------

// Register the AWS S3 domain in the Allowed Domains list
\PoP\Root\App::addFilter('popAwss3Allowedurl:region', 'popAwsAwss3AllowedurlRegion');
function popAwsAwss3AllowedurlRegion($region)
{
    return POP_AWS_REGION;
}
