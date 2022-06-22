<?php

class GD_SP_EM_Module_Processor_CustomVerticalSingleSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public final const COMPONENT_VERTICALSIDEBARINNER_SINGLE_LOCATIONPOST = 'vertical-sidebarinner-single-locationpost';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_VERTICALSIDEBARINNER_SINGLE_LOCATIONPOST,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component->name) {
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



