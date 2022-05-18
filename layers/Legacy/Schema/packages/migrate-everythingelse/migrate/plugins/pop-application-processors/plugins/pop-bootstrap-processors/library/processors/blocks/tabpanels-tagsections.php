<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_TagTabPanelSectionBlocks extends PoP_Module_Processor_TagTabPanelSectionBlocksBase
{
    public final const MODULE_BLOCK_TABPANEL_TAGCONTENT = 'block-tabpanel-tagcontent';
    public final const MODULE_BLOCK_TABPANEL_TAGPOSTS = 'block-tabpanel-tagposts';
    public final const MODULE_BLOCK_TABPANEL_TAGSUBSCRIBERS = 'block-tabpanel-tagsubscribers';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_TABPANEL_TAGCONTENT],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_TAGPOSTS],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_TAGSUBSCRIBERS],
        );
    }

    public function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        $inners = array(
            self::COMPONENT_BLOCK_TABPANEL_TAGCONTENT => [PoP_Module_Processor_TagSectionTabPanelComponents::class, PoP_Module_Processor_TagSectionTabPanelComponents::COMPONENT_TABPANEL_TAGCONTENT],
            self::COMPONENT_BLOCK_TABPANEL_TAGPOSTS => [PoP_Module_Processor_TagSectionTabPanelComponents::class, PoP_Module_Processor_TagSectionTabPanelComponents::COMPONENT_TABPANEL_TAGPOSTS],
            self::COMPONENT_BLOCK_TABPANEL_TAGSUBSCRIBERS => [PoP_Module_Processor_TagSectionTabPanelComponents::class, PoP_Module_Processor_TagSectionTabPanelComponents::COMPONENT_TABPANEL_TAGSUBSCRIBERS],
        );
        if ($inner = $inners[$component[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    protected function getControlgroupBottomSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_TABPANEL_TAGSUBSCRIBERS:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_SUBMENUUSERLIST];
        }

        return parent::getControlgroupBottomSubmodule($component);
    }

    public function getTitle(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_TABPANEL_TAGCONTENT:
                return getRouteIcon(POP_BLOG_ROUTE_CONTENT, true).TranslationAPIFacade::getInstance()->__('Latest content', 'poptheme-wassup');
        }

        return parent::getTitle($component, $props);
    }

    protected function getControlgroupTopSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_TABPANEL_TAGCONTENT:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_POSTLIST];
        }

        return parent::getControlgroupTopSubmodule($component);
    }

    public function getDelegatorfilterSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_TABPANEL_TAGCONTENT:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::COMPONENT_FILTER_TAGCONTENT];

            case self::COMPONENT_BLOCK_TABPANEL_TAGPOSTS:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::COMPONENT_FILTER_TAGPOSTS];

            case self::COMPONENT_BLOCK_TABPANEL_TAGSUBSCRIBERS:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::COMPONENT_FILTER_USERS];
        }

        return parent::getDelegatorfilterSubmodule($component);
    }
}


