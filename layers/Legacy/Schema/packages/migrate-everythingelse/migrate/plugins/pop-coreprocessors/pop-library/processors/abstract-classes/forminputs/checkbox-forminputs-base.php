<?php

abstract class PoP_Module_Processor_CheckboxFormInputsBase extends PoP_Module_Processor_FormInputsBase
{

    // use PoP_Module_Processor_MultipleFormInputsTrait;

    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_Forms_TemplateResourceLoaderProcessor::class, PoP_Forms_TemplateResourceLoaderProcessor::RESOURCE_FORMINPUT_CHECKBOX];
    }

    public function getCheckboxValue(array $componentVariation, array &$props)
    {
        return null;
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        if ($checkbox_value = $this->getCheckboxValue($componentVariation, $props)) {
            $ret['checkbox-value'] = $checkbox_value;
        }

        // Needed to check the checkbox based on a multiple/single value
        $ret['compare-by'] = $this->isMultiple($componentVariation) ? 'in' : 'eq';
        
        return $ret;
    }
}
