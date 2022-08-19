<?php

class PoP_ContentPostLinks_Module_Processor_CustomFilters extends PoP_Module_Processor_FiltersBase
{
    public final const COMPONENT_FILTER_LINKS = 'filter-links';
    public final const COMPONENT_FILTER_AUTHORLINKS = 'filter-authorlinks';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FILTER_LINKS,
            self::COMPONENT_FILTER_AUTHORLINKS,
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inners = array(
            self::COMPONENT_FILTER_LINKS => [PoP_ContentPostLinks_Module_Processor_CustomFilterInners::class, PoP_ContentPostLinks_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_LINKS],
            self::COMPONENT_FILTER_AUTHORLINKS => [PoP_ContentPostLinks_Module_Processor_CustomFilterInners::class, PoP_ContentPostLinks_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_AUTHORLINKS],
        );

        if ($inner = $inners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }
}


