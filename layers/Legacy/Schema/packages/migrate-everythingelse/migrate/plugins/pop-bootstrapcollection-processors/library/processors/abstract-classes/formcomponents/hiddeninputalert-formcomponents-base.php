<?php

use PoP\ComponentModel\ComponentProcessors\FormComponentComponentProcessorInterface;

abstract class PoP_Module_Processor_HiddenInputAlertFormComponentsBase extends PoP_Module_Processor_AlertsBase implements FormComponentComponentProcessorInterface
{
    use FormComponentModuleDelegatorTrait;

    public function getFormcomponentModule(array $componentVariation)
    {
        return $this->getHiddeninputModule($componentVariation);
    }

    public function getLayoutSubmodules(array $componentVariation)
    {
        return array(
            $this->getSelectedModule($componentVariation),
            $this->getHiddeninputModule($componentVariation),
        );
    }

    public function getSelectedModule(array $componentVariation)
    {
        return null;
    }
    public function getHiddeninputModule(array $componentVariation)
    {
        return null;
    }

    public function getAlertBaseClass(array $componentVariation, array &$props)
    {
        return parent::getAlertBaseClass($componentVariation, $props).' hiddeninputalert';
    }

    public function getAlertClass(array $componentVariation, array &$props)
    {
        return 'alert-warning alert-sm';
    }

    public function showCloseButton(array $componentVariation, array &$props)
    {
        return false;
    }

    public function initRequestProps(array $componentVariation, array &$props): void
    {
        $this->metaFormcomponentInitModuleRequestProps($componentVariation, $props);
        parent::initRequestProps($componentVariation, $props);
    }
}
