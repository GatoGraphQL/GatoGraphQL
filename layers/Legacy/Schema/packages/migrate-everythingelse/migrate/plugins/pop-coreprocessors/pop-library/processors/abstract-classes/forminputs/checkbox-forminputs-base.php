<?php

abstract class PoP_Module_Processor_CheckboxFormInputsBase extends PoP_Module_Processor_FormInputsBase
{

    // use PoP_Module_Processor_MultipleFormInputsTrait;

    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_Forms_TemplateResourceLoaderProcessor::class, PoP_Forms_TemplateResourceLoaderProcessor::RESOURCE_FORMINPUT_CHECKBOX];
    }

    public function getCheckboxValue(array $module, array &$props)
    {
        return null;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        if ($checkbox_value = $this->getCheckboxValue($module, $props)) {
            $ret['checkbox-value'] = $checkbox_value;
        }

        // Needed to check the checkbox based on a multiple/single value
        $ret['compare-by'] = $this->isMultiple($module) ? 'in' : 'eq';
        
        return $ret;
    }
}
