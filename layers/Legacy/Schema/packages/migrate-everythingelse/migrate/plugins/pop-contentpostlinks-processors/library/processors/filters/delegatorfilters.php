<?php

class PoP_ContentPostLinks_Module_Processor_CustomDelegatorFilters extends PoP_Module_Processor_CustomDelegatorFiltersBase
{
    public final const MODULE_DELEGATORFILTER_CONTENTPOSTLINKS = 'delegatorfilter-contentpostlinks';
    public final const MODULE_DELEGATORFILTER_AUTHORCONTENTPOSTLINKS = 'delegatorfilter-authorcontentpostlinks';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DELEGATORFILTER_CONTENTPOSTLINKS],
            [self::class, self::MODULE_DELEGATORFILTER_AUTHORCONTENTPOSTLINKS],
        );
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        $inners = array(
            self::MODULE_DELEGATORFILTER_CONTENTPOSTLINKS => [PoP_ContentPostLinks_Module_Processor_CustomSimpleFilterInners::class, PoP_ContentPostLinks_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINPUTCONTAINER_LINKS],
            self::MODULE_DELEGATORFILTER_AUTHORCONTENTPOSTLINKS => [PoP_ContentPostLinks_Module_Processor_CustomSimpleFilterInners::class, PoP_ContentPostLinks_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINPUTCONTAINER_AUTHORLINKS],
        );

        if ($inner = $inners[$componentVariation[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($componentVariation);
    }
}



