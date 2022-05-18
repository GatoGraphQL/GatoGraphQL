<?php

abstract class PoP_Module_Processor_SelectFormInputsBase extends PoP_Module_Processor_FormInputsBase
{

    // use PoP_Module_Processor_MultipleFormInputsTrait;

    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_Forms_TemplateResourceLoaderProcessor::class, PoP_Forms_TemplateResourceLoaderProcessor::RESOURCE_FORMINPUT_SELECT];
    }

    public function addLabel(array $componentVariation, array &$props)
    {
        return false;
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $ret['disabledvalues'] = $this->getProp($componentVariation, $props, 'disabledvalues');

        $addLabel = $this->addLabel($componentVariation, $props);

        $label = $this->getProp($componentVariation, $props, 'label');
        $input = $this->getInput($componentVariation);
        $options = $addlabel ? $input->getAllValues($label) : $input->getAllValues();

        $ret['title'] = $label;
        $ret['options'] = $options;

        $multiple = $this->isMultiple($componentVariation);
        $ret['multiple'] = $multiple;
        $ret['compare-by'] = $multiple ? 'in' : 'eq';

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $this->setProp($componentVariation, $props, 'disabledvalues', array());
        parent::initModelProps($componentVariation, $props);
    }
}
