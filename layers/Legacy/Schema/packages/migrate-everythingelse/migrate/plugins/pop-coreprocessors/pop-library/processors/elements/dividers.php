<?php

class PoP_Module_Processor_Dividers extends PoP_Module_Processor_DividersBase
{
    public final const COMPONENT_DIVIDER = 'divider';
    public final const COMPONENT_COLLAPSIBLEDIVIDER = 'collapsible-divider';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_DIVIDER,
            self::COMPONENT_COLLAPSIBLEDIVIDER,
        );
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_COLLAPSIBLEDIVIDER:
                $this->setProp($component, $props, 'class', 'collapse');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



