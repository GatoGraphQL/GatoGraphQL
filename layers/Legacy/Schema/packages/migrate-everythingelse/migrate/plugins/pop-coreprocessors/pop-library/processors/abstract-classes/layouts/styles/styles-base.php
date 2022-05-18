<?php

abstract class PoP_Module_Processor_StylesLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_STYLES];
    }

    public function getElemTarget(array $componentVariation, array &$props)
    {
        return '';
    }
    public function getElemStyles(array $componentVariation, array &$props)
    {
        return array();
    }
    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $ret['elem-target'] = $this->getElemTarget($componentVariation, $props);
        $ret['elem-styles'] = $this->getElemStyles($componentVariation, $props);

        return $ret;
    }
}
