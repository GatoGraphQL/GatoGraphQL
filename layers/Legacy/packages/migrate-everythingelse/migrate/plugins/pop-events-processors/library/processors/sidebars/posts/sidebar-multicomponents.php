<?php

class GD_EM_Module_Processor_PostMultipleSidebarComponents extends PoP_Module_Processor_MultiplesBase
{
    public const MODULE_SIDEBARMULTICOMPONENT_EVENT = 'sidebarmulticomponent-event';
    public const MODULE_SIDEBARMULTICOMPONENT_PASTEVENT = 'sidebarmulticomponent-pastevent';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SIDEBARMULTICOMPONENT_EVENT],
            [self::class, self::MODULE_SIDEBARMULTICOMPONENT_PASTEVENT],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_SIDEBARMULTICOMPONENT_EVENT:
                $ret[] = [GD_EM_Module_Processor_SidebarComponents::class, GD_EM_Module_Processor_SidebarComponents::MODULE_EM_WIDGETCOMPACT_EVENTINFO];
                $ret[] = [PoP_Module_Processor_SidebarComponentWrappers::class, PoP_Module_Processor_SidebarComponentWrappers::MODULE_WIDGETWRAPPER_REFERENCES];
                $ret[] = [PoP_Module_Processor_Widgets::class, PoP_Module_Processor_Widgets::MODULE_WIDGETCOMPACT_POST_AUTHORS];
                break;

            case self::MODULE_SIDEBARMULTICOMPONENT_PASTEVENT:
                $ret[] = [GD_EM_Module_Processor_SidebarComponents::class, GD_EM_Module_Processor_SidebarComponents::MODULE_EM_WIDGETCOMPACT_PASTEVENTINFO];
                $ret[] = [PoP_Module_Processor_SidebarComponentWrappers::class, PoP_Module_Processor_SidebarComponentWrappers::MODULE_WIDGETWRAPPER_REFERENCES];
                $ret[] = [PoP_Module_Processor_Widgets::class, PoP_Module_Processor_Widgets::MODULE_WIDGETCOMPACT_POST_AUTHORS];
                break;
        }

        return $ret;
    }
}



