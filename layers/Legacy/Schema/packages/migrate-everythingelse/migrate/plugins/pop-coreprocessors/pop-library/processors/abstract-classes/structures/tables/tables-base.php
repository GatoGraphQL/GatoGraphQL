<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_TablesBase extends PoP_Module_Processor_StructuresBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_TABLE];
    }

    public function getHeaderTitles(array $module)
    {
        return array();
    }

    protected function getDescription(array $module, array &$props)
    {
        return '';
    }
    
    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret = parent::getImmutableConfiguration($module, $props);

        if ($headerTitles = $this->getHeaderTitles($module)) {
            $ret['header'][GD_JS_TITLES] = $headerTitles;

            if ($description = $this->getDescription($module, $props)) {
                $ret[GD_JS_DESCRIPTION] = $description;
            }
        }
        
        return $ret;
    }
}
