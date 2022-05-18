<?php

abstract class PoP_Module_Processor_StylesLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_STYLES];
    }

    public function getElemTarget(array $component, array &$props)
    {
        return '';
    }
    public function getElemStyles(array $component, array &$props)
    {
        return array();
    }
    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $ret['elem-target'] = $this->getElemTarget($component, $props);
        $ret['elem-styles'] = $this->getElemStyles($component, $props);

        return $ret;
    }
}
