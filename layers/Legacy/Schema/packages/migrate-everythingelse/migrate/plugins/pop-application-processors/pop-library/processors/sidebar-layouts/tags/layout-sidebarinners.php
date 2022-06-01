<?php

class PoP_Module_Processor_CustomTagLayoutSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public final const COMPONENT_LAYOUT_TAGSIDEBARINNER_VERTICAL = 'layout-tagsidebarinner-vertical';
    public final const COMPONENT_LAYOUT_TAGSIDEBARINNER_HORIZONTAL = 'layout-tagsidebarinner-horizontal';
    public final const COMPONENT_LAYOUT_TAGSIDEBARINNER_COMPACTHORIZONTAL = 'layout-tagsidebarinner-compacthorizontal';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_TAGSIDEBARINNER_VERTICAL],
            [self::class, self::COMPONENT_LAYOUT_TAGSIDEBARINNER_HORIZONTAL],
            [self::class, self::COMPONENT_LAYOUT_TAGSIDEBARINNER_COMPACTHORIZONTAL],
        );
    }

    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_TAGSIDEBARINNER_HORIZONTAL:
            case self::COMPONENT_LAYOUT_TAGSIDEBARINNER_VERTICAL:
                $ret = array_merge(
                    $ret,
                    FullTagSidebarSettings::getSidebarSubcomponents(GD_SIDEBARSECTION_TAG)
                );
                break;

            case self::COMPONENT_LAYOUT_TAGSIDEBARINNER_COMPACTHORIZONTAL:
                $ret = array_merge(
                    $ret,
                    FullTagSidebarSettings::getSidebarSubcomponents(GD_COMPACTSIDEBARSECTION_TAG)
                );
                break;
        }
        
        return $ret;
    }

    public function getWrapperClass(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_TAGSIDEBARINNER_HORIZONTAL:
            case self::COMPONENT_LAYOUT_TAGSIDEBARINNER_COMPACTHORIZONTAL:
                return 'row';
        }
    
        return parent::getWrapperClass($component);
    }
    
    public function getWidgetwrapperClass(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_TAGSIDEBARINNER_HORIZONTAL:
                return 'col-xsm-4';

            case self::COMPONENT_LAYOUT_TAGSIDEBARINNER_COMPACTHORIZONTAL:
                return 'col-xsm-6';
        }
    
        return parent::getWidgetwrapperClass($component);
    }
}



