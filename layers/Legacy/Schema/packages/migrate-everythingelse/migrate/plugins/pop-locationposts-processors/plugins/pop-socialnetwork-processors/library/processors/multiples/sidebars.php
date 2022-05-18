<?php

class PoP_LocationPosts_SocialNetwork_Module_Processor_SidebarMultiples extends PoP_Module_Processor_SidebarMultiplesBase
{
    public final const MODULE_MULTIPLE_SINGLE_LOCATIONPOST_RECOMMENDEDBYSIDEBAR = 'multiple-single-locationpost-recommendedbysidebar';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTIPLE_SINGLE_LOCATIONPOST_RECOMMENDEDBYSIDEBAR],
        );
    }

    public function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        switch ($component[1]) {
            case self::MODULE_MULTIPLE_SINGLE_LOCATIONPOST_RECOMMENDEDBYSIDEBAR:
                $filters = array(
                    self::MODULE_MULTIPLE_SINGLE_LOCATIONPOST_RECOMMENDEDBYSIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::MODULE_MULTIPLE_SECTIONINNER_USERS_SIDEBAR],
                );
                $ret[] = $filters[$component[1]];
                $ret[] = [PoP_LocationPosts_Module_Processor_CustomSidebarDataloads::class, PoP_LocationPosts_Module_Processor_CustomSidebarDataloads::MODULE_DATALOAD_SINGLE_LOCATIONPOST_SIDEBAR];
                break;
        }

        return $ret;
    }

    public function getScreen(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_MULTIPLE_SINGLE_LOCATIONPOST_RECOMMENDEDBYSIDEBAR:
                return POP_SCREEN_SINGLEUSERS;
        }
        
        return parent::getScreen($component);
    }

    public function getScreengroup(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_MULTIPLE_SINGLE_LOCATIONPOST_RECOMMENDEDBYSIDEBAR:
                return POP_SCREENGROUP_CONTENTREAD;
        }
        
        return parent::getScreengroup($component);
    }
}


