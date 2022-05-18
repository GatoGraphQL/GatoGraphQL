<?php
use PoP\ComponentModel\ComponentProcessors\FormComponentComponentProcessorInterface;

abstract class PoP_Module_Processor_SubcomponentFormComponentGroupsBase extends PoP_Module_Processor_FormComponentGroupsBase implements FormComponentComponentProcessorInterface
{
    public function getComponentSubname(array $componentVariation)
    {
        return null;
    }

    public function getComponentName(array $componentVariation)
    {

        // input-name is an array, return the specific subcomponent field
        $name = parent::getComponentName($componentVariation);
        return $name[$this->getComponentSubname($componentVariation)];
    }
}
