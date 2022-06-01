<?php

use PoP\ComponentModel\ComponentProcessors\FormComponentComponentProcessorInterface;

abstract class PoP_Module_Processor_HiddenInputAlertFormComponentsBase extends PoP_Module_Processor_AlertsBase implements FormComponentComponentProcessorInterface
{
    use FormComponentModuleDelegatorTrait;

    public function getFormcomponentComponent(\PoP\ComponentModel\Component\Component $component)
    {
        return $this->getHiddenInputComponent($component);
    }

    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        return array(
            $this->getSelectedComponent($component),
            $this->getHiddenInputComponent($component),
        );
    }

    public function getSelectedComponent(\PoP\ComponentModel\Component\Component $component)
    {
        return null;
    }
    public function getHiddenInputComponent(\PoP\ComponentModel\Component\Component $component)
    {
        return null;
    }

    public function getAlertBaseClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return parent::getAlertBaseClass($component, $props).' hiddeninputalert';
    }

    public function getAlertClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return 'alert-warning alert-sm';
    }

    public function showCloseButton(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return false;
    }

    public function initRequestProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        $this->metaFormcomponentInitModuleRequestProps($component, $props);
        parent::initRequestProps($component, $props);
    }
}
