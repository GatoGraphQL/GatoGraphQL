<?php

class PoP_ContentPostLinksCreation_Module_Processor_CustomDelegatorFilters extends PoP_Module_Processor_CustomDelegatorFiltersBase
{
    public final const COMPONENT_DELEGATORFILTER_MYCONTENTPOSTLINKS = 'delegatorfilter-mypostlinks';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_DELEGATORFILTER_MYCONTENTPOSTLINKS,
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inners = array(
            self::COMPONENT_DELEGATORFILTER_MYCONTENTPOSTLINKS => [PoP_ContentPostLinksCreation_Module_Processor_CustomSimpleFilterInners::class, PoP_ContentPostLinksCreation_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_MYLINKS],
        );

        if ($inner = $inners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }
}



