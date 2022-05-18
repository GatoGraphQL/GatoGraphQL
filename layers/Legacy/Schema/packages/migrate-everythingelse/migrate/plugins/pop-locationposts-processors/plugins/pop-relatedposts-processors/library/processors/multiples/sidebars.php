<?php

class PoP_LocationPosts_RelatedContent_Module_Processor_SidebarMultiples extends PoP_Module_Processor_SidebarMultiplesBase
{
    public final const COMPONENT_MULTIPLE_SINGLE_LOCATIONPOST_RELATEDCONTENTSIDEBAR = 'multiple-single-locationpost-relatedcontentsidebar';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_MULTIPLE_SINGLE_LOCATIONPOST_RELATEDCONTENTSIDEBAR],
        );
    }

    public function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_MULTIPLE_SINGLE_LOCATIONPOST_RELATEDCONTENTSIDEBAR:
                $filters = array(
                    self::COMPONENT_MULTIPLE_SINGLE_LOCATIONPOST_RELATEDCONTENTSIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::COMPONENT_MULTIPLE_SECTIONINNER_CONTENT_SIDEBAR],
                );
                $ret[] = $filters[$component[1]];
                $ret[] = [PoP_LocationPosts_Module_Processor_CustomSidebarDataloads::class, PoP_LocationPosts_Module_Processor_CustomSidebarDataloads::COMPONENT_DATALOAD_SINGLE_LOCATIONPOST_SIDEBAR];
                break;
        }

        return $ret;
    }

    public function getScreen(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_MULTIPLE_SINGLE_LOCATIONPOST_RELATEDCONTENTSIDEBAR:
                return POP_SCREEN_SINGLESECTION;
        }
        
        return parent::getScreen($component);
    }

    public function getScreengroup(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_MULTIPLE_SINGLE_LOCATIONPOST_RELATEDCONTENTSIDEBAR:
                return POP_SCREENGROUP_CONTENTREAD;
        }
        
        return parent::getScreengroup($component);
    }
}


