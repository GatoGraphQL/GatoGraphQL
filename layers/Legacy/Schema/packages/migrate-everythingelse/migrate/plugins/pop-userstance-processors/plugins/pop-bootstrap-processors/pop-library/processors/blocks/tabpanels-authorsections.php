<?php

use PoP\ComponentModel\State\ApplicationState;

class UserStance_Module_Processor_AuthorSectionTabPanelBlocks extends PoP_Module_Processor_AuthorTabPanelSectionBlocksBase
{
    public final const MODULE_BLOCK_TABPANEL_AUTHORSTANCES = 'block-tabpanel-authorstances';
    public final const MODULE_BLOCK_TABPANEL_AUTHORSTANCES_PRO = 'block-tabpanel-authorstances-pro';
    public final const MODULE_BLOCK_TABPANEL_AUTHORSTANCES_NEUTRAL = 'block-tabpanel-authorstances-neutral';
    public final const MODULE_BLOCK_TABPANEL_AUTHORSTANCES_AGAINST = 'block-tabpanel-authorstances-against';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORSTANCES],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORSTANCES_PRO],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORSTANCES_NEUTRAL],
            [self::class, self::COMPONENT_BLOCK_TABPANEL_AUTHORSTANCES_AGAINST],
        );
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        if (defined('POP_USERCOMMUNITIESPROCESSORS_INITIALIZED')) {
            $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
            if (gdUreIsCommunity($author)) {
                switch ($component[1]) {
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORSTANCES:
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORSTANCES_PRO:
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORSTANCES_NEUTRAL:
                    case self::COMPONENT_BLOCK_TABPANEL_AUTHORSTANCES_AGAINST:
                        $ret[] = [GD_URE_Module_Processor_ControlGroups::class, GD_URE_Module_Processor_ControlGroups::COMPONENT_URE_CONTROLGROUP_CONTENTSOURCE];
                        break;
                }
            }
        }

        $inners = array(
            self::COMPONENT_BLOCK_TABPANEL_AUTHORSTANCES => [UserStance_Module_Processor_AuthorSectionTabPanelComponents::class, UserStance_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORSTANCES],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORSTANCES_PRO => [UserStance_Module_Processor_AuthorSectionTabPanelComponents::class, UserStance_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORSTANCES_PRO],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORSTANCES_NEUTRAL => [UserStance_Module_Processor_AuthorSectionTabPanelComponents::class, UserStance_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORSTANCES_NEUTRAL],
            self::COMPONENT_BLOCK_TABPANEL_AUTHORSTANCES_AGAINST => [UserStance_Module_Processor_AuthorSectionTabPanelComponents::class, UserStance_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORSTANCES_AGAINST],
        );
        if ($inner = $inners[$component[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getDelegatorfilterSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_TABPANEL_AUTHORSTANCES:
                return [UserStance_Module_Processor_CustomFilters::class, UserStance_Module_Processor_CustomFilters::COMPONENT_FILTER_AUTHORSTANCES];

            case self::COMPONENT_BLOCK_TABPANEL_AUTHORSTANCES_PRO:
            case self::COMPONENT_BLOCK_TABPANEL_AUTHORSTANCES_NEUTRAL:
            case self::COMPONENT_BLOCK_TABPANEL_AUTHORSTANCES_AGAINST:
                return [UserStance_Module_Processor_CustomFilters::class, UserStance_Module_Processor_CustomFilters::COMPONENT_FILTER_AUTHORSTANCES_STANCE];
        }

        return parent::getDelegatorfilterSubmodule($component);
    }
}


