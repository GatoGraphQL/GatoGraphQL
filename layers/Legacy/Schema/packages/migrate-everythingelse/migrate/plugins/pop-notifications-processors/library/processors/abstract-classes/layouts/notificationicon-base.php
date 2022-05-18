<?php

abstract class PoP_Module_Processor_NotificationActionIconLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_AAL_Processors_TemplateResourceLoaderProcessor::class, PoP_AAL_Processors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_NOTIFICATIONICON];
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $componentVariation, array &$props): array
    {
        $ret = parent::getDataFields($componentVariation, $props);

        // $ret[] = 'action';
        $ret[] = 'icon';
        
        return $ret;
    }

    public function getIconClass(array $componentVariation, array &$props)
    {
        return 'fa fa-fw';
    }
    
    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $ret[GD_JS_CLASSES]['icon'] = $this->getIconClass($componentVariation, $props);

        return $ret;
    }
}
