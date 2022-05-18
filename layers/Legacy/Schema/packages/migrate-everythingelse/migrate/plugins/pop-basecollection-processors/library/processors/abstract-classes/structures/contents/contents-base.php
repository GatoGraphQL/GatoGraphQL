<?php

abstract class PoP_Module_Processor_ContentsBase extends PoP_Module_Processor_StructuresBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_BaseCollectionWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BaseCollectionWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_CONTENT];
    }

    protected function getDescription(array $componentVariation, array &$props)
    {
        return null;
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        if ($description = $this->getProp($componentVariation, $props, 'description')) {
            $ret[GD_JS_DESCRIPTION] = $description;
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $this->setProp($componentVariation, $props, 'description', $this->getDescription($componentVariation, $props));
        $this->appendProp($componentVariation, $props, 'class', 'pop-content');
        parent::initModelProps($componentVariation, $props);
    }
}
