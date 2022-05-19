<?php

abstract class PoP_Module_Processor_NotificationTimeLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_AAL_Processors_TemplateResourceLoaderProcessor::class, PoP_AAL_Processors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_NOTIFICATIONTIME];
    }

    /**
     * @todo Migrate from string to LeafComponentField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentField[]
     */
    public function getLeafComponentFields(array $component, array &$props): array
    {
        $ret = parent::getLeafComponentFields($component, $props);

        $ret[] = 'histTimeNogmt';

        // This one is needed only for the notifications digest, using automated emails
        $ret[] = 'histTimeReadable';
        
        return $ret;
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);
    
        $this->addJsmethod($ret, 'timeFromNow');

        return $ret;
    }

    public function getMomentFormat(array $component, array &$props)
    {

        // Documentation: http://momentjs.com/docs/
        // Unix timestamp
        return 'X';
    }
    
    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $ret['format'] = $this->getMomentFormat($component, $props);

        return $ret;
    }
}
