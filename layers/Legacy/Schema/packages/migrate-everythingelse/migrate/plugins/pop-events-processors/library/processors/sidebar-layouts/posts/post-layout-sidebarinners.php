<?php

class GD_EM_Module_Processor_CustomPostLayoutSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public final const COMPONENT_LAYOUT_POSTSIDEBARINNER_VERTICAL_EVENT = 'layout-postsidebarinner-vertical-event';
    public final const COMPONENT_LAYOUT_POSTSIDEBARINNER_VERTICAL_PASTEVENT = 'layout-postsidebarinner-vertical-pastevent';
    public final const COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_EVENT = 'layout-postsidebarinner-horizontal-event';
    public final const COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_PASTEVENT = 'layout-postsidebarinner-horizontal-pastevent';
    public final const COMPONENT_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_EVENT = 'layout-postsidebarinner-compacthorizontal-event';
    public final const COMPONENT_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_PASTEVENT = 'layout-postsidebarinner-compacthorizontal-pastevent';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_POSTSIDEBARINNER_VERTICAL_EVENT],
            [self::class, self::COMPONENT_LAYOUT_POSTSIDEBARINNER_VERTICAL_PASTEVENT],
            [self::class, self::COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_EVENT],
            [self::class, self::COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_PASTEVENT],
            [self::class, self::COMPONENT_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_EVENT],
            [self::class, self::COMPONENT_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_PASTEVENT],
        );
    }

    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_VERTICAL_EVENT:
            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_EVENT:
                $ret = array_merge(
                    $ret,
                    EM_FullViewSidebarSettings::getSidebarSubcomponents(GD_SIDEBARSECTION_EVENT)
                );
                break;

            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_VERTICAL_PASTEVENT:
            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_PASTEVENT:
                $ret = array_merge(
                    $ret,
                    EM_FullViewSidebarSettings::getSidebarSubcomponents(GD_SIDEBARSECTION_PASTEVENT)
                );
                break;

            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_EVENT:
                $ret = array_merge(
                    $ret,
                    EM_FullViewSidebarSettings::getSidebarSubcomponents(GD_COMPACTSIDEBARSECTION_EVENT)
                );
                break;

            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_PASTEVENT:
                $ret = array_merge(
                    $ret,
                    EM_FullViewSidebarSettings::getSidebarSubcomponents(GD_COMPACTSIDEBARSECTION_PASTEVENT)
                );
                break;
        }
        
        return $ret;
    }

    public function getWrapperClass(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_EVENT:
            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_PASTEVENT:
            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_EVENT:
            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_PASTEVENT:
                return 'row';
        }
    
        return parent::getWrapperClass($component);
    }
    
    public function getWidgetwrapperClass(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_EVENT:
            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_PASTEVENT:
                return 'col-xsm-4';
            
            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_EVENT:
            case self::COMPONENT_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_PASTEVENT:
                return 'col-xsm-6';
        }
    
        return parent::getWidgetwrapperClass($component);
    }
}



