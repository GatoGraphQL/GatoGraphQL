<?php

class GD_SP_Custom_EM_Module_Processor_PostMultipleSidebarComponents extends PoP_Module_Processor_MultiplesBase
{
    public const MODULE_SIDEBARMULTICOMPONENT_LOCATIONPOST = 'sidebarmulticomponent-locationpost';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SIDEBARMULTICOMPONENT_LOCATIONPOST],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_SIDEBARMULTICOMPONENT_LOCATIONPOST:
                $ret[] = [GD_Custom_EM_Module_Processor_PostWidgets::class, GD_Custom_EM_Module_Processor_PostWidgets::MODULE_WIDGETCOMPACT_LOCATIONPOSTINFO];
                $ret[] = [PoP_Module_Processor_SidebarComponentWrappers::class, PoP_Module_Processor_SidebarComponentWrappers::MODULE_WIDGETWRAPPER_REFERENCES];
                $ret[] = [PoP_Module_Processor_Widgets::class, PoP_Module_Processor_Widgets::MODULE_WIDGETCOMPACT_POST_AUTHORS];
                break;
        }

        return $ret;
    }
}



