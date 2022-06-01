<?php
use PoP\ComponentModel\ComponentProcessors\FormComponentComponentProcessorInterface;

abstract class PoP_Module_Processor_SubcomponentFormComponentGroupsBase extends PoP_Module_Processor_FormComponentGroupsBase implements FormComponentComponentProcessorInterface
{
    public function getComponentSubname(\PoP\ComponentModel\Component\Component $component)
    {
        return null;
    }

    public function getComponentName(\PoP\ComponentModel\Component\Component $component)
    {

        // input-name is an array, return the specific subcomponent field
        $name = parent::getComponentName($component);
        return $name[$this->getComponentSubname($component)];
    }
}
