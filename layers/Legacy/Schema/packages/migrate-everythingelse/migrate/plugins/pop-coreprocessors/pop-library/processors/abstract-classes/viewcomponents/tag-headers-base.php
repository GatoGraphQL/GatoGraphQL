<?php

abstract class PoP_Module_Processor_TagViewComponentHeadersBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_VIEWCOMPONENT_HEADER_TAG];
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLModel\ComponentModelSpec\Ast\LeafModuleField[]
     */
    public function getDataFields(array $module, array &$props): array
    {
        $data_fields = array('id', 'symbolnamedescription');
        if ($this->headerShowUrl($module, $props)) {
            $data_fields[] = 'url';
        }

        return $data_fields;
    }

    public function headerShowUrl(array $module, array &$props)
    {
        return false;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);
    
        // Add the URL in the header? Sometimes yes (eg: Addon) sometimes not (eg: modal)
        if ($this->headerShowUrl($module, $props)) {
            $ret['header-show-url'] = true;
        }
        
        return $ret;
    }
}
