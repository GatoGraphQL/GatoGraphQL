<?php
use PoP\ComponentModel\Constants\Params;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Engine\Route\RouteUtils;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\SPA\ModuleFilters\Page;

class PoP_SPAWebPlatform_ConfigurationUtils
{
    public static function getBackgroundloadUrls()
    {
        $url_targets = array();
        foreach (PoP_SPA_ConfigurationUtils::getBackgroundloadRouteConfigurations() as $route => $configuration) {
            $url = RouteUtils::getRouteURL($route);

            // If preloading (eg: INITIALFRAMES) then add the action=preload and modulefilter=page URL parameters
            if ($configuration['preload'] ?? null) {
                $instanceManager = InstanceManagerFacade::getInstance();
                /** @var Page */
                $page = $instanceManager->getInstance(Page::class);

                $url = GeneralUtils::addQueryArgs([
                    Params::MODULEFILTER => $page->getName(),
                    Params::ACTIONS . '[]' => GD_URLPARAM_ACTION_PRELOAD,
                ], $url);
            }
            $url_targets[$url] = $configuration['targets'];
        }

        return HooksAPIFacade::getInstance()->applyFilters('PoP_WebPlatform_ConfigurationUtils:backgroundload_urls', $url_targets);
    }
}
