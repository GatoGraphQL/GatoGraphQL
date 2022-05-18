<?php
use PoP\ComponentModel\Misc\GeneralUtils;

// Add the locale to all the multidomains
\PoP\Root\App::addFilter('pop_componentmanager:multidomain:locale', 'wassupQtransSetLocale', 0, 2);
function wassupQtransSetLocale($locale, $domain)
{
    $pluginapi = PoP_Multilingual_FunctionsAPIFactory::getInstance();
    if ($pluginapi->getUrlModificationMode() == POP_MULTILINGUAL_URLMODIFICATIONMODE_PREPATH) {
        // By default, add the current language, after the domain, to all domains
        // Then, this can be overriden on a domain by domain basis
        $pluginapi = PoP_Multilingual_FunctionsAPIFactory::getInstance();
        return $domain.'/'.$pluginapi->getCurrentLanguage().'/';
    }

    return $locale;
}


/**
 * Override the URLs corresponding to each domain
 */
function popMultidomainQtransMaybeReplacelang($url, $domain, $path)
{
    $pluginapi = PoP_Multilingual_FunctionsAPIFactory::getInstance();

    // For MESYM, do not use ES, in that case switch to EN
    $enabled = POP_MULTIDOMAIN_QTRANS_LANGUAGES_ENABLED[$domain] ?? array();
    if (!in_array($pluginapi->getCurrentLanguage(), $enabled) && POP_MULTIDOMAIN_QTRANS_LANGUAGES_DEFAULT[$domain]) {
        if ($pluginapi->getUrlModificationMode() == POP_MULTILINGUAL_URLMODIFICATIONMODE_PREPATH) {
            // qtranxf_convertURL doesn't work, so recreate the url manually
            // MESYM: Replace the path bit, from ES to EN
            $url = $domain.'/'.POP_MULTIDOMAIN_QTRANS_LANGUAGES_DEFAULT[$domain].$path;
        }
    }

    return $url;
}

/**
 * Add the locale to all the multidomains, for Service Workers
 */
\PoP\Root\App::addFilter('pop_componentmanager:multidomain:locale', 'popMultidomainQtransSetLocale', 10, 2);
function popMultidomainQtransSetLocale($locale, $domain)
{
    return popMultidomainQtransMaybeReplacelang($locale, $domain, '/');
}

/**
 * External Domains background load: Add the language to the URL
 */
function popMultidomainMaybeAddLang($querysource)
{

    // Allow qTrans to inject the language
    $domain = GeneralUtils::getDomain($querysource);
    if (in_array($domain, POP_MULTIDOMAIN_QTRANS_ADDLANG_QUERYSOURCES)) {
        $pluginapi = PoP_Multilingual_FunctionsAPIFactory::getInstance();
        if ($pluginapi->getUrlModificationMode() == POP_MULTILINGUAL_URLMODIFICATIONMODE_PREPATH) {
            // qtranxf_convertURL doesn't work, so create the URL manually
            $querysource = $domain.'/'.$pluginapi->getCurrentLanguage().substr($querysource, strlen($domain));
        }
    }
    return $querysource;
}
