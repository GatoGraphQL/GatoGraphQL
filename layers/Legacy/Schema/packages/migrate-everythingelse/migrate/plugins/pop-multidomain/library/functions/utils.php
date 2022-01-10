<?php
use PoP\ComponentModel\Facades\Info\ApplicationInfoFacade;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\Facades\CMS\CMSServiceFacade;
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_MultiDomain_Utils
{
    public static function transformUrl($url, $domain, $website_name, $options = array())
    {
        // Must add the version (request will be routed through CDN)
        $cmsService = CMSServiceFacade::getInstance();
        $vars = ApplicationState::getVars();
        $url = GeneralUtils::addQueryArgs([
            'ver' => ApplicationInfoFacade::getInstance()->getVersion(),
        ], $url);
        $subpath = substr($url, strlen($cmsService->getSiteURL()));

        // Replace from /simple/ in GetPoP to /sliding/ in all the other websites
        if (defined('POP_THEME_INITIALIZED')) {
            $theme = $options['theme'];
            $thememode = $options['thememode'];

            $vars = ApplicationState::getVars();
            if ($theme && \PoP\Root\App::getState('theme') != $theme) {
                $subpath = str_replace('/'.\PoP\Root\App::getState('theme').'/', '/'.$theme.'/', $subpath);
            }
            if ($thememode && \PoP\Root\App::getState('thememode') != $thememode) {
                $subpath = str_replace('/'.\PoP\Root\App::getState('thememode').'/', '/'.$thememode.'/', $subpath);
            }
        }

        // Replace the local domain with the external one
        // Hook: also allow for PoP Cluster to replace the subpath /WEBSITE_NAME/ (after wp-content/pop-webplatform/) with the external website name
        return HooksAPIFacade::getInstance()->applyFilters(
            'PoP_MultiDomain_Utils:transformUrl',
            $domain.$subpath,
            $subpath,
            $url,
            $domain,
            $website_name,
            $options
        );
    }

    public static function getMultidomainWebsites()
    {
        // Also add the theme and thememode properties for the resourceLoader, to get the corresponding resourceloader-config.js file
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        $cmsService = CMSServiceFacade::getInstance();
        $domain = $cmsService->getSiteURL();
        $domain_properties = array(
            $domain => array(
                'name' => $cmsapplicationapi->getSiteName(),
                'description' => $cmsapplicationapi->getSiteDescription(),
                'handle' => \POP_WEBSITE,
            ),
        );
        if (defined('POP_THEME_INITIALIZED')) {
            $thememanager = \PoP\Theme\Themes\ThemeManagerFactory::getInstance();
            $default_themename = $thememanager->getDefaultThemename();
            $default_thememodename = $thememanager->getDefaultThememodename($default_themename);
            $domain_properties[$domain]['default-theme'] = $default_themename;
            $domain_properties[$domain]['default-thememode'] = $default_thememodename;
        }
        $domain_properties = HooksAPIFacade::getInstance()->applyFilters('PoP_MultiDomain_Utils:domain_properties', $domain_properties);

        // Add the ID to all domains
        foreach ($domain_properties as $domain => &$properties) {
            // Comment Leo 24/08/2017: no need for the pre-defined ID
            // $properties['id'] = RequestUtils::getDomainId($domain);

            // Allow to add the language, and then change the default language on a domain by domain basis
            $properties['locale'] = HooksAPIFacade::getInstance()->applyFilters(
                'pop_modulemanager:multidomain:locale',
                $domain,
                $domain
            );
        }
        return $domain_properties;
    }
}

