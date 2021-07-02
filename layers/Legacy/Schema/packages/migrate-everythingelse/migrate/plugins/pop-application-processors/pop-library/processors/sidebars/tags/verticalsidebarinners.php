<?php

class Wassup_Module_Processor_CustomVerticalTagSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public const MODULE_VERTICALSIDEBARINNER_TAG = 'vertical-sidebarinner-tag';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VERTICALSIDEBARINNER_TAG],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_VERTICALSIDEBARINNER_TAG:
                $ret = array_merge(
                    $ret,
                    FullTagSidebarSettings::getSidebarSubmodules(GD_SIDEBARSECTION_TAG)
                );
                break;
        }

        return $ret;
    }
}



