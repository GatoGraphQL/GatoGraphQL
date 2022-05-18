<?php

abstract class PoP_Module_Processor_FeedbackMessageLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_FEEDBACKMESSAGE];
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);
        
        $ret['messages'] = $this->getMessages($componentVariation, $props);
        
        return $ret;
    }

    public function getMessages(array $componentVariation, array &$props)
    {
        return array();
    }
}
