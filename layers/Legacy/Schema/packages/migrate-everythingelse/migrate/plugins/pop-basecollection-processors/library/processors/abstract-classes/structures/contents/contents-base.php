<?php

abstract class PoP_Module_Processor_ContentsBase extends PoP_Module_Processor_StructuresBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_BaseCollectionWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BaseCollectionWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_CONTENT];
    }

    protected function getDescription(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return null;
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        if ($description = $this->getProp($component, $props, 'description')) {
            $ret[GD_JS_DESCRIPTION] = $description;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        $this->setProp($component, $props, 'description', $this->getDescription($component, $props));
        $this->appendProp($component, $props, 'class', 'pop-content');
        parent::initModelProps($component, $props);
    }
}
