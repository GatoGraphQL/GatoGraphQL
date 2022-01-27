<?php

class PoP_ServiceWorkers_MultiDomain_Job_Fetch_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_ServiceWorkers_Job_Fetch:multidomains',
            array($this, 'getMultidomains')
        );

        \PoP\Root\App::addFilter(
            'PoP_ServiceWorkers_Job_Fetch:multidomain-locales',
            array($this, 'getMultidomainLocales')
        );
    }

    public function getMultidomains($multidomains)
    {
        $multidomains = array_merge(
            $multidomains,
            array_keys(PoP_MultiDomain_Utils::getMultidomainWebsites())
        );

        return $multidomains;
    }

    public function getMultidomainLocales($multidomain_locales)
    {
        $multidomain_websites = PoP_MultiDomain_Utils::getMultidomainWebsites();
        foreach ($multidomain_websites as $domain => $website) {
            if ($locale = $website['locale']) {
                $multidomain_locales[] = $locale;
            }
        }

        return $multidomain_locales;
    }
}

/**
 * Initialization
 */
new PoP_ServiceWorkers_MultiDomain_Job_Fetch_Hooks();
