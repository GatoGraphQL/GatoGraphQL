<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade;

class PoP_ProcessorAutomatedEmailsBase extends PoP_AutomatedEmailsBase
{
    protected function getPagesectionSettingsid()
    {
        
        // By default, use the main pageSection
        return [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::COMPONENT_PAGESECTION_BODY];
    }

    protected function getBlockModule()
    {
        $pop_component_componentroutingprocessor_manager = ComponentRoutingProcessorManagerFacade::getInstance();
        return $pop_component_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGECOMPONENTGROUPPLACEHOLDER_MAINCONTENTCOMPONENT);
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
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $pagesection_settings_id = $this->getPagesectionSettingsid();
        $block_component = $this->getBlockModule();
        $block_settings_id = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($block_component);
        $json = PoP_ServerSideRenderingFactory::getInstance()->getJson();
        return !empty($json['datasetcomponentdata']['combinedstate']['dbobjectids'][$pagesection_settings_id][$block_settings_id]);
    }
}
