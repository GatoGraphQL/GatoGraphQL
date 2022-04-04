<?php

class UserStance_Module_Processor_CustomVerticalSingleSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public final const MODULE_VERTICALSIDEBARINNER_SINGLE_STANCE = 'vertical-sidebarinner-single-stance';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VERTICALSIDEBARINNER_SINGLE_STANCE],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_VERTICALSIDEBARINNER_SINGLE_STANCE:
                $ret = array_merge(
                    $ret,
                    UserStance_FullViewSidebarSettings::getSidebarSubmodules(GD_SIDEBARSECTION_STANCE)
                );
                break;
        }

        return $ret;
    }
}



