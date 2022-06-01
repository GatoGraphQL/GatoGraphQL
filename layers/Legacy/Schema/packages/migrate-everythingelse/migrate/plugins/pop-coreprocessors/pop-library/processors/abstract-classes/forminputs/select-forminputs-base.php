<?php

abstract class PoP_Module_Processor_SelectFormInputsBase extends PoP_Module_Processor_FormInputsBase
{

    // use PoP_Module_Processor_MultipleFormInputsTrait;

    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_Forms_TemplateResourceLoaderProcessor::class, PoP_Forms_TemplateResourceLoaderProcessor::RESOURCE_FORMINPUT_SELECT];
    }

    public function addLabel(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return false;
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $ret['disabledvalues'] = $this->getProp($component, $props, 'disabledvalues');

        $addLabel = $this->addLabel($component, $props);

        $label = $this->getProp($component, $props, 'label');
        $input = $this->getInput($component);
        $options = $addlabel ? $input->getAllValues($label) : $input->getAllValues();

        $ret['title'] = $label;
        $ret['options'] = $options;

        $multiple = $this->isMultiple($component);
        $ret['multiple'] = $multiple;
        $ret['compare-by'] = $multiple ? 'in' : 'eq';

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        $this->setProp($component, $props, 'disabledvalues', array());
        parent::initModelProps($component, $props);
    }
}
