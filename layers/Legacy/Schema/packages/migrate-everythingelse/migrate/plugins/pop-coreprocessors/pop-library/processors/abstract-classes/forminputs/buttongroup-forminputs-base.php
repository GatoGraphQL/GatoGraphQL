<?php

abstract class PoP_Module_Processor_ButtonGroupFormInputsBase extends PoP_Module_Processor_FormInputsBase
{

    // use PoP_Module_Processor_MultipleFormInputsTrait;

    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_Forms_TemplateResourceLoaderProcessor::class, PoP_Forms_TemplateResourceLoaderProcessor::RESOURCE_FORMINPUT_BUTTONGROUP];
    }

    public function getInputbtnClass(array $module, array &$props)
    {
        return 'btn btn-default';
    }

    public function getInputbtnClasses(array $module, array &$props)
    {
        return array();
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $input = $this->getInput($module);
        $ret['options'] = $input->getAllValues();

        if ($btnclass = $this->getProp($module, $props, 'btn-class')) {
            $ret[GD_JS_CLASSES]['btn-input'] = $btnclass;
        }
        if ($btnclasses = $this->getProp($module, $props, 'btn-classes')) {
            $ret[GD_JS_CLASSES]['btn-inputs'] = $btnclasses;
        }

        // multiple == true => checkbox type
        // multiple == false => radio type
        $multiple = $this->isMultiple($module);
        $ret['compare-by'] = $multiple ? 'in' : 'eq';
        $ret['type'] = $multiple ? 'checkbox' : 'radio';

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        $this->setProp($module, $props, 'btn-class', $this->getInputbtnClass($module, $props));
        $this->setProp($module, $props, 'btn-classes', $this->getInputbtnClasses($module, $props));
        parent::initModelProps($module, $props);
    }
}
