<?php
use PoP\Engine\Facades\CMS\CMSServiceFacade;
use PoP\Engine\Route\RouteUtils;

class PoP_ApplicationProcessors_UserLogin_Hooks
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
        
        // Add the loggedinuser-data page for that domain
        $cmsService = CMSServiceFacade::getInstance();
        $url = RouteUtils::getRouteURL(POP_USERLOGIN_ROUTE_LOGGEDINUSERDATA);        
        $homedomain = $cmsService->getSiteURL();
        if ($domain != $homedomain) {
            $bloginfo_url = $cmsService->getHomeURL();

            // Also send the path along (without language information)
            $path = substr($url, strlen($bloginfo_url));
            $url = $domain.substr($url, strlen($homedomain));

            // Allow to override (eg: for a given domain, the page slug may be different)
            $url = \PoP\Root\App::getHookManager()->applyFilters(
                'PoP_ApplicationProcessors_UserLogin_Hooks:backgroundurls:loggedinuser_data',
                $url,
                $domain,
                $path
            );
        }
        $backgroundurls[$url] = array(\PoP\ConfigurationComponentModel\Constants\Targets::MAIN);
        
        return $backgroundurls;
    }
}
    
/**
 * Initialize
 */
new PoP_ApplicationProcessors_UserLogin_Hooks();
