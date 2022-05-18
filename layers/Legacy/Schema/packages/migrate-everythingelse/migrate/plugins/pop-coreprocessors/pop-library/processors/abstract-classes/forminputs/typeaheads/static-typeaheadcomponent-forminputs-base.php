<?php

abstract class PoP_Module_Processor_StaticTypeaheadComponentFormInputsBase extends PoP_Module_Processor_TypeaheadComponentFormInputsBase
{
    protected function getValueKey(array $componentVariation, array &$props)
    {
        return 'value';
    }
    protected function getComponentTemplateResource(array $componentVariation)
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTSTATIC_TYPEAHEAD_COMPONENT];
    }
    protected function getTokenizerKeys(array $componentVariation, array &$props)
    {
        return array();
    }
}
