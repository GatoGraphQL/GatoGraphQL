<?php

use PoP\ComponentModel\State\ApplicationState;

class PoP_Module_Processor_AuthorTabPanelSectionBlocks extends PoP_Module_Processor_AuthorTabPanelSectionBlocksBase
{
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORCONTENT = 'block-tabpanel-authorcontent';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORPOSTS = 'block-tabpanel-authorposts';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORFOLLOWERS = 'block-tabpanel-authorfollowers';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORFOLLOWINGUSERS = 'block-tabpanel-authorfollowingusers';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORSUBSCRIBEDTOTAGS = 'block-tabpanel-authorsubscribedtotags';
    public final const COMPONENT_BLOCK_TABPANEL_AUTHORRECOMMENDEDPOSTS = 'block-tabpanel-authorrecommendedposts';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_BLOCK_TABPANEL_AUTHORCONTENT,
            self::COMPONENT_BLOCK_TABPANEL_AUTHORPOSTS,
            self::COMPONENT_BLOCK_TABPANEL_AUTHORFOLLOWERS,
            self::COMPONENT_BLOCK_TABPANEL_AUTHORFOLLOWINGUSERS,
            self::COMPONENT_BLOCK_TABPANEL_AUTHORSUBSCRIBEDTOTAGS,
            self::COMPONENT_BLOCK_TABPANEL_AUTHORRECOMMENDEDPOSTS,
        );
    }

    protected function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        if (defined('POP_USERCOMMUNITIESPROCESSORS_INITIALIZED')) {
            $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
            if (gdUreIsCommunity($author)) {
                switch ($component->name) {
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORCONTENT:
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORPOSTS:
                        $ret[] = [GD_URE_Module_Processor_ControlGroups::class, GD_URE_Module_Processor_ControlGroups::COMPONENT_URE_CONTROLGROUP_CONTENTSOURCE];
                        break;
                }
            }
        }

        $inners = array(
            self::COMPONENT_BLOCK_TABPANEL_AUTHORCONTENT => [PoP_Module_Processor_AuthorSectionTabPanelComponents::class, PoP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORCONTENT],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORPOSTS => [PoP_Module_Processor_AuthorSectionTabPanelComponents::class, PoP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORPOSTS],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORFOLLOWERS => [PoP_Module_Processor_AuthorSectionTabPanelComponents::class, PoP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORFOLLOWERS],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORFOLLOWINGUSERS => [PoP_Module_Processor_AuthorSectionTabPanelComponents::class, PoP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORFOLLOWINGUSERS],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORSUBSCRIBEDTOTAGS => [PoP_Module_Processor_AuthorSectionTabPanelComponents::class, PoP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORSUBSCRIBEDTOTAGS],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORRECOMMENDEDPOSTS => [PoP_Module_Processor_AuthorSectionTabPanelComponents::class, PoP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORRECOMMENDEDPOSTS],
        );
        if ($inner = $inners[$component->name] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getDelegatorfilterSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_BLOCK_TABPANEL_AUTHORCONTENT:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::COMPONENT_FILTER_AUTHORCONTENT];

            case self::COMPONENT_BLOCK_TABPANEL_AUTHORPOSTS:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::COMPONENT_FILTER_AUTHORPOSTS];

            case self::COMPONENT_BLOCK_TABPANEL_AUTHORRECOMMENDEDPOSTS:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::COMPONENT_FILTER_CONTENT];

            case self::COMPONENT_BLOCK_TABPANEL_AUTHORFOLLOWERS:
            case self::COMPONENT_BLOCK_TABPANEL_AUTHORFOLLOWINGUSERS:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::COMPONENT_FILTER_USERS];

            case self::COMPONENT_BLOCK_TABPANEL_AUTHORSUBSCRIBEDTOTAGS:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::COMPONENT_FILTER_TAGS];
        }

        return parent::getDelegatorfilterSubcomponent($component);
    }
}


