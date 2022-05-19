<?php
use PoP\ComponentModel\State\ApplicationState;
use PoPCMSSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPCMSSchema\CustomPosts\Types\Status;

class UserStance_Module_Processor_SingleSectionTabPanelBlocks extends PoP_Module_Processor_SingleTabPanelSectionBlocksBase
{
    public final const COMPONENT_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT = 'block-tabpanel-singlerelatedstancecontent';
    public final const COMPONENT_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT_PRO = 'block-tabpanel-singlerelatedstancecontent-pro';
    public final const COMPONENT_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT_AGAINST = 'block-tabpanel-singlerelatedstancecontent-against';
    public final const COMPONENT_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT_NEUTRAL = 'block-tabpanel-singlerelatedstancecontent-neutral';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT_PRO],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT_AGAINST],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT_NEUTRAL],
        );
    }

    protected function getInnerSubcomponents(array $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        $inners = array(
            self::COMPONENT_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT => [UserStance_Module_Processor_SingleSectionTabPanelComponents::class, UserStance_Module_Processor_SingleSectionTabPanelComponents::COMPONENT_TABPANEL_SINGLERELATEDSTANCECONTENT],
            self::COMPONENT_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT_PRO => [UserStance_Module_Processor_SingleSectionTabPanelComponents::class, UserStance_Module_Processor_SingleSectionTabPanelComponents::COMPONENT_TABPANEL_SINGLERELATEDSTANCECONTENT_PRO],
            self::COMPONENT_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT_AGAINST => [UserStance_Module_Processor_SingleSectionTabPanelComponents::class, UserStance_Module_Processor_SingleSectionTabPanelComponents::COMPONENT_TABPANEL_SINGLERELATEDSTANCECONTENT_AGAINST],
            self::COMPONENT_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT_NEUTRAL => [UserStance_Module_Processor_SingleSectionTabPanelComponents::class, UserStance_Module_Processor_SingleSectionTabPanelComponents::COMPONENT_TABPANEL_SINGLERELATEDSTANCECONTENT_NEUTRAL],
        );
        if ($inner = $inners[$component[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    protected function getControlgroupBottomSubcomponent(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT:
            case self::COMPONENT_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT_PRO:
            case self::COMPONENT_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT_AGAINST:
            case self::COMPONENT_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT_NEUTRAL:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_SUBMENUPOSTLIST];
        }

        return parent::getControlgroupBottomSubcomponent($component);
    }

    public function initRequestProps(array $component, array &$props): void
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT:
            case self::COMPONENT_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT_PRO:
            case self::COMPONENT_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT_AGAINST:
            case self::COMPONENT_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT_NEUTRAL:
                $post_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                if ($customPostTypeAPI->getStatus($post_id) !== Status::PUBLISHED) {
                    $this->setProp($component, $props, 'show-controls-bottom', false);
                }
                break;
        }

        parent::initRequestProps($component, $props);
    }

    public function getDelegatorfilterSubcomponent(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT:
                return [UserStance_Module_Processor_CustomFilters::class, UserStance_Module_Processor_CustomFilters::COMPONENT_FILTER_STANCES];

            case self::COMPONENT_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT_PRO:
            case self::COMPONENT_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT_AGAINST:
            case self::COMPONENT_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT_NEUTRAL:
                return [UserStance_Module_Processor_CustomFilters::class, UserStance_Module_Processor_CustomFilters::COMPONENT_FILTER_STANCES_STANCE];
        }

        return parent::getDelegatorfilterSubcomponent($component);
    }
}


