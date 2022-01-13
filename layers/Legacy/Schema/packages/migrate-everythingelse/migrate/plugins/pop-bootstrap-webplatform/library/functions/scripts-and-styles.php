<?php

/**
 * preloading fonts
 */
$cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
if (!$cmsapplicationapi->isAdminPanel()) {
    \PoP\Root\App::getHookManager()->addAction('popcms:head', 'popBootstrapwebplatformPreloadfonts');
}
function popBootstrapwebplatformPreloadfonts()
{
    printf(
        '<link rel="preload" href="%s" as "font" crossorigin type="font/woff2">',
        getBootstrapFontUrl()
    );
}
function getBootstrapFontUrl($pathkey = null)
{
    if (!$pathkey) {
        $pathkey = 'local';
        if (PoP_WebPlatform_ServerUtils::accessExternalcdnResources()) {
            $pathkey = 'external';
        }

        // Allow PoP Resource Loader to hook in its values
        $pathkey = \PoP\Root\App::getHookManager()->applyFilters(
            'getBootstrapFontUrl:pathkey',
            $pathkey
        );
    }

    return getBootstrapFontPath($pathkey).'/fonts/glyphicons-halflings-regular.woff2';
}
function getBootstrapFontPath($pathkey)
{
    $local_font_path = POP_BOOTSTRAPWEBPLATFORM_URL.'/css/includes';
    if ($pathkey == 'local') {
        return $local_font_path;
    } elseif ($pathkey == 'external') {
        return 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7';
    }
    
    // Allow PoP Resource Loader to hook in its values
    return \PoP\Root\App::getHookManager()->applyFilters(
        'getBootstrapFontPath',
        $local_font_path,
        $pathkey
    );
}
