<?php

abstract class PoP_Module_Processor_InstantaneousFiltersBase extends PoP_Module_Processor_FiltersBase
{
    public function initModelProps(array $module, array &$props): void
    {
        $this->appendProp($module, $props, 'class', 'pop-filterform-instantaneous');

        // Add for the target for the onActionThenClick function
        if ($inner = $this->getInnerSubmodule($module)) {
            $this->setProp($inner, $props, 'trigger-module', $module);
        }
        parent::initModelProps($module, $props);
    }
}
