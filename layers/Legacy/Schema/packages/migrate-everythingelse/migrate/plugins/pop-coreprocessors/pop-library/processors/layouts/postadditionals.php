<?php

class PoP_Module_Processor_PostAdditionalLayouts extends PoP_Module_Processor_PostAdditionalLayoutsBase
{
    public final const COMPONENT_LAYOUT_POSTADDITIONAL_MULTILAYOUT_LABEL = 'layout-postadditional-multilayout-label';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_POSTADDITIONAL_MULTILAYOUT_LABEL],
        );
    }
}



