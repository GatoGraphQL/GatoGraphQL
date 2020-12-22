<?php

class PoP_Events_RelatedPosts_Module_Processor_SidebarMultiples extends PoP_Module_Processor_SidebarMultiplesBase
{
    public const MODULE_MULTIPLE_SINGLE_EVENT_RELATEDCONTENTSIDEBAR = 'multiple-single-event-relatedcontentsidebar';
    public const MODULE_MULTIPLE_SINGLE_PASTEVENT_RELATEDCONTENTSIDEBAR = 'multiple-single-pastevent-relatedcontentsidebar';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTIPLE_SINGLE_EVENT_RELATEDCONTENTSIDEBAR],
            [self::class, self::MODULE_MULTIPLE_SINGLE_PASTEVENT_RELATEDCONTENTSIDEBAR],
        );
    }

    public function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        switch ($module[1]) {
         // Add also the filter block for the Single Related Content, etc
            case self::MODULE_MULTIPLE_SINGLE_EVENT_RELATEDCONTENTSIDEBAR:
                // Comment Leo 27/07/2016: can't have the filter for "POSTAUTHORSSIDEBAR", because to get the authors we do:
                // $ret['include'] = gdGetPostauthors($post_id); (in function addDataloadqueryargsSingleauthors)
                // and the include cannot be filtered. Once it's there, it's final.
                // (And also, it doesn't look so nice to add the filter for the authors, since most likely there is always only 1 author!)
                $filters = array(
                    self::MODULE_MULTIPLE_SINGLE_EVENT_RELATEDCONTENTSIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::MODULE_MULTIPLE_SECTIONINNER_CONTENT_SIDEBAR],
                );
                $ret[] = $filters[$module[1]];
                $ret[] = [PoP_Events_Module_Processor_CustomSidebarDataloads::class, PoP_Events_Module_Processor_CustomSidebarDataloads::MODULE_DATALOAD_SINGLE_EVENT_SIDEBAR];
                break;

            case self::MODULE_MULTIPLE_SINGLE_PASTEVENT_RELATEDCONTENTSIDEBAR:
                $filters = array(
                    self::MODULE_MULTIPLE_SINGLE_PASTEVENT_RELATEDCONTENTSIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::MODULE_MULTIPLE_SECTIONINNER_CONTENT_SIDEBAR],
                );
                $ret[] = $filters[$module[1]];
                $ret[] = [PoP_Events_Module_Processor_CustomSidebarDataloads::class, PoP_Events_Module_Processor_CustomSidebarDataloads::MODULE_DATALOAD_SINGLE_PASTEVENT_SIDEBAR];
                break;
        }

        return $ret;
    }

    public function getScreen(array $module)
    {
        $screens = array(
            self::MODULE_MULTIPLE_SINGLE_EVENT_RELATEDCONTENTSIDEBAR => POP_SCREEN_SINGLESECTION,
            self::MODULE_MULTIPLE_SINGLE_PASTEVENT_RELATEDCONTENTSIDEBAR => POP_SCREEN_SINGLESECTION,
        );
        if ($screen = $screens[$module[1]]) {
            return $screen;
        }
        
        return parent::getScreen($module);
    }

    public function getScreengroup(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_MULTIPLE_SINGLE_EVENT_RELATEDCONTENTSIDEBAR:
            case self::MODULE_MULTIPLE_SINGLE_PASTEVENT_RELATEDCONTENTSIDEBAR:
                return POP_SCREENGROUP_CONTENTREAD;
        }
        
        return parent::getScreengroup($module);
    }
}


