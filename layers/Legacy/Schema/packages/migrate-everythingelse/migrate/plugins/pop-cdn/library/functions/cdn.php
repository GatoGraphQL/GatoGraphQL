<?php

/**
 * CDN URLs
 */

\PoP\Root\App::getHookManager()->addFilter('pop_modulemanager:allowed_domains', 'popCdnAllowedurls');
function popCdnAllowedurls($allowed_domains)
{

    // Add the Assets and Uploads CDN URLs as long as they were defined
    $allowed_domains = array_merge(
        $allowed_domains,
        array_filter(
            array(
                POP_CDNFOUNDATION_CDN_ASSETS_URI,
                POP_CDNFOUNDATION_CDN_UPLOADS_URI,
                POP_CDNFOUNDATION_CDN_CONTENT_URI,
            )
        )
    );

    return $allowed_domains;
}
