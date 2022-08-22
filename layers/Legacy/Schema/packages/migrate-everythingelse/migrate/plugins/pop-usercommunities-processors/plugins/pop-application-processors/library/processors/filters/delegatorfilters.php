<?php

class PoP_UserCommunities_Module_Processor_CustomDelegatorFilters extends PoP_Module_Processor_CustomDelegatorFiltersBase
{
    public final const COMPONENT_DELEGATORFILTER_MYMEMBERS = 'delegatorfilter-mymembers';
    public final const COMPONENT_DELEGATORFILTER_COMMUNITIES = 'delegatorfilter-communities';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_DELEGATORFILTER_MYMEMBERS,
            self::COMPONENT_DELEGATORFILTER_COMMUNITIES,
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inners = array(
            self::COMPONENT_DELEGATORFILTER_MYMEMBERS => [GD_URE_Module_Processor_CustomSimpleFilterInners::class, GD_URE_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_MYMEMBERS],
            self::COMPONENT_DELEGATORFILTER_COMMUNITIES => [GD_URE_Module_Processor_CustomSimpleFilterInners::class, GD_URE_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_COMMUNITIES],
        );

        if ($inner = $inners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }
}



