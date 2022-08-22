<?php

class PoP_Module_Processor_VolunteerTagLayouts extends PoP_Module_Processor_VolunteerTagLayoutsBase
{
    public final const COMPONENT_LAYOUT_POSTADDITIONAL_VOLUNTEER = 'layout-postadditional-volunteer';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_POSTADDITIONAL_VOLUNTEER,
        );
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_POSTADDITIONAL_VOLUNTEER:
                $this->appendProp($component, $props, 'class', 'label label-warning');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



