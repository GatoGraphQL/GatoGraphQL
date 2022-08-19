<?php

class UserStance_Module_Processor_CustomPostLayoutSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public final const COMPONENT_LAYOUT_POSTSIDEBARINNER_VERTICAL_STANCE = 'layout-postsidebarinner-vertical-stance';
    public final const COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_STANCE = 'layout-postsidebarinner-horizontal-stance';
    public final const COMPONENT_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_STANCE = 'layout-postsidebarinner-compacthorizontal-stance';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_POSTSIDEBARINNER_VERTICAL_STANCE,
            self::COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_STANCE,
            self::COMPONENT_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_STANCE,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_VERTICAL_STANCE:
            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_STANCE:
                $ret = array_merge(
                    $ret,
                    UserStance_FullViewSidebarSettings::getSidebarSubcomponents(GD_SIDEBARSECTION_STANCE)
                );
                break;

            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_STANCE:
                $ret = array_merge(
                    $ret,
                    UserStance_FullViewSidebarSettings::getSidebarSubcomponents(GD_COMPACTSIDEBARSECTION_STANCE)
                );
                break;
        }
        
        return $ret;
    }

    public function getWrapperClass(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_STANCE:
            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_STANCE:
                return 'row';
        }
    
        return parent::getWrapperClass($component);
    }
    
    public function getWidgetwrapperClass(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_STANCE:
                return 'col-xsm-4';
            
            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_STANCE:
                return 'col-xsm-6';
        }
    
        return parent::getWidgetwrapperClass($component);
    }
}



