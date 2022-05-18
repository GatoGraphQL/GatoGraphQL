<?php

abstract class PoP_Module_Processor_CheckboxFormInputsBase extends PoP_Module_Processor_FormInputsBase
{

    // use PoP_Module_Processor_MultipleFormInputsTrait;

    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_Forms_TemplateResourceLoaderProcessor::class, PoP_Forms_TemplateResourceLoaderProcessor::RESOURCE_FORMINPUT_CHECKBOX];
    }

    public function getCheckboxValue(array $component, array &$props)
    {
        return null;
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        if ($checkbox_value = $this->getCheckboxValue($component, $props)) {
            $ret['checkbox-value'] = $checkbox_value;
        }

        // Needed to check the checkbox based on a multiple/single value
        $ret['compare-by'] = $this->isMultiple($component) ? 'in' : 'eq';
        
        return $ret;
    }
}
