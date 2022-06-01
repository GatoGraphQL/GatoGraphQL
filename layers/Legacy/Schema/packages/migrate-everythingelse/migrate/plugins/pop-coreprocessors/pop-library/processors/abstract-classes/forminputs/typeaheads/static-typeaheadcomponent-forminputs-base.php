<?php

abstract class PoP_Module_Processor_StaticTypeaheadComponentFormInputsBase extends PoP_Module_Processor_TypeaheadComponentFormInputsBase
{
    protected function getValueKey(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return 'value';
    }
    protected function getComponentTemplateResource(\PoP\ComponentModel\Component\Component $component)
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTSTATIC_TYPEAHEAD_COMPONENT];
    }
    protected function getTokenizerKeys(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return array();
    }
}
