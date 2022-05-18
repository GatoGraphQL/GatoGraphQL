<?php

abstract class PoP_Module_Processor_NotificationActionIconLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_AAL_Processors_TemplateResourceLoaderProcessor::class, PoP_AAL_Processors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_NOTIFICATIONICON];
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $module, array &$props): array
    {
        $ret = parent::getDataFields($module, $props);

        // $ret[] = 'action';
        $ret[] = 'icon';
        
        return $ret;
    }

    public function getIconClass(array $module, array &$props)
    {
        return 'fa fa-fw';
    }
    
    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $ret[GD_JS_CLASSES]['icon'] = $this->getIconClass($module, $props);

        return $ret;
    }
}
