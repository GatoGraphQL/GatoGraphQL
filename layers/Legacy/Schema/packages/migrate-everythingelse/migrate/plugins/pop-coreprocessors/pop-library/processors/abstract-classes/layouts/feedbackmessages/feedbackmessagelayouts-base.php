<?php

abstract class PoP_Module_Processor_FeedbackMessageLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_FEEDBACKMESSAGE];
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);
        
        $ret['messages'] = $this->getMessages($component, $props);
        
        return $ret;
    }

    public function getMessages(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return array();
    }
}
