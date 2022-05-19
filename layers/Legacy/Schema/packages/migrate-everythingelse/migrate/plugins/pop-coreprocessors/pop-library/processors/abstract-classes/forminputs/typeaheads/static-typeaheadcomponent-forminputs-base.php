<?php

abstract class PoP_Module_Processor_StaticTypeaheadComponentFormInputsBase extends PoP_Module_Processor_TypeaheadComponentFormInputsBase
{
    protected function getValueKey(array $component, array &$props)
    {
        return 'value';
    }
    protected function getComponentTemplateResource(array $component)
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTSTATIC_TYPEAHEAD_COMPONENT];
    }
    protected function getTokenizerKeys(array $component, array &$props)
    {
        return array();
    }
}
