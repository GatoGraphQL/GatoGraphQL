<?php

use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\Facades\CMS\CMSServiceFacade;

class PoP_MultiDomainSPAResourceLoader_DynamicJSResourceLoaderProcessor extends PoP_DynamicJSResourceLoaderProcessor
{
    public const RESOURCE_MULTIDOMAIN_RESOURCELOADERCONFIG = 'multidomain-resourceloader-config';
    public const RESOURCE_RESOURCELOADERCONFIG_EXTERNAL = 'resourceloader-config-external';
    public const RESOURCE_RESOURCELOADERCONFIG_EXTERNALRESOURCES = 'resourceloader-config-externalresources';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_MULTIDOMAIN_RESOURCELOADERCONFIG],
            [self::class, self::RESOURCE_RESOURCELOADERCONFIG_EXTERNAL],
            [self::class, self::RESOURCE_RESOURCELOADERCONFIG_EXTERNALRESOURCES],
        ];
    }
    
    public function getFilename(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_MULTIDOMAIN_RESOURCELOADERCONFIG:
                global $pop_multidomain_resourceloader_configfile;
                return $pop_multidomain_resourceloader_configfile->getFilename();
        }

        return parent::getFilename($resource);
    }
    
    public function getDir(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_MULTIDOMAIN_RESOURCELOADERCONFIG:
                global $pop_multidomain_resourceloader_configfile;
                return $pop_multidomain_resourceloader_configfile->getDir();
        }
    
        return parent::getDir($resource);
    }
    
    public function getAssetPath(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_MULTIDOMAIN_RESOURCELOADERCONFIG:
                global $pop_multidomain_resourceloader_configfile;
                return $pop_multidomain_resourceloader_configfile->getFilepath();
        }

        return parent::getAssetPath($resource);
    }

    public function canBundle(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_RESOURCELOADERCONFIG_EXTERNAL:
            case self::RESOURCE_RESOURCELOADERCONFIG_EXTERNALRESOURCES:
                return false;
        }

        return parent::canBundle($resource);
    }
    
    public function getFileUrl(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_MULTIDOMAIN_RESOURCELOADERCONFIG:
                global $pop_multidomain_resourceloader_configfile;
                return $pop_multidomain_resourceloader_configfile->getFileurl();
            
            case self::RESOURCE_RESOURCELOADERCONFIG_EXTERNAL:
            case self::RESOURCE_RESOURCELOADERCONFIG_EXTERNALRESOURCES:
                // When we load the External Page, obtain the resources for the requested domain
                // Eg: https://sukipop.com/en/external/?url=https%3A%2F%2Fwww.mesym.com%2Fen%2Fevents%2Fmindset-public-talk-maintaining-peopled-forests-by-joe-fragoso-and-kamal-s-fadzil%2F
                $vars = ApplicationState::getVars();
                if ($external_url_domain = \PoP\Root\App::getState('external-url-domain')) {
                     // Comment Leo 06/11/2017: we don't know what nature will be needed for the external domain
                    // (eg: this URL needs the single nature, to process an event: https://sukipop.com/en/external/?url=https%3A%2F%2Fwww.mesym.com%2Fen%2Fevents%2Fmindset-public-talk-maintaining-peopled-forests-by-joe-fragoso-and-kamal-s-fadzil%2F)
                    // So we must load all resources, and not deferred, then no need for file initialresources.js
                    global $pop_sparesourceloader_configfile, $pop_sparesourceloader_resources_configfile/*, $pop_resourceloader_initialresources_configfile*/;
                    $file_urls = array(
                        self::RESOURCE_RESOURCELOADERCONFIG_EXTERNAL => $pop_sparesourceloader_configfile->getFileurl(),
                        self::RESOURCE_RESOURCELOADERCONFIG_EXTERNALRESOURCES => $pop_sparesourceloader_resources_configfile->getFileurl(),
                        // self::RESOURCE_RESOURCELOADERCONFIG_EXTERNALINITIALRESOURCES => $pop_resourceloader_initialresources_configfile->getFileurl(),
                    );
                    $file_url = $file_urls[$resource[1]];
                    
                    $cmsService = CMSServiceFacade::getInstance();
                    $local_domain = $cmsService->getSiteURL();

                    // Obtain all the info for that domain, or fall back on the local domain info, which we know we have
                    $multidomain_info = PoP_MultiDomain_Utils::getMultidomainWebsites();
                    $external_website_name = $multidomain_info[$external_url_domain]['handle'] ?? $multidomain_info[$local_domain]['handle'];

                    // Theme and thememode: if we are in the default theme/thememode, then switch to the default thememode of the external website
                    // Then, GetPoP, which thememode 'simple', will load the resourceloader config files for its own default thememode, 'sliding'
                    // If it is not the default one, then use the one we are loading in the local website (eg: 'embed', 'print')
                    $options = array();
                    if (defined('POP_THEME_INITIALIZED')) {
                        $options['theme'] = \PoP\Root\App::getState('theme-isdefault') ?
                          $multidomain_info[$external_url_domain]['default-theme'] ?? $multidomain_info[$local_domain]['default-theme'] :
                          \PoP\Root\App::getState('theme');
                        $options['thememode'] = \PoP\Root\App::getState('theme-isdefault') && \PoP\Root\App::getState('thememode-isdefault') ?
                          $multidomain_info[$external_url_domain]['default-thememode'] ?? $multidomain_info[$local_domain]['default-thememode'] :
                          \PoP\Root\App::getState('thememode');
                    }
                    $file_url = PoP_MultiDomain_Utils::transformUrl($file_url, $external_url_domain, $external_website_name, $options);

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
            // Commented because POP_RESOURCELOADER_RESOURCELOADER doesn't exist, and it is not clear if it should be removed or replaced with another resource
            // case self::RESOURCE_RESOURCELOADERCONFIG_EXTERNAL:
            //     $dependencies[] = POP_RESOURCELOADER_RESOURCELOADER;
            //     break;

            case self::RESOURCE_RESOURCELOADERCONFIG_EXTERNALRESOURCES:
                $dependencies[] = [self::class, self::RESOURCE_RESOURCELOADERCONFIG_EXTERNAL];
                break;
        }

        return $dependencies;
    }
    
    public function getDecoratedResources(array $resource)
    {
        $decorated = parent::getDecoratedResources($resource);
    
        switch ($resource[1]) {
            case self::RESOURCE_MULTIDOMAIN_RESOURCELOADERCONFIG:
                $decorated[] = [PoP_MultiDomain_JSResourceLoaderProcessor::class, PoP_MultiDomain_JSResourceLoaderProcessor::RESOURCE_MULTIDOMAIN];
                break;
        }

        return $decorated;
    }
}


