<?php

class UserStance_Module_Processor_CustomVerticalSingleSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public final const COMPONENT_VERTICALSIDEBARINNER_SINGLE_STANCE = 'vertical-sidebarinner-single-stance';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_VERTICALSIDEBARINNER_SINGLE_STANCE,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_VERTICALSIDEBARINNER_SINGLE_STANCE:
                $ret = array_merge(
                    $ret,
                    UserStance_FullViewSidebarSettings::getSidebarSubcomponents(GD_SIDEBARSECTION_STANCE)
                );
                break;
        }

        return $ret;
    }
}



