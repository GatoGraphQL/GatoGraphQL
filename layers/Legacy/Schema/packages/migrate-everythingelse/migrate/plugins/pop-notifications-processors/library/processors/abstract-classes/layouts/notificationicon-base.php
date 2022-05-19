<?php

abstract class PoP_Module_Processor_NotificationActionIconLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_AAL_Processors_TemplateResourceLoaderProcessor::class, PoP_AAL_Processors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_NOTIFICATIONICON];
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $component, array &$props): array
    {
        $ret = parent::getDataFields($component, $props);

        // $ret[] = 'action';
        $ret[] = 'icon';
        
        return $ret;
    }

    public function getIconClass(array $component, array &$props)
    {
        return 'fa fa-fw';
    }
    
    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $ret[GD_JS_CLASSES]['icon'] = $this->getIconClass($component, $props);

        return $ret;
    }
}
