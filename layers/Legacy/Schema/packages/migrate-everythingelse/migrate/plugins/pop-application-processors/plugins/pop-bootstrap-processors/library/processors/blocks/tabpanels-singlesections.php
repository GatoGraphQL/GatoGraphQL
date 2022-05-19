<?php

use PoP\ComponentModel\State\ApplicationState;
use PoPCMSSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPCMSSchema\CustomPosts\Types\Status;

class PoP_Module_Processor_SingleTabPanelSectionBlocks extends PoP_Module_Processor_SingleTabPanelSectionBlocksBase
{
    public final const COMPONENT_BLOCK_TABPANEL_SINGLERELATEDCONTENT = 'block-tabpanel-singlerelatedcontent';
    public final const COMPONENT_BLOCK_TABPANEL_SINGLEAUTHORS = 'block-tabpanel-singleauthors';
    public final const COMPONENT_BLOCK_TABPANEL_SINGLERECOMMENDEDBY = 'block-tabpanel-singlerecommendedby';
    public final const COMPONENT_BLOCK_TABPANEL_SINGLEUPVOTEDBY = 'block-tabpanel-singleupvotedby';
    public final const COMPONENT_BLOCK_TABPANEL_SINGLEDOWNVOTEDBY = 'block-tabpanel-singledownvotedby';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_TABPANEL_SINGLERELATEDCONTENT],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_SINGLEAUTHORS],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_SINGLERECOMMENDEDBY],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_SINGLEUPVOTEDBY],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_SINGLEDOWNVOTEDBY],
        );
    }

    public function getInnerSubcomponents(array $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        $inners = array(
            self::COMPONENT_BLOCK_TABPANEL_SINGLERELATEDCONTENT => [PoP_Module_Processor_SingleSectionTabPanelComponents::class, PoP_Module_Processor_SingleSectionTabPanelComponents::COMPONENT_TABPANEL_SINGLERELATEDCONTENT],
            self::COMPONENT_BLOCK_TABPANEL_SINGLEAUTHORS => [PoP_Module_Processor_SingleSectionTabPanelComponents::class, PoP_Module_Processor_SingleSectionTabPanelComponents::COMPONENT_TABPANEL_SINGLEAUTHORS],
            self::COMPONENT_BLOCK_TABPANEL_SINGLERECOMMENDEDBY => [PoP_Module_Processor_SingleSectionTabPanelComponents::class, PoP_Module_Processor_SingleSectionTabPanelComponents::COMPONENT_TABPANEL_SINGLERECOMMENDEDBY],
            self::COMPONENT_BLOCK_TABPANEL_SINGLEUPVOTEDBY => [PoP_Module_Processor_SingleSectionTabPanelComponents::class, PoP_Module_Processor_SingleSectionTabPanelComponents::COMPONENT_TABPANEL_SINGLEUPVOTEDBY],
            self::COMPONENT_BLOCK_TABPANEL_SINGLEDOWNVOTEDBY => [PoP_Module_Processor_SingleSectionTabPanelComponents::class, PoP_Module_Processor_SingleSectionTabPanelComponents::COMPONENT_TABPANEL_SINGLEDOWNVOTEDBY],
        );
        if ($inner = $inners[$component[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    protected function getControlgroupBottomSubcomponent(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_TABPANEL_SINGLERELATEDCONTENT:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_SUBMENUPOSTLIST];

         // Single Authors has no filter, so show only the Share control
            case self::COMPONENT_BLOCK_TABPANEL_SINGLEAUTHORS:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_SUBMENUSHARE];

            case self::COMPONENT_BLOCK_TABPANEL_SINGLERECOMMENDEDBY:
            case self::COMPONENT_BLOCK_TABPANEL_SINGLEUPVOTEDBY:
            case self::COMPONENT_BLOCK_TABPANEL_SINGLEDOWNVOTEDBY:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_SUBMENUUSERLIST];
        }

        return parent::getControlgroupBottomSubcomponent($component);
    }

    public function getDelegatorfilterSubcomponent(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_TABPANEL_SINGLERELATEDCONTENT:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::COMPONENT_FILTER_CONTENT];

            case self::COMPONENT_BLOCK_TABPANEL_SINGLERECOMMENDEDBY:
            case self::COMPONENT_BLOCK_TABPANEL_SINGLEUPVOTEDBY:
            case self::COMPONENT_BLOCK_TABPANEL_SINGLEDOWNVOTEDBY:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::COMPONENT_FILTER_USERS];
        }

        return parent::getDelegatorfilterSubcomponent($component);
    }


    public function initRequestProps(array $component, array &$props): void
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_TABPANEL_SINGLERELATEDCONTENT:
            case self::COMPONENT_BLOCK_TABPANEL_SINGLEAUTHORS:
            case self::COMPONENT_BLOCK_TABPANEL_SINGLERECOMMENDEDBY:
            case self::COMPONENT_BLOCK_TABPANEL_SINGLEUPVOTEDBY:
            case self::COMPONENT_BLOCK_TABPANEL_SINGLEDOWNVOTEDBY:
                $post_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                if ($customPostTypeAPI->getStatus($post_id) !== Status::PUBLISHED) {
                    $this->setProp($component, $props, 'show-controls-bottom', false);
                }
                break;
        }

        parent::initRequestProps($component, $props);
    }
}


