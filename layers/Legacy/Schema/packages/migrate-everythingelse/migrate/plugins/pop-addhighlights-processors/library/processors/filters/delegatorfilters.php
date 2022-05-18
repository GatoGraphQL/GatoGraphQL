<?php

class PoP_AddHighlights_Module_Processor_CustomDelegatorFilters extends PoP_Module_Processor_CustomDelegatorFiltersBase
{
    public final const COMPONENT_DELEGATORFILTER_AUTHORHIGHLIGHTS = 'delegatorfilter-authorhighlights';
    public final const COMPONENT_DELEGATORFILTER_HIGHLIGHTS = 'delegatorfilter-highlights';
    public final const COMPONENT_DELEGATORFILTER_MYHIGHLIGHTS = 'delegatorfilter-myhighlights';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DELEGATORFILTER_HIGHLIGHTS],
            [self::class, self::COMPONENT_DELEGATORFILTER_AUTHORHIGHLIGHTS],
            [self::class, self::COMPONENT_DELEGATORFILTER_MYHIGHLIGHTS],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::COMPONENT_DELEGATORFILTER_HIGHLIGHTS => [PoP_AddHighlights_Module_Processor_CustomSimpleFilterInners::class, PoP_AddHighlights_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_HIGHLIGHTS],
            self::COMPONENT_DELEGATORFILTER_AUTHORHIGHLIGHTS => [PoP_AddHighlights_Module_Processor_CustomSimpleFilterInners::class, PoP_AddHighlights_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_AUTHORHIGHLIGHTS],
            self::COMPONENT_DELEGATORFILTER_MYHIGHLIGHTS => [PoP_AddHighlights_Module_Processor_CustomSimpleFilterInners::class, PoP_AddHighlights_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_MYHIGHLIGHTS],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }
}



