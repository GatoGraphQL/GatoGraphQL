<?php

abstract class PoP_Module_Processor_NotificationTimeLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_AAL_Processors_TemplateResourceLoaderProcessor::class, PoP_AAL_Processors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_NOTIFICATIONTIME];
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $module, array &$props): array
    {
        $ret = parent::getDataFields($module, $props);

        $ret[] = 'histTimeNogmt';

        // This one is needed only for the notifications digest, using automated emails
        $ret[] = 'histTimeReadable';
        
        return $ret;
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);
    
        $this->addJsmethod($ret, 'timeFromNow');

        return $ret;
    }

    public function getMomentFormat(array $module, array &$props)
    {

        // Documentation: http://momentjs.com/docs/
        // Unix timestamp
        return 'X';
    }
    
    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $ret['format'] = $this->getMomentFormat($module, $props);

        return $ret;
    }
}
