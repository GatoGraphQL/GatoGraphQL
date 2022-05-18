<?php

class PoP_LocationPosts_RelatedContent_Module_Processor_SidebarMultiples extends PoP_Module_Processor_SidebarMultiplesBase
{
    public final const MODULE_MULTIPLE_SINGLE_LOCATIONPOST_RELATEDCONTENTSIDEBAR = 'multiple-single-locationpost-relatedcontentsidebar';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTIPLE_SINGLE_LOCATIONPOST_RELATEDCONTENTSIDEBAR],
        );
    }

    public function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_MULTIPLE_SINGLE_LOCATIONPOST_RELATEDCONTENTSIDEBAR:
                $filters = array(
                    self::MODULE_MULTIPLE_SINGLE_LOCATIONPOST_RELATEDCONTENTSIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::MODULE_MULTIPLE_SECTIONINNER_CONTENT_SIDEBAR],
                );
                $ret[] = $filters[$module[1]];
                $ret[] = [PoP_LocationPosts_Module_Processor_CustomSidebarDataloads::class, PoP_LocationPosts_Module_Processor_CustomSidebarDataloads::MODULE_DATALOAD_SINGLE_LOCATIONPOST_SIDEBAR];
                break;
        }

        return $ret;
    }

    public function getScreen(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_MULTIPLE_SINGLE_LOCATIONPOST_RELATEDCONTENTSIDEBAR:
                return POP_SCREEN_SINGLESECTION;
        }
        
        return parent::getScreen($module);
    }

    public function getScreengroup(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_MULTIPLE_SINGLE_LOCATIONPOST_RELATEDCONTENTSIDEBAR:
                return POP_SCREENGROUP_CONTENTREAD;
        }
        
        return parent::getScreengroup($module);
    }
}


