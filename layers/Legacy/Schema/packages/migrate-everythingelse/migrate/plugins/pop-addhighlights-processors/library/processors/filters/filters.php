<?php

class PoP_AddHighlights_Module_Processor_CustomFilters extends PoP_Module_Processor_FiltersBase
{
    public final const COMPONENT_FILTER_HIGHLIGHTS = 'filter-highlights';
    public final const COMPONENT_FILTER_AUTHORHIGHLIGHTS = 'filter-authorhighlights';
    public final const COMPONENT_FILTER_MYHIGHLIGHTS = 'filter-myhighlights';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTER_HIGHLIGHTS],
            [self::class, self::COMPONENT_FILTER_AUTHORHIGHLIGHTS],
            [self::class, self::COMPONENT_FILTER_MYHIGHLIGHTS],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::COMPONENT_FILTER_HIGHLIGHTS => [PoP_AddHighlights_Module_Processor_CustomFilterInners::class, PoP_AddHighlights_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_HIGHLIGHTS],
            self::COMPONENT_FILTER_AUTHORHIGHLIGHTS => [PoP_AddHighlights_Module_Processor_CustomFilterInners::class, PoP_AddHighlights_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_AUTHORHIGHLIGHTS],
            self::COMPONENT_FILTER_MYHIGHLIGHTS => [PoP_AddHighlights_Module_Processor_CustomFilterInners::class, PoP_AddHighlights_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_MYHIGHLIGHTS],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }
}


