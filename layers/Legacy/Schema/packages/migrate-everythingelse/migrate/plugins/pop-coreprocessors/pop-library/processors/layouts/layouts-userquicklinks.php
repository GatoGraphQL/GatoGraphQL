<?php

class PoP_Module_Processor_UserQuickLinkLayouts extends PoP_Module_Processor_UserQuickLinkLayoutsBase
{
    public final const COMPONENT_LAYOUTUSER_QUICKLINKS = 'layoutuser-quicklinks';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUTUSER_QUICKLINKS],
        );
    }
}



