<?php

abstract class PoP_Module_Processor_FeedbackMessageLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_FEEDBACKMESSAGE];
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);
        
        $ret['messages'] = $this->getMessages($module, $props);
        
        return $ret;
    }

    public function getMessages(array $module, array &$props)
    {
        return array();
    }
}
