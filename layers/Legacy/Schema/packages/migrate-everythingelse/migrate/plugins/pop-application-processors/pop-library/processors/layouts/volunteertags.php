<?php

class PoP_Module_Processor_VolunteerTagLayouts extends PoP_Module_Processor_VolunteerTagLayoutsBase
{
    public final const MODULE_LAYOUT_POSTADDITIONAL_VOLUNTEER = 'layout-postadditional-volunteer';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_POSTADDITIONAL_VOLUNTEER],
        );
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_POSTADDITIONAL_VOLUNTEER:
                $this->appendProp($module, $props, 'class', 'label label-warning');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



