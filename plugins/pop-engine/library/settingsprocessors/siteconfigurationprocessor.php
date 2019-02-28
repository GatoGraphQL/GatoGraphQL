<?php
namespace PoP\Engine\Settings\Impl;

class PageModuleSiteConfigurationProcessor extends \PoP\Engine\Settings\SiteConfigurationProcessorBase
{
    public function getEntryModule()
    {
        $pop_module_pagemoduleprocessor_manager = \PoP\Engine\PageModuleProcessorManager_Factory::getInstance();
        return $pop_module_pagemoduleprocessor_manager->getPageModuleByMostAllmatchingVarsProperties(POP_PAGEMODULEGROUP_ENTRYMODULE);
    }
}

/**
 * Initialization
 */
new PageModuleSiteConfigurationProcessor();
