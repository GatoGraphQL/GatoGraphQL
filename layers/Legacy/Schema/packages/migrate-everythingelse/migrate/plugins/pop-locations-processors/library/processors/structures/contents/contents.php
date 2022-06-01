<?php

class PoP_Module_Processor_LocationContents extends PoP_Module_Processor_ContentsBase
{
    public final const COMPONENT_TRIGGERTYPEAHEADSELECT_LOCATION = 'triggertypeaheadselect-location';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_TRIGGERTYPEAHEADSELECT_LOCATION,
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_TRIGGERTYPEAHEADSELECT_LOCATION:
                return [PoP_Module_Processor_LocationContentInners::class, PoP_Module_Processor_LocationContentInners::COMPONENT_TRIGGERTYPEAHEADSELECTINNER_LOCATION];
        }

        return parent::getInnerSubcomponent($component);
    }
}


