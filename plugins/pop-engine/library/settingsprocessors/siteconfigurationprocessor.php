<?php
namespace PoP\Engine\Settings\Impl;

class PageModuleSiteConfigurationProcessor extends \PoP\Engine\Settings\SiteConfigurationProcessorBase
{
    public function getEntryModule()
    {
        $pop_module_routemoduleprocessor_manager = \PoP\Engine\RouteModuleProcessorManager_Factory::getInstance();
        return $pop_module_routemoduleprocessor_manager->getRouteModuleByMostAllmatchingVarsProperties(POP_PAGEMODULEGROUP_ENTRYMODULE);
    }
}

/**
 * Initialization
 */
new PageModuleSiteConfigurationProcessor();
