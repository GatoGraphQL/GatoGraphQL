<?php
use PoP\Hooks\Facades\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('PoP_Mailer_AWS_Engine:uploadToS3:configuration', 'popMailerClusterEmailConfiguration');
function popMailerClusterEmailConfiguration($configuration)
{
    $configuration['website'] = POP_WEBSITE;
    return $configuration;
}
