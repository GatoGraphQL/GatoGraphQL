<?php

use PoP\ComponentModel\ComponentProcessors\FormComponentComponentProcessorInterface;

abstract class PoP_Module_Processor_NoLabelFormComponentGroupsBase extends PoP_Module_Processor_FormComponentGroupsBase implements FormComponentComponentProcessorInterface
{
    public function initModelProps(array $component, array &$props): void
    {
        $component = $this->getComponentSubcomponent($component);

        // Because the checkbox already has the label, then it can be shown already there
        $this->setProp($component, $props, 'label', '');
        $this->setProp($component, $props, 'label', $this->getLabel($component, $props));

        parent::initModelProps($component, $props);
    }
}
