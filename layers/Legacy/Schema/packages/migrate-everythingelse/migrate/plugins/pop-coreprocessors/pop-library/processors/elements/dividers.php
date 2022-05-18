<?php

class PoP_Module_Processor_Dividers extends PoP_Module_Processor_DividersBase
{
    public final const MODULE_DIVIDER = 'divider';
    public final const MODULE_COLLAPSIBLEDIVIDER = 'collapsible-divider';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DIVIDER],
            [self::class, self::COMPONENT_COLLAPSIBLEDIVIDER],
        );
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_COLLAPSIBLEDIVIDER:
                $this->setProp($component, $props, 'class', 'collapse');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



