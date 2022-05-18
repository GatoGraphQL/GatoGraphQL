<?php

abstract class PoP_Module_Processor_SegmentedButtonLinksBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_SEGMENTEDBUTTON_LINK];
    }

    public function getFontawesome(array $module, array &$props)
    {
        return null;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        if ($fontawesome = $this->getFontawesome($module, $props)) {
            $ret[GD_JS_FONTAWESOME] = $fontawesome;
        }

        return $ret;
    }
}
