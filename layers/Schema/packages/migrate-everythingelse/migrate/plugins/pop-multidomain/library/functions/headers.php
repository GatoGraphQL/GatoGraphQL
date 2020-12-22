<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\State\ApplicationState;

// If we are on the external page, add a canonical link to the aggregated page
HooksAPIFacade::getInstance()->addAction('popcms:head', 'popMultidomainHeaders');
function popMultidomainHeaders()
{
    $vars = ApplicationState::getVars();

    // Add the external URL's domain, only if we are on the External Page
    if ($vars['routing-state']['is-standard'] && $vars['route'] == POP_MULTIDOMAIN_ROUTE_EXTERNAL) {
        if ($external_url = $_REQUEST[GD_URLPARAM_URL] ?? null) {
            printf(
                '<link rel="canonical" href="%s">',
                $external_url
            );
        }
    }
}
