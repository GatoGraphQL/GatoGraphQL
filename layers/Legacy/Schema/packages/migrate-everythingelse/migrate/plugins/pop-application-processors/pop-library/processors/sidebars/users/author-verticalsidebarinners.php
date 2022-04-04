<?php

class PoP_Module_Processor_CustomVerticalAuthorSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public final const MODULE_VERTICALSIDEBARINNER_AUTHOR_GENERIC = 'vertical-sidebarinner-author-generic';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VERTICALSIDEBARINNER_AUTHOR_GENERIC],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_VERTICALSIDEBARINNER_AUTHOR_GENERIC:
                $ret = array_merge(
                    $ret,
                    FullUserSidebarSettings::getSidebarSubmodules(GD_SIDEBARSECTION_GENERICUSER)
                );
                break;
        }

        return $ret;
    }
}



