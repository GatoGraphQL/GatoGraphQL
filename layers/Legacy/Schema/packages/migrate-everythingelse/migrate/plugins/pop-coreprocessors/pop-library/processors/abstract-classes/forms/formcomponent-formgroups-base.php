<?php
use PoP\ComponentModel\ComponentProcessors\FormComponentComponentProcessorInterface;

abstract class PoP_Module_Processor_FormComponentGroupsBase extends PoP_Module_Processor_FormGroupsBase implements FormComponentComponentProcessorInterface
{
    use FormComponentModuleDelegatorTrait;

    public function getFormcomponentModule(array $componentVariation)
    {
        return $this->getComponentSubmodule($componentVariation);
    }

    public function getComponentName(array $componentVariation)
    {
        // Because this class is a FormComponentComponentProcessorInterface, input_name is the inner components input_name
        return $this->getInputName($componentVariation);
    }

    public function initRequestProps(array $componentVariation, array &$props): void
    {
        $this->metaFormcomponentInitModuleRequestProps($componentVariation, $props);
        parent::initRequestProps($componentVariation, $props);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $component = $this->getComponentSubmodule($componentVariation);

        // Show the label in the FormComponentGroup, and not in the Input
        $this->setProp($componentVariation, $props, 'label', $this->getLabel($componentVariation, $props));
        $this->setProp($component, $props, 'label', '');
        $this->setProp($component, $props, 'placeholder', '');

        parent::initModelProps($componentVariation, $props);
    }
}
