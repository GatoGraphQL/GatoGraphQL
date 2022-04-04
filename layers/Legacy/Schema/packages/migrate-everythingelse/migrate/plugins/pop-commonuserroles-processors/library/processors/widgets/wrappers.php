<?php

class GD_URE_Custom_Module_Processor_SidebarComponentsWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const MODULE_URE_LAYOUTWRAPPER_PROFILEINDIVIDUAL_DETAILS = 'ure-layoutwrapper-profileindividual-details';
    public final const MODULE_URE_LAYOUTWRAPPER_PROFILEORGANIZATION_DETAILS = 'ure-layoutwrapper-profileorganization-details';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_LAYOUTWRAPPER_PROFILEINDIVIDUAL_DETAILS],
            [self::class, self::MODULE_URE_LAYOUTWRAPPER_PROFILEORGANIZATION_DETAILS],
        );
    }

    public function getConditionSucceededSubmodules(array $module)
    {
        $ret = parent::getConditionSucceededSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_URE_LAYOUTWRAPPER_PROFILEINDIVIDUAL_DETAILS:
                $ret[] = [GD_URE_Custom_Module_Processor_ProfileIndividualLayouts::class, GD_URE_Custom_Module_Processor_ProfileIndividualLayouts::MODULE_URE_LAYOUT_PROFILEINDIVIDUAL_DETAILS];
                break;

            case self::MODULE_URE_LAYOUTWRAPPER_PROFILEORGANIZATION_DETAILS:
                $ret[] = [GD_URE_Custom_Module_Processor_ProfileOrganizationLayouts::class, GD_URE_Custom_Module_Processor_ProfileOrganizationLayouts::MODULE_URE_LAYOUT_PROFILEORGANIZATION_DETAILS];
                break;
        }

        return $ret;
    }

    public function getConditionFailedSubmodules(array $module)
    {
        $ret = parent::getConditionFailedSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_URE_LAYOUTWRAPPER_PROFILEINDIVIDUAL_DETAILS:
            case self::MODULE_URE_LAYOUTWRAPPER_PROFILEORGANIZATION_DETAILS:
                $ret[] = [GD_URE_Custom_Module_Processor_WidgetMessages::class, GD_URE_Custom_Module_Processor_WidgetMessages::MODULE_URE_MESSAGE_NODETAILS];
                break;
        }

        return $ret;
    }

    public function getConditionField(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_URE_LAYOUTWRAPPER_PROFILEINDIVIDUAL_DETAILS:
                return 'hasIndividualDetails';

            case self::MODULE_URE_LAYOUTWRAPPER_PROFILEORGANIZATION_DETAILS:
                return 'hasOrganizationDetails';
        }

        return 'hasOrganizationDetails';
    }
}



