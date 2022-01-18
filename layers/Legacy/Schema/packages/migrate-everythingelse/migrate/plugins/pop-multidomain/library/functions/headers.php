<?php
use PoP\ComponentModel\State\ApplicationState;

// If we are on the external page, add a canonical link to the aggregated page
\PoP\Root\App::addAction('popcms:head', 'popMultidomainHeaders');
function popMultidomainHeaders()
{
    
    // Add the external URL's domain, only if we are on the External Page
    if (\PoP\Root\App::getState(['routing', 'is-generic']) && \PoP\Root\App::getState('route') == POP_MULTIDOMAIN_ROUTE_EXTERNAL) {
        if ($external_url = $_REQUEST[\PoP\ComponentModel\Constants\Response::URL] ?? null) {
            printf(
                '<link rel="canonical" href="%s">',
                $external_url
            );
        }
    }
}
