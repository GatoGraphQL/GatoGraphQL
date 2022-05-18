<?php
use PoP\ComponentModel\ComponentProcessors\FormComponentComponentProcessorInterface;

abstract class PoP_Module_Processor_FormComponentGroupsBase extends PoP_Module_Processor_FormGroupsBase implements FormComponentComponentProcessorInterface
{
    use FormComponentModuleDelegatorTrait;

    public function getFormcomponentModule(array $module)
    {
        return $this->getComponentSubmodule($module);
    }

    public function getComponentName(array $module)
    {
        // Because this class is a FormComponentComponentProcessorInterface, input_name is the inner components input_name
        return $this->getInputName($module);
    }

    public function initRequestProps(array $module, array &$props): void
    {
        $this->metaFormcomponentInitModuleRequestProps($module, $props);
        parent::initRequestProps($module, $props);
    }

    public function initModelProps(array $module, array &$props): void
    {
        $component = $this->getComponentSubmodule($module);

        // Show the label in the FormComponentGroup, and not in the Input
        $this->setProp($module, $props, 'label', $this->getLabel($module, $props));
        $this->setProp($component, $props, 'label', '');
        $this->setProp($component, $props, 'placeholder', '');

        parent::initModelProps($module, $props);
    }
}
