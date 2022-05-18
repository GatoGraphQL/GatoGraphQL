<?php

class GD_Custom_EM_Module_Processor_WidgetWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const MODULE_LAYOUTWRAPPER_LOCATIONPOST_CATEGORIES = 'layoutwrapper-locationpost-categories';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUTWRAPPER_LOCATIONPOST_CATEGORIES],
        );
    }

    public function getConditionSucceededSubmodules(array $componentVariation)
    {
        $ret = parent::getConditionSucceededSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUTWRAPPER_LOCATIONPOST_CATEGORIES:
                $ret[] = [GD_Custom_EM_Module_Processor_Layouts::class, GD_Custom_EM_Module_Processor_Layouts::MODULE_LAYOUT_LOCATIONPOST_CATEGORIES];
                break;
        }

        return $ret;
    }

    public function getConditionFailedSubmodules(array $componentVariation)
    {
        $ret = parent::getConditionFailedSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUTWRAPPER_LOCATIONPOST_CATEGORIES:
                $ret[] = [GD_Custom_Module_Processor_WidgetMessages::class, GD_Custom_Module_Processor_WidgetMessages::MODULE_MESSAGE_NOCATEGORIES];
                break;
        }

        return $ret;
    }

    public function getConditionField(array $componentVariation): ?string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUTWRAPPER_LOCATIONPOST_CATEGORIES:
                return 'has-locationpostcategories';
        }

        return null;
    }
}



