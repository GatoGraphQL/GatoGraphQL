<?php

class PoP_Module_Processor_VolunteerTagLayouts extends PoP_Module_Processor_VolunteerTagLayoutsBase
{
    public final const MODULE_LAYOUT_POSTADDITIONAL_VOLUNTEER = 'layout-postadditional-volunteer';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_POSTADDITIONAL_VOLUNTEER],
        );
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_POSTADDITIONAL_VOLUNTEER:
                $this->appendProp($componentVariation, $props, 'class', 'label label-warning');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



