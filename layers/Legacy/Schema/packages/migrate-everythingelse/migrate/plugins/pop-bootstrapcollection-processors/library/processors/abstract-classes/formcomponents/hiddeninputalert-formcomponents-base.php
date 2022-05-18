<?php

use PoP\ComponentModel\ComponentProcessors\FormComponentComponentProcessorInterface;

abstract class PoP_Module_Processor_HiddenInputAlertFormComponentsBase extends PoP_Module_Processor_AlertsBase implements FormComponentComponentProcessorInterface
{
    use FormComponentModuleDelegatorTrait;

    public function getFormcomponentModule(array $module)
    {
        return $this->getHiddeninputModule($module);
    }

    public function getLayoutSubmodules(array $module)
    {
        return array(
            $this->getSelectedModule($module),
            $this->getHiddeninputModule($module),
        );
    }

    public function getSelectedModule(array $module)
    {
        return null;
    }
    public function getHiddeninputModule(array $module)
    {
        return null;
    }

    public function getAlertBaseClass(array $module, array &$props)
    {
        return parent::getAlertBaseClass($module, $props).' hiddeninputalert';
    }

    public function getAlertClass(array $module, array &$props)
    {
        return 'alert-warning alert-sm';
    }

    public function showCloseButton(array $module, array &$props)
    {
        return false;
    }

    public function initRequestProps(array $module, array &$props): void
    {
        $this->metaFormcomponentInitModuleRequestProps($module, $props);
        parent::initRequestProps($module, $props);
    }
}
