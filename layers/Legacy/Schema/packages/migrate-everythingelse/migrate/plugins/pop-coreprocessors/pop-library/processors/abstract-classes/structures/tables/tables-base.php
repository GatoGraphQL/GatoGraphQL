<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_TablesBase extends PoP_Module_Processor_StructuresBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_TABLE];
    }

    public function getHeaderTitles(array $component)
    {
        return array();
    }

    protected function getDescription(array $component, array &$props)
    {
        return '';
    }
    
    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret = parent::getImmutableConfiguration($component, $props);

        if ($headerTitles = $this->getHeaderTitles($component)) {
            $ret['header'][GD_JS_TITLES] = $headerTitles;

            if ($description = $this->getDescription($component, $props)) {
                $ret[GD_JS_DESCRIPTION] = $description;
            }
        }
        
        return $ret;
    }
}
