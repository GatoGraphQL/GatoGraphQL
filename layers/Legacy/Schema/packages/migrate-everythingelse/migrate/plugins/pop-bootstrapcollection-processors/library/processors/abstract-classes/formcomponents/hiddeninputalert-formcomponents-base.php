<?php

use PoP\ComponentModel\ComponentProcessors\FormComponentComponentProcessorInterface;

abstract class PoP_Module_Processor_HiddenInputAlertFormComponentsBase extends PoP_Module_Processor_AlertsBase implements FormComponentComponentProcessorInterface
{
    use FormComponentModuleDelegatorTrait;

    public function getFormcomponentModule(array $component)
    {
        return $this->getHiddeninputModule($component);
    }

    public function getLayoutSubmodules(array $component)
    {
        return array(
            $this->getSelectedModule($component),
            $this->getHiddeninputModule($component),
        );
    }

    public function getSelectedModule(array $component)
    {
        return null;
    }
    public function getHiddeninputModule(array $component)
    {
        return null;
    }

    public function getAlertBaseClass(array $component, array &$props)
    {
        return parent::getAlertBaseClass($component, $props).' hiddeninputalert';
    }

    public function getAlertClass(array $component, array &$props)
    {
        return 'alert-warning alert-sm';
    }

    public function showCloseButton(array $component, array &$props)
    {
        return false;
    }

    public function initRequestProps(array $component, array &$props): void
    {
        $this->metaFormcomponentInitModuleRequestProps($component, $props);
        parent::initRequestProps($component, $props);
    }
}
