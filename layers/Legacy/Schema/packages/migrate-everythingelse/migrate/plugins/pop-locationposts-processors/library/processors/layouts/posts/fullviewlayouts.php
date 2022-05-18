<?php

class GD_Custom_EM_Module_Processor_CustomFullViewLayouts extends PoP_Module_Processor_CustomFullViewLayoutsBase
{
    public final const COMPONENT_LAYOUT_FULLVIEW_LOCATIONPOST = 'layout-fullview-locationpost';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_FULLVIEW_LOCATIONPOST],
        );
    }


    
    public function getFooterSubmodules(array $component)
    {
        $ret = parent::getFooterSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_FULLVIEW_LOCATIONPOST:
                $ret[] = [PoP_Module_Processor_ViewComponentButtonWrappers::class, PoP_Module_Processor_ViewComponentButtonWrappers::COMPONENT_LAYOUTWRAPPER_POSTCONCLUSIONSIDEBAR_HORIZONTAL];
                $ret[] = [PoP_Module_Processor_CustomWrapperLayouts::class, PoP_Module_Processor_CustomWrapperLayouts::COMPONENT_LAYOUTWRAPPER_USERPOSTINTERACTION];
                $ret[] = [PoP_Module_Processor_CustomWrapperLayouts::class, PoP_Module_Processor_CustomWrapperLayouts::COMPONENT_CODEWRAPPER_LAZYLOADINGSPINNER];
                $ret[] = [PoP_Module_Processor_HighlightReferencedbyLayouts::class, PoP_Module_Processor_HighlightReferencedbyLayouts::COMPONENT_LAZYSUBCOMPONENT_HIGHLIGHTS];
                $ret[] = [PoP_Module_Processor_ReferencedbyLayouts::class, PoP_Module_Processor_ReferencedbyLayouts::COMPONENT_LAZYSUBCOMPONENT_REFERENCEDBY];
                $ret[] = [PoP_Module_Processor_PostCommentSubcomponentLayouts::class, PoP_Module_Processor_PostCommentSubcomponentLayouts::COMPONENT_LAZYSUBCOMPONENT_POSTCOMMENTS];
                break;
        }

        return $ret;
    }

    public function getSidebarSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_FULLVIEW_LOCATIONPOST:
                $sidebars = array(
                    self::COMPONENT_LAYOUT_FULLVIEW_LOCATIONPOST => [GD_Custom_EM_Module_Processor_CustomPostLayoutSidebars::class, GD_Custom_EM_Module_Processor_CustomPostLayoutSidebars::COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_LOCATIONPOST],
                );

                return $sidebars[$component[1]];
        }

        return parent::getSidebarSubmodule($component);
    }
}



