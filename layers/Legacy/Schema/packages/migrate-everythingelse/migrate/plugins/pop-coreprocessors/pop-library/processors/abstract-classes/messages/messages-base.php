<?php

abstract class PoP_Module_Processor_MessagesBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_MESSAGE];
    }

    public function getMessage(array $componentVariation)
    {
        return '';
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);
    
        $ret['message'] = $this->getMessage($componentVariation);
        
        return $ret;
    }
}
