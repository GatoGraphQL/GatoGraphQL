<?php

class UserStance_Module_Processor_CustomVerticalSingleSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public final const MODULE_VERTICALSIDEBARINNER_SINGLE_STANCE = 'vertical-sidebarinner-single-stance';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_VERTICALSIDEBARINNER_SINGLE_STANCE],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_VERTICALSIDEBARINNER_SINGLE_STANCE:
                $ret = array_merge(
                    $ret,
                    UserStance_FullViewSidebarSettings::getSidebarSubmodules(GD_SIDEBARSECTION_STANCE)
                );
                break;
        }

        return $ret;
    }
}



