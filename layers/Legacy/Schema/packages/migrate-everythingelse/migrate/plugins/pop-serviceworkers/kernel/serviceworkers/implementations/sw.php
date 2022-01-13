<?php
use PoP\ComponentModel\Facades\Info\ApplicationInfoFacade;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Definitions\Constants\Params as DefinitionsParams;
use PoP\Engine\Facades\CMS\CMSServiceFacade;
use PoP\Engine\Route\RouteUtils;

class PoP_ServiceWorkers_Job_SW extends PoP_ServiceWorkers_Job
{
    public function getSwJsPath()
    {
        return POP_SERVICEWORKERS_ASSETS_DIR.'/js/jobs/sw.js';
    }

    public function getDependencies()
    {
        return array(
            'localforage' => POP_SERVICEWORKERS_ASSETS_DIR.'/js/dependencies/localforage.1.4.3.min.js',
            'utils' => POP_SERVICEWORKERS_ASSETS_DIR.'/js/jobs/lib/utils.js',
        );
    }

    public function getSwConfiguration()
    {
        $configuration = parent::getSwConfiguration();
		$cmsService = CMSServiceFacade::getInstance();
        
        // Add a string before the version, since starting with a number makes trouble
        $configuration['${cacheNamePrefix}'] = 'PoP';
        $configuration['${version}'] = ApplicationInfoFacade::getInstance()->getVersion();
        $configuration['${homeDomain}'] = $cmsService->getSiteURL();
        // $configuration['${contentDomain}'] = $this->getContentDomains();
        $configuration['${appshellPages}'] = $this->getAppshellPages();
        $configuration['${appshellPrecachedParams}'] = array();
        $configuration['${appshellFromServerParams}'] = array(
            \PoP\ComponentModel\Constants\Params::DATAOUTPUTMODE,
            \PoP\ComponentModel\Constants\Params::DATABASESOUTPUTMODE,
            \PoP\ConfigurationComponentModel\Constants\Params::FORMAT, // Initially, this is a proxy for \PoP\ConfigurationComponentModel\Constants\Params::SETTINGSFORMAT
            DefinitionsParams::MANGLED,
        );
        $configuration['${localesByURL}'] = $this->getLocalesByurl();
        $configuration['${defaultLocale}'] = $this->getDefaultLocale();
        $configuration['${versionParam}'] = \PoP\ComponentModel\Constants\Params::VERSION;
        $configuration['${outputJSON}'] = \PoP\ComponentModel\Constants\Params::OUTPUT.'='.\PoP\ComponentModel\Constants\Outputs::JSON;
        $configuration['${origins}'] = PoP_WebPlatform_ConfigurationUtils::getAllowedDomains();
        $configuration['${multidomains}'] = array(); // $this->getMultidomains();
        $configuration['${multidomainLocales}'] = $this->getMultidomainLocales();
        $configuration['${cacheBustParam}'] = GD_URLPARAM_SWCACHEBUST;
        // $configuration['${fontExtensions}'] = $this->getFontExtensions();
        $configuration['${staticCacheExtensions}'] = $this->getStaticcacheExtensions();
        $configuration['${staticResourceExtensions}'] = $this->getStaticresourceExtensions();

        // This will be injected by PoP Theme
        $configuration['${themes}'] = array();

        $resourceTypes = array('static', 'json', 'html');
        $configuration['${excludedFullPaths}'] = $configuration['${excludedPartialPaths}'] = $configuration['${excludedParams}'] = $configuration['${excludedParamValues}'] = $configuration['${cacheItems}'] = $configuration['${strategies}'] = $configuration['${ignore}'] = array();
        foreach ($resourceTypes as $resourceType) {
            $configuration['${excludedFullPaths}'][$resourceType] = array_unique(array_filter($this->getExcludedFullpaths($resourceType)));
            $configuration['${excludedPartialPaths}'][$resourceType] = array_unique(array_filter($this->getExcludedPartialpaths($resourceType)));
            $configuration['${excludedParams}'][$resourceType] = array_unique(array_filter($this->getExcludedParams($resourceType)));
            $configuration['${excludedParamValues}'][$resourceType] = array_unique(array_filter($this->getExcludedParamvalues($resourceType)));
            $configuration['${cacheItems}'][$resourceType] = array_values(array_unique($this->getPrecacheList($resourceType)));
            $configuration['${strategies}'][$resourceType] = $this->getStrategies($resourceType);
            $configuration['${ignore}'][$resourceType] = $this->getIgnoredParams($resourceType);
        }

        // These values will be overriden in wp-content/plugins/pop-serviceworkers/plugins/pop-cdn-core/library/serviceworkers/sw-hooks.php,
        // but must declare here the empty values so that, if the plug-in is not activated, it still replaces those values in service-worker.js
        $configuration['${contentCDNOriginalDomain}'] = $cmsService->getSiteURL();
        $configuration['${contentCDNDomain}'] = '';
        $configuration['${contentCDNParams}'] = array();

        // Allow to hook the CDN configuration
        $configuration = \PoP\Root\App::applyFilters('PoP_ServiceWorkers_Job_SW:configuration', $configuration);

        return $configuration;
    }

    protected function getPrecacheList($resourceType)
    {
        $precache = array();

        // Add the offline and appshell pages
        if ($resourceType == 'html') {
            if (defined('POP_THEME_INITIALIZED')) {
                foreach ($this->getAppshellPages() as $locale => $themes) {
                    foreach ($themes as $theme => $thememodes) {
                        foreach ($thememodes as $thememode => $url) {
                            $precache[] = $url;
                        }
                    }
                }
            } else {
                // Array of $locale => $url
                $precache = array_values($this->getAppshellPages());
            }
        }

        // Hook in the resources to pre-cache
        return \PoP\Root\App::applyFilters(
            'PoP_ServiceWorkers_Job_CacheResources:precache',
            $precache,
            $resourceType
        );
    }

    protected function getExcludedFullpaths($resourceType)
    {

        // Hook in the resources to exclude
        return \PoP\Root\App::applyFilters(
            'PoP_ServiceWorkers_Job_Fetch:exclude:full',
            array(),
            $resourceType
        );
    }

    protected function getExcludedPartialpaths($resourceType)
    {

        // Hook in the resources to exclude
        return \PoP\Root\App::applyFilters(
            'PoP_ServiceWorkers_Job_Fetch:exclude:partial',
            array(),
            $resourceType
        );
    }

    protected function getFontExtensions()
    {
        return \PoP\Root\App::applyFilters(
            'PoP_ServiceWorkers_Job_Fetch:font-extensions',
            array('woff', 'woff2', 'ttf', 'eof', 'eot')
        );
    }

    protected function getStaticcacheExtensions()
    {
        return \PoP\Root\App::applyFilters(
            'PoP_ServiceWorkers_Job_Fetch:staticcache-extensions',
            array_merge(
                array('js', 'css', 'jpg', 'jpeg', 'png', 'gif', 'svg'),
                $this->getFontExtensions()
            )
        );
    }

    protected function getStaticresourceExtensions()
    {
        return \PoP\Root\App::applyFilters(
            'PoP_ServiceWorkers_Job_Fetch:staticresource-extensions',
            array_merge(
                array('txt', 'ico', 'xml', 'xsl', 'css', 'js', 'svg', 'jpg', 'jpeg', 'png', 'gif', 'pdf'),
                $this->getFontExtensions()
            )
        );
    }

    protected function getIgnoredParams($resourceType)
    {
        $ignore = array();
        if ($resourceType == 'json') {
            $ignore = array(
                GD_URLPARAM_SWNETWORKFIRST,
            );
        }

        // Hook in the paths to include
        // All the layout loaders (eg: POP_POSTS_ROUTE_LOADERS_POSTS_LAYOUTS) belong here
        // It can be resolved to all silentDocument pages without a checkpoint
        return \PoP\Root\App::applyFilters(
            'PoP_ServiceWorkers_Job_Fetch:ignoredparams:'.$resourceType,
            $ignore
        );
    }

    protected function getStrategies($resourceType)
    {
        $strategies = array();
        if ($resourceType == 'json') {
            // $hasParams = $this->getIgnoredParams($resourceType);

            // Hook in the paths to include
            // All the layout loaders (eg: POP_POSTS_ROUTE_LOADERS_POSTS_LAYOUTS) belong here
            // It can be resolved to all silentDocument pages without a checkpoint
            $strategies['networkFirst'] = array(
                'startsWith' => array(
                    'full' => \PoP\Root\App::applyFilters(
                        'PoP_ServiceWorkers_Job_Fetch:strategies:'.$resourceType.':networkFirst:startsWith:full',
                        array()
                    ),
                    'partial' => \PoP\Root\App::applyFilters(
                        'PoP_ServiceWorkers_Job_Fetch:strategies:'.$resourceType.':networkFirst:startsWith:partial',
                        array()
                    ),
                ),
                'hasParams' => \PoP\Root\App::applyFilters(
                    'PoP_ServiceWorkers_Job_Fetch:strategies:'.$resourceType.':networkFirst:hasParams',
                    // $hasParams
                    array(
                        GD_URLPARAM_SWNETWORKFIRST,
                    )
                ),
            );
        }

        return $strategies;
    }

    protected function getExcludedParams($resourceType)
    {
        $excluded = array();
        if ($resourceType == 'json') {
            $excluded = array(
                // Do not cache when the URL has the timestamp, or otherwise the cache grows incredibly big with the loadLatest
                GD_URLPARAM_TIMESTAMP,
            );
        }

        // Hook in the params to exclude
        return \PoP\Root\App::applyFilters(
            'PoP_ServiceWorkers_Job_Fetch:exclude-params:'.$resourceType,
            $excluded
        );
    }

    protected function getExcludedParamvalues($resourceType)
    {

        // Each item in the array must be an array of two items: [param, value]
        $excluded = array();

        // Hook in the params to exclude
        return \PoP\Root\App::applyFilters(
            'PoP_ServiceWorkers_Job_Fetch:exclude-paramvalues:'.$resourceType,
            $excluded
        );
    }

    protected function getMultidomains()
    {
        $multidomains = array_unique(
            \PoP\Root\App::applyFilters(
                'PoP_ServiceWorkers_Job_Fetch:multidomains',
                array()
            )
        );

        // Make sure the homeurl is not there!
		$cmsService = CMSServiceFacade::getInstance();
        $homeurl = $cmsService->getSiteURL();
        $pos = array_search($homeurl, $multidomains);
        if ($pos > -1) {
            array_splice($multidomains, $pos, 1);
        }

        return $multidomains;
    }

    protected function getMultidomainLocales()
    {
        $multidomain_locales = array_unique(
            \PoP\Root\App::applyFilters(
                'PoP_ServiceWorkers_Job_Fetch:multidomain-locales',
                array()
            )
        );

        // Make sure the homeurl is not there!
		$cmsService = CMSServiceFacade::getInstance();
        $homelocale = GeneralUtils::maybeAddTrailingSlash($cmsService->getHomeURL());
        $pos = array_search($homelocale, $multidomain_locales);
        if ($pos > -1) {
            array_splice($multidomain_locales, $pos, 1);
        }

        return $multidomain_locales;
    }

    protected function getLocales()
    {

        // Allow qTrans to modify this
        return \PoP\Root\App::applyFilters(
            'PoP_ServiceWorkers_Job_Fetch:locales',
            array(get_locale())
        );
    }

    protected function getAppshellUrl($route, $locale, $options = array())
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        $url = RouteUtils::getRouteURL($route);
        if (defined('POP_THEME_INITIALIZED')) {
            $themename = $options['themename'] ?? '';
            $thememodename = $options['thememodename'] ?? '';
            $url = GeneralUtils::addQueryArgs([
                GD_URLPARAM_THEMEMODE => $thememodename,
                GD_URLPARAM_THEME => $themename,
            ], $url);
        }

        // Allow qTrans to modify this
        return \PoP\Root\App::applyFilters(
            'PoP_ServiceWorkers_Job_Fetch:appshell_url',
            $url,
            $locale
        );
    }

    protected function getAppshellPages()
    {
        $pages = array();
        if (defined('POP_ENGINEWEBPLATFORM_ROUTE_APPSHELL') && POP_ENGINEWEBPLATFORM_ROUTE_APPSHELL) {
            $locales = $this->getLocales();

            if (defined('POP_THEME_INITIALIZED')) {
                $thememanager = \PoP\Theme\Themes\ThemeManagerFactory::getInstance();

                // Just pre-cache the appshell for the default theme, and all of its thememodes
                $themes = array($thememanager->getTheme());
                foreach ($locales as $locale) {
                    foreach ($themes as $theme) {
                        foreach ($theme->getThememodes() as $thememode) {
                            $options = array(
                                'themename' => $theme->getName(),
                                'thememodename' => $thememode->getName(),
                            );
                            $pages[$locale][$theme->getName()][$thememode->getName()] = $this->getAppshellUrl(POP_ENGINEWEBPLATFORM_ROUTE_APPSHELL, $locale, $options);
                        }
                    }
                }
            } else {
                foreach ($locales as $locale) {
                    $pages[$locale] = $this->getAppshellUrl(POP_ENGINEWEBPLATFORM_ROUTE_APPSHELL, $locale);
                }
            }
        }

        return \PoP\Root\App::applyFilters(
            'PoP_ServiceWorkers_Job_Fetch:appshell_pages',
            $pages,
            $locales
        );
    }

    protected function getLocalesByurl()
    {

        // Allow qTrans to modify this
        return \PoP\Root\App::applyFilters(
            'PoP_ServiceWorkers_Job_Fetch:locales_byurl',
            array(
                site_url() => get_locale(),
            )
        );
    }

    protected function getDefaultLocale()
    {

        // Allow qTrans to modify this
        return \PoP\Root\App::applyFilters(
            'PoP_ServiceWorkers_Job_Fetch:default_locale',
            get_locale()
        );
    }
}

/**
 * Initialize
 */
new PoP_ServiceWorkers_Job_SW();
