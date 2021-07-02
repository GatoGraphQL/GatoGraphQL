<?php

class GD_EM_Module_Processor_CustomVerticalSingleSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public const MODULE_VERTICALSIDEBARINNER_SINGLE_EVENT = 'vertical-sidebarinner-single-event';
    public const MODULE_VERTICALSIDEBARINNER_SINGLE_PASTEVENT = 'vertical-sidebarinner-single-pastevent';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VERTICALSIDEBARINNER_SINGLE_EVENT],
            [self::class, self::MODULE_VERTICALSIDEBARINNER_SINGLE_PASTEVENT],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_VERTICALSIDEBARINNER_SINGLE_EVENT:
                $ret = array_merge(
                    $ret,
                    EM_FullViewSidebarSettings::getSidebarSubmodules(GD_SIDEBARSECTION_EVENT)
                );
                break;

            case self::MODULE_VERTICALSIDEBARINNER_SINGLE_PASTEVENT:
                $ret = array_merge(
                    $ret,
                    EM_FullViewSidebarSettings::getSidebarSubmodules(GD_SIDEBARSECTION_PASTEVENT)
                );
                break;
        }

        return $ret;
    }
}



