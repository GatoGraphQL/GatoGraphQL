<?php

abstract class PoP_Module_Processor_StylesLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_STYLES];
    }

    public function getElemTarget(array $module, array &$props)
    {
        return '';
    }
    public function getElemStyles(array $module, array &$props)
    {
        return array();
    }
    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $ret['elem-target'] = $this->getElemTarget($module, $props);
        $ret['elem-styles'] = $this->getElemStyles($module, $props);

        return $ret;
    }
}
