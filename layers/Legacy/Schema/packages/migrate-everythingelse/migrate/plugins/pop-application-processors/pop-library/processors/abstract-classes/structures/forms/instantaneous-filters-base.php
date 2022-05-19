<?php

abstract class PoP_Module_Processor_InstantaneousFiltersBase extends PoP_Module_Processor_FiltersBase
{
    public function initModelProps(array $component, array &$props): void
    {
        $this->appendProp($component, $props, 'class', 'pop-filterform-instantaneous');

        // Add for the target for the onActionThenClick function
        if ($inner = $this->getInnerSubcomponent($component)) {
            $this->setProp($inner, $props, 'trigger-component', $component);
        }
        parent::initModelProps($component, $props);
    }
}
