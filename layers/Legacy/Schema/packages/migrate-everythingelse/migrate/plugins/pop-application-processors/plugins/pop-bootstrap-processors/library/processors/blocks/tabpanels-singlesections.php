<?php

use PoP\ComponentModel\State\ApplicationState;
use PoPCMSSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPCMSSchema\CustomPosts\Types\Status;

class PoP_Module_Processor_SingleTabPanelSectionBlocks extends PoP_Module_Processor_SingleTabPanelSectionBlocksBase
{
    public final const MODULE_BLOCK_TABPANEL_SINGLERELATEDCONTENT = 'block-tabpanel-singlerelatedcontent';
    public final const MODULE_BLOCK_TABPANEL_SINGLEAUTHORS = 'block-tabpanel-singleauthors';
    public final const MODULE_BLOCK_TABPANEL_SINGLERECOMMENDEDBY = 'block-tabpanel-singlerecommendedby';
    public final const MODULE_BLOCK_TABPANEL_SINGLEUPVOTEDBY = 'block-tabpanel-singleupvotedby';
    public final const MODULE_BLOCK_TABPANEL_SINGLEDOWNVOTEDBY = 'block-tabpanel-singledownvotedby';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_TABPANEL_SINGLERELATEDCONTENT],
            [self::class, self::MODULE_BLOCK_TABPANEL_SINGLEAUTHORS],
            [self::class, self::MODULE_BLOCK_TABPANEL_SINGLERECOMMENDEDBY],
            [self::class, self::MODULE_BLOCK_TABPANEL_SINGLEUPVOTEDBY],
            [self::class, self::MODULE_BLOCK_TABPANEL_SINGLEDOWNVOTEDBY],
        );
    }

    public function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        $inners = array(
            self::MODULE_BLOCK_TABPANEL_SINGLERELATEDCONTENT => [PoP_Module_Processor_SingleSectionTabPanelComponents::class, PoP_Module_Processor_SingleSectionTabPanelComponents::MODULE_TABPANEL_SINGLERELATEDCONTENT],
            self::MODULE_BLOCK_TABPANEL_SINGLEAUTHORS => [PoP_Module_Processor_SingleSectionTabPanelComponents::class, PoP_Module_Processor_SingleSectionTabPanelComponents::MODULE_TABPANEL_SINGLEAUTHORS],
            self::MODULE_BLOCK_TABPANEL_SINGLERECOMMENDEDBY => [PoP_Module_Processor_SingleSectionTabPanelComponents::class, PoP_Module_Processor_SingleSectionTabPanelComponents::MODULE_TABPANEL_SINGLERECOMMENDEDBY],
            self::MODULE_BLOCK_TABPANEL_SINGLEUPVOTEDBY => [PoP_Module_Processor_SingleSectionTabPanelComponents::class, PoP_Module_Processor_SingleSectionTabPanelComponents::MODULE_TABPANEL_SINGLEUPVOTEDBY],
            self::MODULE_BLOCK_TABPANEL_SINGLEDOWNVOTEDBY => [PoP_Module_Processor_SingleSectionTabPanelComponents::class, PoP_Module_Processor_SingleSectionTabPanelComponents::MODULE_TABPANEL_SINGLEDOWNVOTEDBY],
        );
        if ($inner = $inners[$module[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    protected function getControlgroupBottomSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_TABPANEL_SINGLERELATEDCONTENT:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_SUBMENUPOSTLIST];

         // Single Authors has no filter, so show only the Share control
            case self::MODULE_BLOCK_TABPANEL_SINGLEAUTHORS:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_SUBMENUSHARE];

            case self::MODULE_BLOCK_TABPANEL_SINGLERECOMMENDEDBY:
            case self::MODULE_BLOCK_TABPANEL_SINGLEUPVOTEDBY:
            case self::MODULE_BLOCK_TABPANEL_SINGLEDOWNVOTEDBY:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_SUBMENUUSERLIST];
        }

        return parent::getControlgroupBottomSubmodule($module);
    }

    public function getDelegatorfilterSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_TABPANEL_SINGLERELATEDCONTENT:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_CONTENT];

            case self::MODULE_BLOCK_TABPANEL_SINGLERECOMMENDEDBY:
            case self::MODULE_BLOCK_TABPANEL_SINGLEUPVOTEDBY:
            case self::MODULE_BLOCK_TABPANEL_SINGLEDOWNVOTEDBY:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_USERS];
        }

        return parent::getDelegatorfilterSubmodule($module);
    }


    public function initRequestProps(array $module, array &$props): void
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        switch ($module[1]) {
            case self::MODULE_BLOCK_TABPANEL_SINGLERELATEDCONTENT:
            case self::MODULE_BLOCK_TABPANEL_SINGLEAUTHORS:
            case self::MODULE_BLOCK_TABPANEL_SINGLERECOMMENDEDBY:
            case self::MODULE_BLOCK_TABPANEL_SINGLEUPVOTEDBY:
            case self::MODULE_BLOCK_TABPANEL_SINGLEDOWNVOTEDBY:
                $post_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                if ($customPostTypeAPI->getStatus($post_id) !== Status::PUBLISHED) {
                    $this->setProp($module, $props, 'show-controls-bottom', false);
                }
                break;
        }

        parent::initRequestProps($module, $props);
    }
}


