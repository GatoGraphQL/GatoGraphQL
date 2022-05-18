<?php

class PoP_ContentPostLinksCreation_Module_Processor_CustomDelegatorFilters extends PoP_Module_Processor_CustomDelegatorFiltersBase
{
    public final const MODULE_DELEGATORFILTER_MYCONTENTPOSTLINKS = 'delegatorfilter-mypostlinks';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DELEGATORFILTER_MYCONTENTPOSTLINKS],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::MODULE_DELEGATORFILTER_MYCONTENTPOSTLINKS => [PoP_ContentPostLinksCreation_Module_Processor_CustomSimpleFilterInners::class, PoP_ContentPostLinksCreation_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINPUTCONTAINER_MYLINKS],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }
}



