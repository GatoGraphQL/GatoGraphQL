<?php

class PoP_ContentPostLinks_Module_Processor_CustomPostMultipleSidebarComponents extends PoP_Module_Processor_MultiplesBase
{
    public const MODULE_SIDEBARMULTICOMPONENT_LINK = 'sidebarmulticomponent-link';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SIDEBARMULTICOMPONENT_LINK],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_SIDEBARMULTICOMPONENT_LINK:
                $ret[] = [PoP_ContentPostLinks_Module_Processor_CustomPostWidgets::class, PoP_ContentPostLinks_Module_Processor_CustomPostWidgets::MODULE_WIDGETCOMPACT_LINKINFO];
                $ret[] = [PoP_Module_Processor_SidebarComponentWrappers::class, PoP_Module_Processor_SidebarComponentWrappers::MODULE_WIDGETWRAPPER_REFERENCES];
                $ret[] = [PoP_Module_Processor_Widgets::class, PoP_Module_Processor_Widgets::MODULE_WIDGETCOMPACT_POST_AUTHORS];
                break;
        }

        return $ret;
    }
}



