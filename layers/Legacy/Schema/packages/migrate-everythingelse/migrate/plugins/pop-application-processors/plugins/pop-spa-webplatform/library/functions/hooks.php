<?php
use PoP\Engine\Facades\CMS\CMSServiceFacade;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_ApplicationProcessors_SPA_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
            'PoP_MultidomainProcessors_Module_Processor_Dataloads:backgroundurls',
            array($this, 'addBackgroundurls'),
            10,
            2
        );
    }

    public function addBackgroundurls($backgroundurls, $domain)
    {
        $cmsService = CMSServiceFacade::getInstance();
        $homedomain = $cmsService->getSiteURL();
        $bloginfo_url = $cmsService->getHomeURL();

        // Load all the Initial Frame loader pages for the domain
        foreach (PoP_SPAWebPlatform_ConfigurationUtils::getBackgroundloadUrls() as $url => $targets) {
            // Also send the path along (without language information)
            $path = substr($url, strlen($bloginfo_url));
            // By default, the URL simply changes from the home domain to the requested domain
            if ($domain != $homedomain) {
                $url = $domain.substr($url, strlen($homedomain));
            }
            $params_pos = strpos($path, '?');
            if ($params_pos > -1) {
                $path = substr($path, 0, $params_pos);
            }
            
            // Allow to override (eg: for a given domain, the page slug may be different)
            $url = \PoP\Root\App::getHookManager()->applyFilters(
                'PoP_ApplicationProcessors_SPA_Hooks:backgroundurls:backgroundurl',
                $url,
                $domain,
                $path
            );
            $backgroundurls[$url] = $targets;
        }
        
        return $backgroundurls;
    }
}
    
/**
 * Initialize
 */
new PoP_ApplicationProcessors_SPA_Hooks();
