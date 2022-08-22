<?php

class PoPSPEM_Module_Processor_CustomDelegatorFilters extends PoP_Module_Processor_CustomDelegatorFiltersBase
{
    public final const COMPONENT_DELEGATORFILTER_MYLOCATIONPOSTS = 'delegatorfilter-mylocationposts';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_DELEGATORFILTER_MYLOCATIONPOSTS,
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inners = array(
            self::COMPONENT_DELEGATORFILTER_MYLOCATIONPOSTS => [PoPSPEM_Module_Processor_CustomSimpleFilterInners::class, PoPSPEM_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_MYLOCATIONPOSTS],
        );

        if ($inner = $inners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }
}



