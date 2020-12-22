<?php
namespace PoP\Engine\Settings\Impl;
use PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade;
use PoP\ModuleRouting\ModuleRoutingGroups;

class PageModuleSiteConfigurationProcessor extends \PoP\ComponentModel\Settings\SiteConfigurationProcessorBase
{
    public function getEntryModule(): ?array
    {
        $pop_module_routemoduleprocessor_manager = RouteModuleProcessorManagerFacade::getInstance();
        return $pop_module_routemoduleprocessor_manager->getRouteModuleByMostAllmatchingVarsProperties(ModuleRoutingGroups::ENTRYMODULE);
    }
}

/**
 * Initialization
 */
new PageModuleSiteConfigurationProcessor();
