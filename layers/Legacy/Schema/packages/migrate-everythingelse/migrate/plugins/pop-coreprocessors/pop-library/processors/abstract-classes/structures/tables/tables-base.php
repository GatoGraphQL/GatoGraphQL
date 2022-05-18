<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_TablesBase extends PoP_Module_Processor_StructuresBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_TABLE];
    }

    public function getHeaderTitles(array $componentVariation)
    {
        return array();
    }

    protected function getDescription(array $componentVariation, array &$props)
    {
        return '';
    }
    
    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        if ($headerTitles = $this->getHeaderTitles($componentVariation)) {
            $ret['header'][GD_JS_TITLES] = $headerTitles;

            if ($description = $this->getDescription($componentVariation, $props)) {
                $ret[GD_JS_DESCRIPTION] = $description;
            }
        }
        
        return $ret;
    }
}
