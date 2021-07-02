<?php
use PoP\ComponentModel\Modules\ModuleUtils;
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade;

class PoP_ProcessorAutomatedEmailsBase extends PoP_AutomatedEmailsBase
{
    protected function getPagesectionSettingsid()
    {
        
        // By default, use the main pageSection
        return [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::MODULE_PAGESECTION_BODY];
    }

    protected function getBlockModule()
    {
        $pop_module_routemoduleprocessor_manager = RouteModuleProcessorManagerFacade::getInstance();
        return $pop_module_routemoduleprocessor_manager->getRouteModuleByMostAllmatchingVarsProperties(POP_PAGEMODULEGROUPPLACEHOLDER_MAINCONTENTMODULE);
    }
    
    protected function getContent()
    {
        $content = PoP_ServerSideRenderingFactory::getInstance()->renderBlock($this->getPagesectionSettingsid(), $this->getBlockModule());

        // Newsletter: remove all unwanted HTML output, such as Javascript code
        // Taken from https://stackoverflow.com/questions/7130867/remove-script-tag-from-html-content#7131156
        $content = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $content);

        return $content;
    }
    
    protected function hasResults()
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
        $pagesection_settings_id = $this->getPagesectionSettingsid();
        $block_module = $this->getBlockModule();
        $block_settings_id = ModuleUtils::getModuleOutputName($block_module);
        $json = PoP_ServerSideRenderingFactory::getInstance()->getJson();
        return !empty($json['datasetmoduledata']['combinedstate']['dbobjectids'][$pagesection_settings_id][$block_settings_id]);
    }
}
