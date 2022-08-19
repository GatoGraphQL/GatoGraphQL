<?php

class PoP_LocationPosts_Coauthors_Module_Processor_SidebarMultiples extends PoP_Module_Processor_SidebarMultiplesBase
{
    public final const COMPONENT_MULTIPLE_SINGLE_LOCATIONPOST_POSTAUTHORSSIDEBAR = 'multiple-single-locationpost-postauthorssidebar';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_MULTIPLE_SINGLE_LOCATIONPOST_POSTAUTHORSSIDEBAR,
        );
    }

    public function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_MULTIPLE_SINGLE_LOCATIONPOST_POSTAUTHORSSIDEBAR:
                $filters = array(
                    self::COMPONENT_MULTIPLE_SINGLE_LOCATIONPOST_POSTAUTHORSSIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::COMPONENT_MULTIPLE_SECTIONINNER_USERS_NOFILTER_SIDEBAR],
                );
                $ret[] = $filters[$component->name];
                $ret[] = [PoP_LocationPosts_Module_Processor_CustomSidebarDataloads::class, PoP_LocationPosts_Module_Processor_CustomSidebarDataloads::COMPONENT_DATALOAD_SINGLE_LOCATIONPOST_SIDEBAR];
                break;
        }

        return $ret;
    }

    public function getScreen(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_MULTIPLE_SINGLE_LOCATIONPOST_POSTAUTHORSSIDEBAR:
                return POP_SCREEN_SINGLEUSERS;
        }
        
        return parent::getScreen($component);
    }

    public function getScreengroup(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_MULTIPLE_SINGLE_LOCATIONPOST_POSTAUTHORSSIDEBAR:
                return POP_SCREENGROUP_CONTENTREAD;
        }
        
        return parent::getScreengroup($component);
    }
}


