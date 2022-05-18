<?php

use PoP\ComponentModel\ComponentProcessors\FormComponentComponentProcessorInterface;

abstract class PoP_Module_Processor_NoLabelFormComponentGroupsBase extends PoP_Module_Processor_FormComponentGroupsBase implements FormComponentComponentProcessorInterface
{
    public function initModelProps(array $componentVariation, array &$props): void
    {
        $component = $this->getComponentSubmodule($componentVariation);

        // Because the checkbox already has the label, then it can be shown already there
        $this->setProp($componentVariation, $props, 'label', '');
        $this->setProp($component, $props, 'label', $this->getLabel($componentVariation, $props));

        parent::initModelProps($componentVariation, $props);
    }
}
