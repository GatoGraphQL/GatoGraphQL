<?php

class PoP_Module_Processor_TabPanelSectionBlocks extends PoP_Module_Processor_SectionBlocksBase
{
    public final const MODULE_BLOCK_TABPANEL_SEARCHCONTENT = 'block-searchcontent-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_CONTENT = 'block-content-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_POSTS = 'block-posts-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_SEARCHUSERS = 'block-searchusers-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_USERS = 'block-users-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_MYCONTENT = 'block-mycontent-tabpanel';
    public final const MODULE_BLOCK_TABPANEL_MYPOSTS = 'block-myposts-tabpanel';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_TABPANEL_SEARCHCONTENT],
            [self::class, self::MODULE_BLOCK_TABPANEL_CONTENT],
            [self::class, self::MODULE_BLOCK_TABPANEL_POSTS],

            [self::class, self::MODULE_BLOCK_TABPANEL_SEARCHUSERS],
            [self::class, self::MODULE_BLOCK_TABPANEL_USERS],

            [self::class, self::MODULE_BLOCK_TABPANEL_MYCONTENT],
            [self::class, self::MODULE_BLOCK_TABPANEL_MYPOSTS],
        );
    }

    public function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);

        $inners = array(
            self::MODULE_BLOCK_TABPANEL_SEARCHCONTENT => [PoP_Module_Processor_SectionTabPanelComponents::class, PoP_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_SEARCHCONTENT],
            self::MODULE_BLOCK_TABPANEL_CONTENT => [PoP_Module_Processor_SectionTabPanelComponents::class, PoP_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_CONTENT],
            self::MODULE_BLOCK_TABPANEL_POSTS => [PoP_Module_Processor_SectionTabPanelComponents::class, PoP_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_POSTS],
            self::MODULE_BLOCK_TABPANEL_SEARCHUSERS => [PoP_Module_Processor_SectionTabPanelComponents::class, PoP_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_SEARCHUSERS],
            self::MODULE_BLOCK_TABPANEL_USERS => [PoP_Module_Processor_SectionTabPanelComponents::class, PoP_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_USERS],
            self::MODULE_BLOCK_TABPANEL_MYCONTENT => [PoP_Module_Processor_SectionTabPanelComponents::class, PoP_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_MYCONTENT],
            self::MODULE_BLOCK_TABPANEL_MYPOSTS => [PoP_Module_Processor_SectionTabPanelComponents::class, PoP_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_MYPOSTS],
        );
        if ($inner = $inners[$componentVariation[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getDelegatorfilterSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_TABPANEL_SEARCHCONTENT:
            case self::MODULE_BLOCK_TABPANEL_CONTENT:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_CONTENT];

            case self::MODULE_BLOCK_TABPANEL_POSTS:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_POSTS];

            case self::MODULE_BLOCK_TABPANEL_USERS:
            case self::MODULE_BLOCK_TABPANEL_SEARCHUSERS:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_USERS];

            case self::MODULE_BLOCK_TABPANEL_MYCONTENT:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_MYCONTENT];

            case self::MODULE_BLOCK_TABPANEL_MYPOSTS:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_MYPOSTS];
        }

        return parent::getDelegatorfilterSubmodule($componentVariation);
    }


    protected function getControlgroupTopSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_TABPANEL_SEARCHCONTENT:
            case self::MODULE_BLOCK_TABPANEL_CONTENT:
            case self::MODULE_BLOCK_TABPANEL_POSTS:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_POSTLIST];

            case self::MODULE_BLOCK_TABPANEL_USERS:
            case self::MODULE_BLOCK_TABPANEL_SEARCHUSERS:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_USERLIST];

            case self::MODULE_BLOCK_TABPANEL_MYCONTENT:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_MYCUSTOMPOSTLIST];

            case self::MODULE_BLOCK_TABPANEL_MYPOSTS:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_MYPOSTLIST];
        }

        return parent::getControlgroupTopSubmodule($componentVariation);
    }
}


