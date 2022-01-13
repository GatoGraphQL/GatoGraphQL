<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

function getCompatibilityJsFiles()
{
    $files = array();
    if (PoP_WebPlatform_ServerUtils::accessExternalcdnResources()) {
        $files[] = 'https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js';
        $files[] = 'https://cdnjs.cloudflare.com/ajax/libs/respond.js/1.1.0/respond.min.js';
    } else {
        $files[] = POPTHEME_WASSUP_URL.'/js/compat/html5shiv.min.js';
        $files[] = POPTHEME_WASSUP_URL.'/js/compat/respond.min.js';
    }

    return $files;
}

/**
 * preloading fonts
 */
$cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
if (!$cmsapplicationapi->isAdminPanel()) {
    if (defined('POP_ENGINEWEBPLATFORM_INITIALIZED')) {
        \PoP\Root\App::getHookManager()->addAction('popcms:head', 'popHeaderPreloadfonts');
    }
}
function popHeaderPreloadfonts()
{

    // Always preload the "woff2" font only, no need for the others: all browsers that support preload, also support woff2
    // Read on https://developers.google.com/web/fundamentals/performance/optimizing-content-efficiency/webfont-optimization#preload_your_webfont_resources
    $preload = '<link rel="preload" href="%s" as "font" crossorigin type="font/woff2">';
    printf(
        $preload,
        getFontawesomeFontUrl()
    );
}
function getFontawesomeFontUrl($pathkey = null)
{
    if (!$pathkey) {
        $pathkey = 'local';
        if (PoP_WebPlatform_ServerUtils::accessExternalcdnResources()) {
            $pathkey = 'external';
        }

        // Allow PoP Resource Loader to hook in its values
        $pathkey = \PoP\Root\App::getHookManager()->applyFilters(
            'getFontawesomeFontUrl:pathkey',
            $pathkey
        );
    }

    return getFontawesomeFontPath($pathkey).'/fonts/fontawesome-webfont.woff2?v=4.7.0';
}
function getFontawesomeFontPath($pathkey)
{
    $local_font_path = POPTHEME_WASSUP_URL.'/css/includes';
    if ($pathkey == 'local') {
        return $local_font_path;
    } elseif ($pathkey == 'external') {
        return 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0';
    }
    
    // Allow PoP Resource Loader to hook in its values
    return \PoP\Root\App::getHookManager()->applyFilters(
        'getFontawesomeFontPath',
        $local_font_path,
        $pathkey
    );
}
