<?php

abstract class PoP_Module_Processor_InstantaneousFiltersBase extends PoP_Module_Processor_FiltersBase
{
    public function initModelProps(array $componentVariation, array &$props): void
    {
        $this->appendProp($componentVariation, $props, 'class', 'pop-filterform-instantaneous');

        // Add for the target for the onActionThenClick function
        if ($inner = $this->getInnerSubmodule($componentVariation)) {
            $this->setProp($inner, $props, 'trigger-module', $componentVariation);
        }
        parent::initModelProps($componentVariation, $props);
    }
}
