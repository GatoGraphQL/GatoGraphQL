<?php

use PoP\ComponentModel\State\ApplicationState;

class PoP_Module_Processor_AuthorTabPanelSectionBlocks extends PoP_Module_Processor_AuthorTabPanelSectionBlocksBase
{
    public final const MODULE_BLOCK_TABPANEL_AUTHORCONTENT = 'block-tabpanel-authorcontent';
    public final const MODULE_BLOCK_TABPANEL_AUTHORPOSTS = 'block-tabpanel-authorposts';
    public final const MODULE_BLOCK_TABPANEL_AUTHORFOLLOWERS = 'block-tabpanel-authorfollowers';
    public final const MODULE_BLOCK_TABPANEL_AUTHORFOLLOWINGUSERS = 'block-tabpanel-authorfollowingusers';
    public final const MODULE_BLOCK_TABPANEL_AUTHORSUBSCRIBEDTOTAGS = 'block-tabpanel-authorsubscribedtotags';
    public final const MODULE_BLOCK_TABPANEL_AUTHORRECOMMENDEDPOSTS = 'block-tabpanel-authorrecommendedposts';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORCONTENT],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORPOSTS],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORFOLLOWERS],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORFOLLOWINGUSERS],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORSUBSCRIBEDTOTAGS],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORRECOMMENDEDPOSTS],
        );
    }

    protected function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);

        if (defined('POP_USERCOMMUNITIESPROCESSORS_INITIALIZED')) {
            $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
            if (gdUreIsCommunity($author)) {
                switch ($componentVariation[1]) {
                    case self::MODULE_BLOCK_TABPANEL_AUTHORCONTENT:
                    case self::MODULE_BLOCK_TABPANEL_AUTHORPOSTS:
                        $ret[] = [GD_URE_Module_Processor_ControlGroups::class, GD_URE_Module_Processor_ControlGroups::MODULE_URE_CONTROLGROUP_CONTENTSOURCE];
                        break;
                }
            }
        }

        $inners = array(
            self::MODULE_BLOCK_TABPANEL_AUTHORCONTENT => [PoP_Module_Processor_AuthorSectionTabPanelComponents::class, PoP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORCONTENT],
            self::MODULE_BLOCK_TABPANEL_AUTHORPOSTS => [PoP_Module_Processor_AuthorSectionTabPanelComponents::class, PoP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORPOSTS],
            self::MODULE_BLOCK_TABPANEL_AUTHORFOLLOWERS => [PoP_Module_Processor_AuthorSectionTabPanelComponents::class, PoP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORFOLLOWERS],
            self::MODULE_BLOCK_TABPANEL_AUTHORFOLLOWINGUSERS => [PoP_Module_Processor_AuthorSectionTabPanelComponents::class, PoP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORFOLLOWINGUSERS],
            self::MODULE_BLOCK_TABPANEL_AUTHORSUBSCRIBEDTOTAGS => [PoP_Module_Processor_AuthorSectionTabPanelComponents::class, PoP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORSUBSCRIBEDTOTAGS],
            self::MODULE_BLOCK_TABPANEL_AUTHORRECOMMENDEDPOSTS => [PoP_Module_Processor_AuthorSectionTabPanelComponents::class, PoP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORRECOMMENDEDPOSTS],
        );
        if ($inner = $inners[$componentVariation[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getDelegatorfilterSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_TABPANEL_AUTHORCONTENT:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_AUTHORCONTENT];

            case self::MODULE_BLOCK_TABPANEL_AUTHORPOSTS:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_AUTHORPOSTS];

            case self::MODULE_BLOCK_TABPANEL_AUTHORRECOMMENDEDPOSTS:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_CONTENT];

            case self::MODULE_BLOCK_TABPANEL_AUTHORFOLLOWERS:
            case self::MODULE_BLOCK_TABPANEL_AUTHORFOLLOWINGUSERS:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_USERS];

            case self::MODULE_BLOCK_TABPANEL_AUTHORSUBSCRIBEDTOTAGS:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_TAGS];
        }

        return parent::getDelegatorfilterSubmodule($componentVariation);
    }
}


