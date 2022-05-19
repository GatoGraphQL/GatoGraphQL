<?php

class GD_SP_EM_Module_Processor_CustomVerticalSingleSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public final const COMPONENT_VERTICALSIDEBARINNER_SINGLE_LOCATIONPOST = 'vertical-sidebarinner-single-locationpost';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_VERTICALSIDEBARINNER_SINGLE_LOCATIONPOST],
        );
    }

    public function getLayoutSubcomponents(array $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_VERTICALSIDEBARINNER_SINGLE_LOCATIONPOST:
                $ret = array_merge(
                    $ret,
                    Custom_EM_FullViewSidebarSettings::getSidebarSubcomponents(GD_SIDEBARSECTION_LOCATIONPOST)
                );
                break;
        }

        return $ret;
    }
}



