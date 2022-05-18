<?php

use PoP\ComponentModel\State\ApplicationState;

class UserStance_Module_Processor_AuthorSectionTabPanelBlocks extends PoP_Module_Processor_AuthorTabPanelSectionBlocksBase
{
    public final const MODULE_BLOCK_TABPANEL_AUTHORSTANCES = 'block-tabpanel-authorstances';
    public final const MODULE_BLOCK_TABPANEL_AUTHORSTANCES_PRO = 'block-tabpanel-authorstances-pro';
    public final const MODULE_BLOCK_TABPANEL_AUTHORSTANCES_NEUTRAL = 'block-tabpanel-authorstances-neutral';
    public final const MODULE_BLOCK_TABPANEL_AUTHORSTANCES_AGAINST = 'block-tabpanel-authorstances-against';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORSTANCES],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORSTANCES_PRO],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORSTANCES_NEUTRAL],
            [self::class, self::MODULE_BLOCK_TABPANEL_AUTHORSTANCES_AGAINST],
        );
    }

    protected function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);

        if (defined('POP_USERCOMMUNITIESPROCESSORS_INITIALIZED')) {
            $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
            if (gdUreIsCommunity($author)) {
                switch ($componentVariation[1]) {
                    case self::MODULE_BLOCK_TABPANEL_AUTHORSTANCES:
                    case self::MODULE_BLOCK_TABPANEL_AUTHORSTANCES_PRO:
                    case self::MODULE_BLOCK_TABPANEL_AUTHORSTANCES_NEUTRAL:
                    case self::MODULE_BLOCK_TABPANEL_AUTHORSTANCES_AGAINST:
                        $ret[] = [GD_URE_Module_Processor_ControlGroups::class, GD_URE_Module_Processor_ControlGroups::MODULE_URE_CONTROLGROUP_CONTENTSOURCE];
                        break;
                }
            }
        }

        $inners = array(
            self::MODULE_BLOCK_TABPANEL_AUTHORSTANCES => [UserStance_Module_Processor_AuthorSectionTabPanelComponents::class, UserStance_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORSTANCES],
            self::MODULE_BLOCK_TABPANEL_AUTHORSTANCES_PRO => [UserStance_Module_Processor_AuthorSectionTabPanelComponents::class, UserStance_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORSTANCES_PRO],
            self::MODULE_BLOCK_TABPANEL_AUTHORSTANCES_NEUTRAL => [UserStance_Module_Processor_AuthorSectionTabPanelComponents::class, UserStance_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORSTANCES_NEUTRAL],
            self::MODULE_BLOCK_TABPANEL_AUTHORSTANCES_AGAINST => [UserStance_Module_Processor_AuthorSectionTabPanelComponents::class, UserStance_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORSTANCES_AGAINST],
        );
        if ($inner = $inners[$componentVariation[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getDelegatorfilterSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_TABPANEL_AUTHORSTANCES:
                return [UserStance_Module_Processor_CustomFilters::class, UserStance_Module_Processor_CustomFilters::MODULE_FILTER_AUTHORSTANCES];

            case self::MODULE_BLOCK_TABPANEL_AUTHORSTANCES_PRO:
            case self::MODULE_BLOCK_TABPANEL_AUTHORSTANCES_NEUTRAL:
            case self::MODULE_BLOCK_TABPANEL_AUTHORSTANCES_AGAINST:
                return [UserStance_Module_Processor_CustomFilters::class, UserStance_Module_Processor_CustomFilters::MODULE_FILTER_AUTHORSTANCES_STANCE];
        }

        return parent::getDelegatorfilterSubmodule($componentVariation);
    }
}


