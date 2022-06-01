<?php
use PoP\ComponentModel\ComponentProcessors\FormComponentComponentProcessorInterface;

abstract class PoP_Module_Processor_FormComponentGroupsBase extends PoP_Module_Processor_FormGroupsBase implements FormComponentComponentProcessorInterface
{
    use FormComponentModuleDelegatorTrait;

    public function getFormcomponentComponent(\PoP\ComponentModel\Component\Component $component)
    {
        return $this->getComponentSubcomponent($component);
    }

    public function getComponentName(\PoP\ComponentModel\Component\Component $component)
    {
        // Because this class is a FormComponentComponentProcessorInterface, input_name is the inner components input_name
        return $this->getInputName($component);
    }

    public function initRequestProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        $this->metaFormcomponentInitModuleRequestProps($component, $props);
        parent::initRequestProps($component, $props);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        $component = $this->getComponentSubcomponent($component);

        // Show the label in the FormComponentGroup, and not in the Input
        $this->setProp($component, $props, 'label', $this->getLabel($component, $props));
        $this->setProp($component, $props, 'label', '');
        $this->setProp($component, $props, 'placeholder', '');

        parent::initModelProps($component, $props);
    }
}
