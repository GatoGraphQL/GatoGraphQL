<?php

class PoP_LocationPosts_SocialNetwork_Module_Processor_SidebarMultiples extends PoP_Module_Processor_SidebarMultiplesBase
{
    public final const COMPONENT_MULTIPLE_SINGLE_LOCATIONPOST_RECOMMENDEDBYSIDEBAR = 'multiple-single-locationpost-recommendedbysidebar';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_MULTIPLE_SINGLE_LOCATIONPOST_RECOMMENDEDBYSIDEBAR],
        );
    }

    public function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_MULTIPLE_SINGLE_LOCATIONPOST_RECOMMENDEDBYSIDEBAR:
                $filters = array(
                    self::COMPONENT_MULTIPLE_SINGLE_LOCATIONPOST_RECOMMENDEDBYSIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::COMPONENT_MULTIPLE_SECTIONINNER_USERS_SIDEBAR],
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
            case self::COMPONENT_MULTIPLE_SINGLE_LOCATIONPOST_RECOMMENDEDBYSIDEBAR:
                return POP_SCREEN_SINGLEUSERS;
        }
        
        return parent::getScreen($component);
    }

    public function getScreengroup(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_MULTIPLE_SINGLE_LOCATIONPOST_RECOMMENDEDBYSIDEBAR:
                return POP_SCREENGROUP_CONTENTREAD;
        }
        
        return parent::getScreengroup($component);
    }
}


