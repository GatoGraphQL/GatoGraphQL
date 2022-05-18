<?php

class GD_URE_Module_Processor_SidebarComponentsWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const MODULE_URE_WIDGETWRAPPER_COMMUNITIES = 'ure-widgetwrapper-communities';
    public final const MODULE_URE_WIDGETCOMPACTWRAPPER_COMMUNITIES = 'ure-widgetcompactwrapper-communities';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_WIDGETWRAPPER_COMMUNITIES],
            [self::class, self::MODULE_URE_WIDGETCOMPACTWRAPPER_COMMUNITIES],
        );
    }

    public function getConditionSucceededSubmodules(array $module)
    {
        $ret = parent::getConditionSucceededSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_URE_WIDGETWRAPPER_COMMUNITIES:
                $ret[] = [GD_URE_Module_Processor_Widgets::class, GD_URE_Module_Processor_Widgets::MODULE_URE_WIDGET_COMMUNITIES];
                break;

            case self::MODULE_URE_WIDGETCOMPACTWRAPPER_COMMUNITIES:
                $ret[] = [GD_URE_Module_Processor_Widgets::class, GD_URE_Module_Processor_Widgets::MODULE_URE_WIDGETCOMPACT_COMMUNITIES];
                break;
        }

        return $ret;
    }

    public function getConditionField(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_URE_WIDGETWRAPPER_COMMUNITIES:
            case self::MODULE_URE_WIDGETCOMPACTWRAPPER_COMMUNITIES:
                return 'hasActiveCommunities';
        }

        return null;
    }
}



