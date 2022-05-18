<?php

class GD_SP_EM_Module_Processor_CustomVerticalSingleSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public final const MODULE_VERTICALSIDEBARINNER_SINGLE_LOCATIONPOST = 'vertical-sidebarinner-single-locationpost';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VERTICALSIDEBARINNER_SINGLE_LOCATIONPOST],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
            case self::MODULE_VERTICALSIDEBARINNER_SINGLE_LOCATIONPOST:
                $ret = array_merge(
                    $ret,
                    Custom_EM_FullViewSidebarSettings::getSidebarSubmodules(GD_SIDEBARSECTION_LOCATIONPOST)
                );
                break;
        }

        return $ret;
    }
}



