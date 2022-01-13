<?php

\PoP\Root\App::getHookManager()->addFilter('PoP_Mailer_AWS_Engine:uploadToS3:configuration', 'popthemeWassupEmailConfiguration');
function popthemeWassupEmailConfiguration($configuration)
{
    $logo = gdLogo();
    $configuration['images']['logo'] = $logo[0];
    return $configuration;
}
