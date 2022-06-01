<?php

class GD_URE_Module_Processor_SectionTabPanelBlocks extends PoP_Module_Processor_TabPanelSectionBlocksBase
{
    public final const COMPONENT_BLOCK_TABPANEL_ORGANIZATIONS = 'block-organizations-tabpanel';
    public final const COMPONENT_BLOCK_TABPANEL_INDIVIDUALS = 'block-individuals-tabpanel';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_BLOCK_TABPANEL_ORGANIZATIONS,
            self::COMPONENT_BLOCK_TABPANEL_INDIVIDUALS,
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_BLOCK_TABPANEL_INDIVIDUALS => POP_COMMONUSERROLES_ROUTE_INDIVIDUALS,
            self::COMPONENT_BLOCK_TABPANEL_ORGANIZATIONS => POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        $inners = array(
            self::COMPONENT_BLOCK_TABPANEL_INDIVIDUALS => [GD_URE_Module_Processor_SectionTabPanelComponents::class, GD_URE_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_INDIVIDUALS],
            self::COMPONENT_BLOCK_TABPANEL_ORGANIZATIONS => [GD_URE_Module_Processor_SectionTabPanelComponents::class, GD_URE_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_ORGANIZATIONS],
        );
        if ($inner = $inners[$component->name] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    protected function getControlgroupTopSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_BLOCK_TABPANEL_ORGANIZATIONS:
            case self::COMPONENT_BLOCK_TABPANEL_INDIVIDUALS:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_USERLIST];
        }

        return parent::getControlgroupTopSubcomponent($component);
    }

    public function getDelegatorfilterSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_BLOCK_TABPANEL_ORGANIZATIONS:
                return [PoP_CommonUserRoles_Module_Processor_CustomFilters::class, PoP_CommonUserRoles_Module_Processor_CustomFilters::COMPONENT_FILTER_ORGANIZATIONS];

            case self::COMPONENT_BLOCK_TABPANEL_INDIVIDUALS:
                return [PoP_CommonUserRoles_Module_Processor_CustomFilters::class, PoP_CommonUserRoles_Module_Processor_CustomFilters::COMPONENT_FILTER_INDIVIDUALS];
        }

        return parent::getDelegatorfilterSubcomponent($component);
    }
}


