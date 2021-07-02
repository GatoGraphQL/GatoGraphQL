<?php

use PoP\ComponentModel\ModuleProcessors\FormComponentModuleProcessorInterface;

abstract class PoP_Module_Processor_NoLabelFormComponentGroupsBase extends PoP_Module_Processor_FormComponentGroupsBase implements FormComponentModuleProcessorInterface
{
    public function initModelProps(array $module, array &$props): void
    {
        $component = $this->getComponentSubmodule($module);

        // Because the checkbox already has the label, then it can be shown already there
        $this->setProp($module, $props, 'label', '');
        $this->setProp($component, $props, 'label', $this->getLabel($module, $props));

        parent::initModelProps($module, $props);
    }
}
