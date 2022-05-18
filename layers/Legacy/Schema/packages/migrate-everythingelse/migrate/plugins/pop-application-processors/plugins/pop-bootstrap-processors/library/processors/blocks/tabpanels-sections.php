<?php

class PoP_Module_Processor_TabPanelSectionBlocks extends PoP_Module_Processor_SectionBlocksBase
{
    public final const COMPONENT_BLOCK_TABPANEL_SEARCHCONTENT = 'block-searchcontent-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_CONTENT = 'block-content-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_POSTS = 'block-posts-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_SEARCHUSERS = 'block-searchusers-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_USERS = 'block-users-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_MYCONTENT = 'block-mycontent-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_MYPOSTS = 'block-myposts-tabpanel';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_TABPANEL_SEARCHCONTENT],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_CONTENT],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_POSTS],

            [self::class, self::COMPONENT_BLOCK_TABPANEL_SEARCHUSERS],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_USERS],

            [self::class, self::COMPONENT_BLOCK_TABPANEL_MYCONTENT],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_MYPOSTS],
        );
    }

    public function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        $inners = array(
            self::COMPONENT_BLOCK_TABPANEL_SEARCHCONTENT => [PoP_Module_Processor_SectionTabPanelComponents::class, PoP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_SEARCHCONTENT],
            self::COMPONENT_BLOCK_TABPANEL_CONTENT => [PoP_Module_Processor_SectionTabPanelComponents::class, PoP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_CONTENT],
            self::COMPONENT_BLOCK_TABPANEL_POSTS => [PoP_Module_Processor_SectionTabPanelComponents::class, PoP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_POSTS],
            self::COMPONENT_BLOCK_TABPANEL_SEARCHUSERS => [PoP_Module_Processor_SectionTabPanelComponents::class, PoP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_SEARCHUSERS],
            self::COMPONENT_BLOCK_TABPANEL_USERS => [PoP_Module_Processor_SectionTabPanelComponents::class, PoP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_USERS],
            self::COMPONENT_BLOCK_TABPANEL_MYCONTENT => [PoP_Module_Processor_SectionTabPanelComponents::class, PoP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_MYCONTENT],
            self::COMPONENT_BLOCK_TABPANEL_MYPOSTS => [PoP_Module_Processor_SectionTabPanelComponents::class, PoP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_MYPOSTS],
        );
        if ($inner = $inners[$component[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getDelegatorfilterSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_TABPANEL_SEARCHCONTENT:
            case self::COMPONENT_BLOCK_TABPANEL_CONTENT:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::COMPONENT_FILTER_CONTENT];

            case self::COMPONENT_BLOCK_TABPANEL_POSTS:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::COMPONENT_FILTER_POSTS];

            case self::COMPONENT_BLOCK_TABPANEL_USERS:
            case self::COMPONENT_BLOCK_TABPANEL_SEARCHUSERS:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::COMPONENT_FILTER_USERS];

            case self::COMPONENT_BLOCK_TABPANEL_MYCONTENT:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::COMPONENT_FILTER_MYCONTENT];

            case self::COMPONENT_BLOCK_TABPANEL_MYPOSTS:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::COMPONENT_FILTER_MYPOSTS];
        }

        return parent::getDelegatorfilterSubmodule($component);
    }


    protected function getControlgroupTopSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_TABPANEL_SEARCHCONTENT:
            case self::COMPONENT_BLOCK_TABPANEL_CONTENT:
            case self::COMPONENT_BLOCK_TABPANEL_POSTS:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_POSTLIST];

            case self::COMPONENT_BLOCK_TABPANEL_USERS:
            case self::COMPONENT_BLOCK_TABPANEL_SEARCHUSERS:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_USERLIST];

            case self::COMPONENT_BLOCK_TABPANEL_MYCONTENT:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_MYCUSTOMPOSTLIST];

            case self::COMPONENT_BLOCK_TABPANEL_MYPOSTS:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_MYPOSTLIST];
        }

        return parent::getControlgroupTopSubmodule($component);
    }
}


