<?php

abstract class PoP_Module_Processor_ButtonGroupFormInputsBase extends PoP_Module_Processor_FormInputsBase
{

    // use PoP_Module_Processor_MultipleFormInputsTrait;

    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_Forms_TemplateResourceLoaderProcessor::class, PoP_Forms_TemplateResourceLoaderProcessor::RESOURCE_FORMINPUT_BUTTONGROUP];
    }

    public function getInputbtnClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return 'btn btn-default';
    }

    public function getInputbtnClasses(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return array();
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $input = $this->getInput($component);
        $ret['options'] = $input->getAllValues();

        if ($btnclass = $this->getProp($component, $props, 'btn-class')) {
            $ret[GD_JS_CLASSES]['btn-input'] = $btnclass;
        }
        if ($btnclasses = $this->getProp($component, $props, 'btn-classes')) {
            $ret[GD_JS_CLASSES]['btn-inputs'] = $btnclasses;
        }

        // multiple == true => checkbox type
        // multiple == false => radio type
        $multiple = $this->isMultiple($component);
        $ret['compare-by'] = $multiple ? 'in' : 'eq';
        $ret['type'] = $multiple ? 'checkbox' : 'radio';

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        $this->setProp($component, $props, 'btn-class', $this->getInputbtnClass($component, $props));
        $this->setProp($component, $props, 'btn-classes', $this->getInputbtnClasses($component, $props));
        parent::initModelProps($component, $props);
    }
}
