<?php
use PoP\Engine\Facades\CMS\CMSServiceFacade;
use PoP\Hooks\Facades\HooksAPIFacade;

/**
 * Litte bit of everything
 */
// Change the position of where the path in a URL starts (accounting for "en/")
HooksAPIFacade::getInstance()->addFilter('pop_modulemanager:pathstartpos', 'gdQtransPathstartpos');
function gdQtransPathstartpos($pos)
{
    $pluginapi = PoP_Multilingual_FunctionsAPIFactory::getInstance();
    if ($pluginapi->getUrlModificationMode() == POP_MULTILINGUAL_URLMODIFICATIONMODE_PREPATH) {
        // Initial "/" + length of language code + "/"
        return $pos + strlen($pluginapi->getCurrentLanguage()) + 1;
    }

    return $pos;
}

/*
 * Add extra classes to the body: Locale
 */
HooksAPIFacade::getInstance()->addFilter("gdClassesBody", "gdBodyClassLocale");
function gdBodyClassLocale($body_classes)
{

    // Language
    $pluginapi = PoP_Multilingual_FunctionsAPIFactory::getInstance();
    $body_classes[] = "body-".$pluginapi->getCurrentLanguage();
    
    return $body_classes;
}

/**
 * Add language to Ajax url
 */

// Add the locale to the webplatform
HooksAPIFacade::getInstance()->addFilter('pop_modulemanager:locale', 'popQtransLocale');
function popQtransLocale($locale)
{

    // Send the language
    $pluginapi = PoP_Multilingual_FunctionsAPIFactory::getInstance();
    return $pluginapi->getCurrentLanguage();
}

// Add the locale to the home url
HooksAPIFacade::getInstance()->addFilter('pop_modulemanager:homelocale_url', 'popQtransHomelocaleUrl');
function popQtransHomelocaleUrl($url)
{
    $cmsService = CMSServiceFacade::getInstance();
    // $cmsService->getHomeURL() already contains the language information
    return $cmsService->getHomeURL();
}
