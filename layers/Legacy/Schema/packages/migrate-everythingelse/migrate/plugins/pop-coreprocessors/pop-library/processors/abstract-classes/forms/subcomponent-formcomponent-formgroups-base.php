<?php
use PoP\ComponentModel\ComponentProcessors\FormComponentComponentProcessorInterface;

abstract class PoP_Module_Processor_SubcomponentFormComponentGroupsBase extends PoP_Module_Processor_FormComponentGroupsBase implements FormComponentComponentProcessorInterface
{
    public function getComponentSubname(array $module)
    {
        return null;
    }

    public function getComponentName(array $module)
    {

        // input-name is an array, return the specific subcomponent field
        $name = parent::getComponentName($module);
        return $name[$this->getComponentSubname($module)];
    }
}
