<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

abstract class PoP_Module_Processor_VolunteerTagLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_ApplicationProcessors_TemplateResourceLoaderProcessor::class, PoP_ApplicationProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_VOLUNTEERTAG];
    }

    public function getDataFields(array $module, array &$props): array
    {
        return array('volunteersNeeded');
    }

    public function getTitle(array $module, array &$props)
    {
        return '<i class="fa fa-leaf fa-fw fa-lg"></i>'.TranslationAPIFacade::getInstance()->__('Volunteer!', 'poptheme-wassup');
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
    
        $ret[GD_JS_TITLES] = array(
            'volunteer' => $this->getTitle($module, $props)
        );
        
        return $ret;
    }
}
