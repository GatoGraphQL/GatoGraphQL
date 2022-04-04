<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_TagTabPanelSectionBlocks extends PoP_Module_Processor_TagTabPanelSectionBlocksBase
{
    public final const MODULE_BLOCK_TABPANEL_TAGCONTENT = 'block-tabpanel-tagcontent';
    public final const MODULE_BLOCK_TABPANEL_TAGPOSTS = 'block-tabpanel-tagposts';
    public final const MODULE_BLOCK_TABPANEL_TAGSUBSCRIBERS = 'block-tabpanel-tagsubscribers';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_TABPANEL_TAGCONTENT],
            [self::class, self::MODULE_BLOCK_TABPANEL_TAGPOSTS],
            [self::class, self::MODULE_BLOCK_TABPANEL_TAGSUBSCRIBERS],
        );
    }

    public function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        $inners = array(
            self::MODULE_BLOCK_TABPANEL_TAGCONTENT => [PoP_Module_Processor_TagSectionTabPanelComponents::class, PoP_Module_Processor_TagSectionTabPanelComponents::MODULE_TABPANEL_TAGCONTENT],
            self::MODULE_BLOCK_TABPANEL_TAGPOSTS => [PoP_Module_Processor_TagSectionTabPanelComponents::class, PoP_Module_Processor_TagSectionTabPanelComponents::MODULE_TABPANEL_TAGPOSTS],
            self::MODULE_BLOCK_TABPANEL_TAGSUBSCRIBERS => [PoP_Module_Processor_TagSectionTabPanelComponents::class, PoP_Module_Processor_TagSectionTabPanelComponents::MODULE_TABPANEL_TAGSUBSCRIBERS],
        );
        if ($inner = $inners[$module[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    protected function getControlgroupBottomSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_TABPANEL_TAGSUBSCRIBERS:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_SUBMENUUSERLIST];
        }

        return parent::getControlgroupBottomSubmodule($module);
    }

    public function getTitle(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_TABPANEL_TAGCONTENT:
                return getRouteIcon(POP_BLOG_ROUTE_CONTENT, true).TranslationAPIFacade::getInstance()->__('Latest content', 'poptheme-wassup');
        }

        return parent::getTitle($module, $props);
    }

    protected function getControlgroupTopSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_TABPANEL_TAGCONTENT:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_POSTLIST];
        }

        return parent::getControlgroupTopSubmodule($module);
    }

    public function getDelegatorfilterSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_TABPANEL_TAGCONTENT:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_TAGCONTENT];

            case self::MODULE_BLOCK_TABPANEL_TAGPOSTS:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_TAGPOSTS];

            case self::MODULE_BLOCK_TABPANEL_TAGSUBSCRIBERS:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_USERS];
        }

        return parent::getDelegatorfilterSubmodule($module);
    }
}


