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

    public function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        $inners = array(
            self::COMPONENT_BLOCK_TABPANEL_HOMECONTENT => [PoP_Module_Processor_HomeSectionTabPanelComponents::class, PoP_Module_Processor_HomeSectionTabPanelComponents::COMPONENT_TABPANEL_HOMECONTENT],
        );
        if ($inner = $inners[$component[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getDelegatorfilterSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_TABPANEL_HOMECONTENT:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::COMPONENT_FILTER_CONTENT];
        }

        return parent::getDelegatorfilterSubmodule($component);
    }

    protected function getControlgroupTopSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_TABPANEL_HOMECONTENT:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_POSTLIST];
        }

        return parent::getControlgroupTopSubmodule($component);
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


