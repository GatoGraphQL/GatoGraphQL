<?php

class PoP_Module_Processor_AnchorMenus extends PoP_Module_Processor_AnchorMenusBase
{
    public final const COMPONENT_ANCHORMENU = 'anchormenu';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_ANCHORMENU],
        );
    }

    public function getItemClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_ANCHORMENU:
                return 'btn btn-default btn-block';
        }
    
        return parent::getItemClass($component, $props);
    }
}


