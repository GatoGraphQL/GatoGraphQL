<?php

abstract class PoP_Module_Processor_TagViewComponentHeadersBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_VIEWCOMPONENT_HEADER_TAG];
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $component, array &$props): array
    {
        $data_fields = array('id', 'symbolnamedescription');
        if ($this->headerShowUrl($component, $props)) {
            $data_fields[] = 'url';
        }

        return $data_fields;
    }

    public function headerShowUrl(array $component, array &$props)
    {
        return false;
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);
    
        // Add the URL in the header? Sometimes yes (eg: Addon) sometimes not (eg: modal)
        if ($this->headerShowUrl($component, $props)) {
            $ret['header-show-url'] = true;
        }
        
        return $ret;
    }
}
