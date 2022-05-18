<?php

class PoP_ContentPostLinks_Module_Processor_CustomDelegatorFilters extends PoP_Module_Processor_CustomDelegatorFiltersBase
{
    public final const COMPONENT_DELEGATORFILTER_CONTENTPOSTLINKS = 'delegatorfilter-contentpostlinks';
    public final const COMPONENT_DELEGATORFILTER_AUTHORCONTENTPOSTLINKS = 'delegatorfilter-authorcontentpostlinks';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DELEGATORFILTER_CONTENTPOSTLINKS],
            [self::class, self::COMPONENT_DELEGATORFILTER_AUTHORCONTENTPOSTLINKS],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::COMPONENT_DELEGATORFILTER_CONTENTPOSTLINKS => [PoP_ContentPostLinks_Module_Processor_CustomSimpleFilterInners::class, PoP_ContentPostLinks_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_LINKS],
            self::COMPONENT_DELEGATORFILTER_AUTHORCONTENTPOSTLINKS => [PoP_ContentPostLinks_Module_Processor_CustomSimpleFilterInners::class, PoP_ContentPostLinks_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_AUTHORLINKS],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }
}



