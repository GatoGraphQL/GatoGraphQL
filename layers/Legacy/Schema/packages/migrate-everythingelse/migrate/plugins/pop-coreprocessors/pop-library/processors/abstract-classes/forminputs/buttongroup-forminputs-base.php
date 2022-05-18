<?php

abstract class PoP_Module_Processor_ButtonGroupFormInputsBase extends PoP_Module_Processor_FormInputsBase
{

    // use PoP_Module_Processor_MultipleFormInputsTrait;

    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_Forms_TemplateResourceLoaderProcessor::class, PoP_Forms_TemplateResourceLoaderProcessor::RESOURCE_FORMINPUT_BUTTONGROUP];
    }

    public function getInputbtnClass(array $componentVariation, array &$props)
    {
        return 'btn btn-default';
    }

    public function getInputbtnClasses(array $componentVariation, array &$props)
    {
        return array();
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $input = $this->getInput($componentVariation);
        $ret['options'] = $input->getAllValues();

        if ($btnclass = $this->getProp($componentVariation, $props, 'btn-class')) {
            $ret[GD_JS_CLASSES]['btn-input'] = $btnclass;
        }
        if ($btnclasses = $this->getProp($componentVariation, $props, 'btn-classes')) {
            $ret[GD_JS_CLASSES]['btn-inputs'] = $btnclasses;
        }

        // multiple == true => checkbox type
        // multiple == false => radio type
        $multiple = $this->isMultiple($componentVariation);
        $ret['compare-by'] = $multiple ? 'in' : 'eq';
        $ret['type'] = $multiple ? 'checkbox' : 'radio';

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $this->setProp($componentVariation, $props, 'btn-class', $this->getInputbtnClass($componentVariation, $props));
        $this->setProp($componentVariation, $props, 'btn-classes', $this->getInputbtnClasses($componentVariation, $props));
        parent::initModelProps($componentVariation, $props);
    }
}
