<?php

class PoP_ContentPostLinks_Module_Processor_CustomFilters extends PoP_Module_Processor_FiltersBase
{
    public final const COMPONENT_FILTER_LINKS = 'filter-links';
    public final const COMPONENT_FILTER_AUTHORLINKS = 'filter-authorlinks';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTER_LINKS],
            [self::class, self::COMPONENT_FILTER_AUTHORLINKS],
        );
    }

    public function getInnerSubcomponent(array $component)
    {
        $inners = array(
            self::COMPONENT_FILTER_LINKS => [PoP_ContentPostLinks_Module_Processor_CustomFilterInners::class, PoP_ContentPostLinks_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_LINKS],
            self::COMPONENT_FILTER_AUTHORLINKS => [PoP_ContentPostLinks_Module_Processor_CustomFilterInners::class, PoP_ContentPostLinks_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_AUTHORLINKS],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }
}


