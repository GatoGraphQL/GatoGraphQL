<?php
use PoP\ComponentModel\State\ApplicationState;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\CustomPosts\Types\Status;

class UserStance_Module_Processor_SingleSectionTabPanelBlocks extends PoP_Module_Processor_SingleTabPanelSectionBlocksBase
{
    public const MODULE_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT = 'block-tabpanel-singlerelatedstancecontent';
    public const MODULE_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT_PRO = 'block-tabpanel-singlerelatedstancecontent-pro';
    public const MODULE_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT_AGAINST = 'block-tabpanel-singlerelatedstancecontent-against';
    public const MODULE_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT_NEUTRAL = 'block-tabpanel-singlerelatedstancecontent-neutral';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT],
            [self::class, self::MODULE_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT_PRO],
            [self::class, self::MODULE_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT_AGAINST],
            [self::class, self::MODULE_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT_NEUTRAL],
        );
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        $inners = array(
            self::MODULE_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT => [UserStance_Module_Processor_SingleSectionTabPanelComponents::class, UserStance_Module_Processor_SingleSectionTabPanelComponents::MODULE_TABPANEL_SINGLERELATEDSTANCECONTENT],
            self::MODULE_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT_PRO => [UserStance_Module_Processor_SingleSectionTabPanelComponents::class, UserStance_Module_Processor_SingleSectionTabPanelComponents::MODULE_TABPANEL_SINGLERELATEDSTANCECONTENT_PRO],
            self::MODULE_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT_AGAINST => [UserStance_Module_Processor_SingleSectionTabPanelComponents::class, UserStance_Module_Processor_SingleSectionTabPanelComponents::MODULE_TABPANEL_SINGLERELATEDSTANCECONTENT_AGAINST],
            self::MODULE_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT_NEUTRAL => [UserStance_Module_Processor_SingleSectionTabPanelComponents::class, UserStance_Module_Processor_SingleSectionTabPanelComponents::MODULE_TABPANEL_SINGLERELATEDSTANCECONTENT_NEUTRAL],
        );
        if ($inner = $inners[$module[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    protected function getControlgroupBottomSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT:
            case self::MODULE_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT_PRO:
            case self::MODULE_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT_AGAINST:
            case self::MODULE_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT_NEUTRAL:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_SUBMENUPOSTLIST];
        }

        return parent::getControlgroupBottomSubmodule($module);
    }

    public function initRequestProps(array $module, array &$props): void
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $vars = ApplicationState::getVars();
        switch ($module[1]) {
            case self::MODULE_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT:
            case self::MODULE_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT_PRO:
            case self::MODULE_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT_AGAINST:
            case self::MODULE_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT_NEUTRAL:
                $post_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                if ($customPostTypeAPI->getStatus($post_id) !== Status::PUBLISHED) {
                    $this->setProp($module, $props, 'show-controls-bottom', false);
                }
                break;
        }

        parent::initRequestProps($module, $props);
    }

    public function getDelegatorfilterSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT:
                return [UserStance_Module_Processor_CustomFilters::class, UserStance_Module_Processor_CustomFilters::MODULE_FILTER_STANCES];

            case self::MODULE_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT_PRO:
            case self::MODULE_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT_AGAINST:
            case self::MODULE_BLOCK_TABPANEL_SINGLERELATEDSTANCECONTENT_NEUTRAL:
                return [UserStance_Module_Processor_CustomFilters::class, UserStance_Module_Processor_CustomFilters::MODULE_FILTER_STANCES_STANCE];
        }

        return parent::getDelegatorfilterSubmodule($module);
    }
}


