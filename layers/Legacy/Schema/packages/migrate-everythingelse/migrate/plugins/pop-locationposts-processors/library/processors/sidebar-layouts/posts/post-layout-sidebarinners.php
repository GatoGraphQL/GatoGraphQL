<?php

class GD_Custom_EM_Module_Processor_CustomPostLayoutSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public final const COMPONENT_LAYOUT_POSTSIDEBARINNER_VERTICAL_LOCATIONPOST = 'layout-postsidebarinner-vertical-locationpost';
    public final const COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_LOCATIONPOST = 'layout-postsidebarinner-horizontal-locationpost';
    public final const COMPONENT_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_LOCATIONPOST = 'layout-postsidebarinner-compacthorizontal-locationpost';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_POSTSIDEBARINNER_VERTICAL_LOCATIONPOST,
            self::COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_LOCATIONPOST,
            self::COMPONENT_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_LOCATIONPOST,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_VERTICAL_LOCATIONPOST:
            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_LOCATIONPOST:
                $ret = array_merge(
                    $ret,
                    Custom_EM_FullViewSidebarSettings::getSidebarSubcomponents(GD_SIDEBARSECTION_LOCATIONPOST)
                );
                break;

            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_LOCATIONPOST:
                $ret = array_merge(
                    $ret,
                    Custom_EM_FullViewSidebarSettings::getSidebarSubcomponents(GD_COMPACTSIDEBARSECTION_LOCATIONPOST)
                );
                break;
        }
        
        return $ret;
    }

    public function getWrapperClass(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_LOCATIONPOST:
            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_LOCATIONPOST:
                return 'row';
        }
    
        return parent::getWrapperClass($component);
    }
    
    public function getWidgetwrapperClass(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_LOCATIONPOST:
                return 'col-xsm-4';
            
            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_LOCATIONPOST:
                return 'col-xsm-6';
        }
    
        return parent::getWidgetwrapperClass($component);
    }
}



