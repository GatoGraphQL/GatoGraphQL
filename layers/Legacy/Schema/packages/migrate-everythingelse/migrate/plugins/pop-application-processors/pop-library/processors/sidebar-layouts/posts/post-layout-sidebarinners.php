<?php

class PoP_Module_Processor_CustomPostLayoutSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public final const COMPONENT_LAYOUT_POSTSIDEBARINNER_VERTICAL = 'layout-postsidebarinner-vertical';
    public final const COMPONENT_LAYOUT_POSTSIDEBARINNER_VERTICAL_HIGHLIGHT = 'layout-postsidebarinner-vertical-highlight';
    public final const COMPONENT_LAYOUT_POSTSIDEBARINNER_VERTICAL_POST = 'layout-postsidebarinner-vertical-post';
    public final const COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL = 'layout-postsidebarinner-horizontal';
    public final const COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_HIGHLIGHT = 'layout-postsidebarinner-horizontal-highlight';
    public final const COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_POST = 'layout-postsidebarinner-horizontal-post';
    public final const COMPONENT_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL = 'layout-postsidebarinner-compacthorizontal';
    public final const COMPONENT_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_HIGHLIGHT = 'layout-postsidebarinner-compacthorizontal-highlight';
    public final const COMPONENT_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_POST = 'layout-postsidebarinner-compacthorizontal-post';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_POSTSIDEBARINNER_VERTICAL,
            self::COMPONENT_LAYOUT_POSTSIDEBARINNER_VERTICAL_HIGHLIGHT,
            self::COMPONENT_LAYOUT_POSTSIDEBARINNER_VERTICAL_POST,
            self::COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL,
            self::COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_HIGHLIGHT,
            self::COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_POST,
            self::COMPONENT_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL,
            self::COMPONENT_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_HIGHLIGHT,
            self::COMPONENT_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_POST,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_VERTICAL:
            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL:
                $ret = array_merge(
                    $ret,
                    FullViewSidebarSettings::getSidebarSubcomponents(GD_SIDEBARSECTION_GENERIC)
                );
                break;

            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_VERTICAL_HIGHLIGHT:
            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_HIGHLIGHT:
                $ret = array_merge(
                    $ret,
                    FullViewSidebarSettings::getSidebarSubcomponents(GD_SIDEBARSECTION_HIGHLIGHT)
                );
                break;

            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_VERTICAL_POST:
            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_POST:
                $ret = array_merge(
                    $ret,
                    FullViewSidebarSettings::getSidebarSubcomponents(GD_SIDEBARSECTION_POST)
                );
                break;

            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL:
                $ret = array_merge(
                    $ret,
                    FullViewSidebarSettings::getSidebarSubcomponents(GD_COMPACTSIDEBARSECTION_GENERIC)
                );
                break;

            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_HIGHLIGHT:
                $ret = array_merge(
                    $ret,
                    FullViewSidebarSettings::getSidebarSubcomponents(GD_COMPACTSIDEBARSECTION_HIGHLIGHT)
                );
                break;

            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_POST:
                $ret = array_merge(
                    $ret,
                    FullViewSidebarSettings::getSidebarSubcomponents(GD_COMPACTSIDEBARSECTION_POST)
                );
                break;
        }
        
        return $ret;
    }

    public function getWrapperClass(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL:
            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_HIGHLIGHT:
            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_POST:
            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL:
            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_HIGHLIGHT:
            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_POST:
                return 'row';
        }
    
        return parent::getWrapperClass($component);
    }
    
    public function getWidgetwrapperClass(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL:
            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_HIGHLIGHT:
            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_POST:
                return 'col-xsm-4';
            
            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL:
            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_HIGHLIGHT:
            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_POST:
                return 'col-xsm-6';
        }
    
        return parent::getWidgetwrapperClass($component);
    }
}



