<?php

class Wassup_Module_Processor_CustomVerticalSingleSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public const MODULE_VERTICALSIDEBARINNER_SINGLE_HIGHLIGHT = 'vertical-sidebarinner-single-highlight';
    public const MODULE_VERTICALSIDEBARINNER_SINGLE_POST = 'vertical-sidebarinner-single-post';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VERTICALSIDEBARINNER_SINGLE_HIGHLIGHT],
            [self::class, self::MODULE_VERTICALSIDEBARINNER_SINGLE_POST],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_VERTICALSIDEBARINNER_SINGLE_HIGHLIGHT:
                $ret = array_merge(
                    $ret,
                    FullViewSidebarSettings::getSidebarSubmodules(GD_SIDEBARSECTION_HIGHLIGHT)
                );
                break;

            case self::MODULE_VERTICALSIDEBARINNER_SINGLE_POST:
                $ret = array_merge(
                    $ret,
                    FullViewSidebarSettings::getSidebarSubmodules(GD_SIDEBARSECTION_POST)
                );
                break;
        }

        return $ret;
    }
}



