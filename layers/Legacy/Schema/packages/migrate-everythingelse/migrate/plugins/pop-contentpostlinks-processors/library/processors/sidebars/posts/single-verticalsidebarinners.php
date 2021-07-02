<?php

class PoP_ContentPostLinks_Module_Processor_CustomVerticalSingleSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public const MODULE_VERTICALSIDEBARINNER_SINGLE_LINK = 'vertical-sidebarinner-single-link';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VERTICALSIDEBARINNER_SINGLE_LINK],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_VERTICALSIDEBARINNER_SINGLE_LINK:
                $ret = array_merge(
                    $ret,
                    PoP_ContentPostLinks_FullViewSidebarSettings::getSidebarSubmodules(GD_SIDEBARSECTION_POSTLINK)
                );
                break;
        }

        return $ret;
    }
}



