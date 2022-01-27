<?php

/**
 * preloading fonts
 */
$cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
if (!$cmsapplicationapi->isAdminPanel()) {
    \PoP\Root\App::addAction('popcms:head', 'popHeaderWassupPreloadfonts');
}
function popHeaderWassupPreloadfonts()
{

    // Always preload the "woff2" font only, no need for the others: all browsers that support preload, also support woff2
    // Read on https://developers.google.com/web/fundamentals/performance/optimizing-content-efficiency/webfont-optimization#preload_your_webfont_resources
    $preload = '<link rel="preload" href="%s" as "font" crossorigin type="font/woff2">';
    printf(
        $preload,
        getWassupFontUrl()
    );
}
function getWassupFontUrl($pathkey = null)
{
    if (!$pathkey) {
        $pathkey = 'local';

        // Allow PoP Resource Loader to hook in its values
        $pathkey = \PoP\Root\App::applyFilters(
            'getWassupFontUrl:pathkey',
            $pathkey
        );
    }

    return getWassupFontPath($pathkey).'/fonts/Rockwell.woff2';
}
function getWassupFontPath($pathkey)
{
    $local_font_path = POPTHEME_WASSUPWEBPLATFORM_URL.'/css'.(PoP_WebPlatform_ServerUtils::useMinifiedResources() ? '/dist' : '');
    if ($pathkey == 'local') {
        return $local_font_path;
    }

    // Allow PoP Resource Loader to hook in its values
    return \PoP\Root\App::applyFilters(
        'getWassupFontPath',
        $local_font_path,
        $pathkey
    );
}
