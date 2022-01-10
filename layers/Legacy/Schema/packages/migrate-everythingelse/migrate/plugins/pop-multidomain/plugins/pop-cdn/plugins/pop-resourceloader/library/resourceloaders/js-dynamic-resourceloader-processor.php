<?php

use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\Facades\CMS\CMSServiceFacade;

class PoP_MultiDomain_CDN_DynamicJSResourceLoaderProcessor extends PoP_DynamicJSResourceLoaderProcessor
{
    public const RESOURCE_CDNCONFIG_EXTERNAL = 'cdn-config-external';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_CDNCONFIG_EXTERNAL],
        ];
    }
    
    public function canBundle(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_CDNCONFIG_EXTERNAL:
                return false;
        }

        return parent::canBundle($resource);
    }
    
    public function getFileUrl(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_CDNCONFIG_EXTERNAL:
                // When we load the External Page, obtain the resources for the requested domain
                // Eg: https://sukipop.com/en/external/?url=https%3A%2F%2Fwww.mesym.com%2Fen%2Fevents%2Fmindset-public-talk-maintaining-peopled-forests-by-joe-fragoso-and-kamal-s-fadzil%2F
                $cmsService = CMSServiceFacade::getInstance();
                if ($external_url_domain = \PoP\Root\App::getState('external-url-domain')) {
                    global $pop_cdn_configfile;
                    $file_url = $pop_cdn_configfile->getFileurl();
                    $local_domain = $cmsService->getSiteURL();

                    // Obtain all the info for that domain, or fall back on the local domain info, which we know we have
                    $multidomain_info = PoP_MultiDomain_Utils::getMultidomainWebsites();
                    $external_website_name = $multidomain_info[$external_url_domain]['handle'] ?? $multidomain_info[$local_domain]['handle'];

                    // Theme and thememode: if we are in the default theme/thememode, then switch to the default thememode of the external website
                    // Then, GetPoP, which thememode 'simple', will load the cdn config files for its own default thememode, 'sliding'
                    // If it is not the default one, then use the one we are loading in the local website (eg: 'embed', 'print')
                    $file_url = PoP_MultiDomain_Utils::transformUrl($file_url, $external_url_domain, $external_website_name);

                    return $file_url;
                }

                // If there is no external domain, then return null, this file should not be enqueued
                return null;
        }

        return parent::getFileUrl($resource);
    }
    
    public function getDependencies(array $resource)
    {
        $dependencies = parent::getDependencies($resource);
    
        switch ($resource[1]) {
            case self::RESOURCE_CDNCONFIG_EXTERNAL:
                $dependencies[] = [PoP_CDN_JSResourceLoaderProcessor::class, PoP_CDN_JSResourceLoaderProcessor::RESOURCE_CDN];
                break;
        }

        return $dependencies;
    }
}


