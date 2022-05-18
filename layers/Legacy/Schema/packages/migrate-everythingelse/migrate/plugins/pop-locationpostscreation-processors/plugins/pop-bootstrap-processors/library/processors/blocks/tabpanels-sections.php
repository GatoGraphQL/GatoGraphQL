<?php

class PoP_LocationPostsCreation_Module_Processor_SectionTabPanelBlock extends PoP_Module_Processor_TabPanelSectionBlocksBase
{
    public final const MODULE_BLOCK_TABPANEL_MYLOCATIONPOSTS = 'block-mylocationposts-tabpanel';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_TABPANEL_MYLOCATIONPOSTS],
        );
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        $inners = array(
            self::MODULE_BLOCK_TABPANEL_MYLOCATIONPOSTS => [PoP_LocationPostsCreation_Module_Processor_SectionTabPanelComponents::class, PoP_LocationPostsCreation_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_MYLOCATIONPOSTS],
        );
        if ($inner = $inners[$module[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    protected function getControlgroupTopSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_TABPANEL_MYLOCATIONPOSTS:
                return [CommonPages_EM_Module_Processor_ControlGroups::class, CommonPages_EM_Module_Processor_ControlGroups::MODULE_CONTROLGROUP_MYLOCATIONPOSTLIST];
        }

        return parent::getControlgroupTopSubmodule($module);
    }

    public function getDelegatorfilterSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_TABPANEL_MYLOCATIONPOSTS:
                return [GD_Custom_EM_Module_Processor_CustomFilters::class, GD_Custom_EM_Module_Processor_CustomFilters::MODULE_FILTER_MYLOCATIONPOSTS];
        }

        return parent::getDelegatorfilterSubmodule($module);
    }
}


