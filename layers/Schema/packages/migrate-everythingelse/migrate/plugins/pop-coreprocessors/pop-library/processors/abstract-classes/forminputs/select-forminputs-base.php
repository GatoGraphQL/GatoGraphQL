<?php

abstract class PoP_Module_Processor_SelectFormInputsBase extends PoP_Module_Processor_FormInputsBase
{

    // use PoP_Module_Processor_MultipleFormInputsTrait;
    
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_Forms_TemplateResourceLoaderProcessor::class, PoP_Forms_TemplateResourceLoaderProcessor::RESOURCE_FORMINPUT_SELECT];
    }

    public function addLabel(array $module, array &$props)
    {
        return false;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $ret['disabledvalues'] = $this->getProp($module, $props, 'disabledvalues');

        $addLabel = $this->addLabel($module, $props);
    
        $label = $this->getProp($module, $props, 'label');
        $input = $this->getInput($module);
        $options = $addlabel ? $input->getAllValues($label) : $input->getAllValues();

        $ret['title'] = $label;
        $ret['options'] = $options;

        $multiple = $this->isMultiple($module);
        $ret['multiple'] = $multiple;
        $ret['compare-by'] = $multiple ? 'in' : 'eq';
                
        return $ret;
    }

    public function initModelProps(array $module, array &$props)
    {
        $this->setProp($module, $props, 'disabledvalues', array());
        parent::initModelProps($module, $props);
    }
}
