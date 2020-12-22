<?php

abstract class PoP_Module_Processor_ContentsBase extends PoP_Module_Processor_StructuresBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_BaseCollectionWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BaseCollectionWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_CONTENT];
    }

    protected function getDescription(array $module, array &$props)
    {
        return null;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        if ($description = $this->getProp($module, $props, 'description')) {
            $ret[GD_JS_DESCRIPTION] = $description;
        }
        
        return $ret;
    }

    public function initModelProps(array $module, array &$props)
    {
        $this->setProp($module, $props, 'description', $this->getDescription($module, $props));
        $this->appendProp($module, $props, 'class', 'pop-content');
        parent::initModelProps($module, $props);
    }
}
