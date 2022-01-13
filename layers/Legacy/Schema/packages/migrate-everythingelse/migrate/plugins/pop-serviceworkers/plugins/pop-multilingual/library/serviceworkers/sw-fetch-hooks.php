<?php
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Engine\Facades\CMS\CMSServiceFacade;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_ServiceWorkers_QtransX_Job_Fetch_Hooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_ServiceWorkers_Job_Fetch:locales',
            array($this, 'getLocales')
        );
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_ServiceWorkers_Job_Fetch:appshell_url',
            array($this, 'getAppshellUrl'),
            10,
            2
        );
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_ServiceWorkers_Job_Fetch:locales_byurl',
            array($this, 'getLocalesByurl')
        );
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_ServiceWorkers_Job_Fetch:default_locale',
            array($this, 'getDefaultLocale')
        );
    }

    public function getLocales($locales)
    {
        $pluginapi = PoP_Multilingual_FunctionsAPIFactory::getInstance();
        if ($languages = $pluginapi->getEnabledLanguages()) {
            return $languages;
        }

        return $locales;
    }

    public function getAppshellUrl($url, $lang)
    {
        $pluginapi = PoP_Multilingual_FunctionsAPIFactory::getInstance();
        return $pluginapi->convertUrl($url, $lang);
    }

    public function getLocalesByurl($locales)
    {
        $pluginapi = PoP_Multilingual_FunctionsAPIFactory::getInstance();
        if ($languages = $pluginapi->getEnabledLanguages()) {
            // Ignore what has been given here, add the qTrans languages instead
            $locales = array();
            $cmsService = CMSServiceFacade::getInstance();
            $url = GeneralUtils::maybeAddTrailingSlash($cmsService->getHomeURL());
            foreach ($languages as $lang) {
                $locales[$pluginapi->convertUrl($url, $lang)] = $lang;
            }
        }

        return $locales;
    }

    public function getDefaultLocale($default)
    {
        $pluginapi = PoP_Multilingual_FunctionsAPIFactory::getInstance();
        if ($lang = $pluginapi->getCurrentLanguage()) {
            return $lang;
        }

        return $default;
    }
}

/**
 * Initialization
 */
new PoP_ServiceWorkers_QtransX_Job_Fetch_Hooks();
