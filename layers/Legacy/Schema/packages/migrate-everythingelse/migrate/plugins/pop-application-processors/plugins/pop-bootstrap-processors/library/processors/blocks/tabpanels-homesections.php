<?php

class PoP_Module_Processor_HomeTabPanelSectionBlocks extends PoP_Module_Processor_HomeTabPanelSectionBlocksBase
{
    public final const COMPONENT_BLOCK_TABPANEL_HOMECONTENT = 'block-homecontent-tabpanel';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_TABPANEL_HOMECONTENT],
        );
    }

    public function getInnerSubcomponents(array $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        $inners = array(
            self::COMPONENT_BLOCK_TABPANEL_HOMECONTENT => [PoP_Module_Processor_HomeSectionTabPanelComponents::class, PoP_Module_Processor_HomeSectionTabPanelComponents::COMPONENT_TABPANEL_HOMECONTENT],
        );
        if ($inner = $inners[$component[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getDelegatorfilterSubcomponent(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_TABPANEL_HOMECONTENT:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::COMPONENT_FILTER_CONTENT];
        }

        return parent::getDelegatorfilterSubcomponent($component);
    }

    protected function getControlgroupTopSubcomponent(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_TABPANEL_HOMECONTENT:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_POSTLIST];
        }

        return parent::getControlgroupTopSubcomponent($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_TABPANEL_HOMECONTENT:
                $this->appendProp($component, $props, 'class', 'pop-home-latesteverything');
                break;
        }

        parent::initModelProps($component, $props);
    }
}


