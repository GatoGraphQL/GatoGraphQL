<?php

class PoP_Module_Processor_PublishedLayouts extends PoP_Module_Processor_PostStatusDateLayoutsBase
{
    public final const MODULE_LAYOUT_PUBLISHED = 'layout-published';
    public final const MODULE_LAYOUT_WIDGETPUBLISHED = 'layout-widgetpublished';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_PUBLISHED],
            [self::class, self::MODULE_LAYOUT_WIDGETPUBLISHED],
        );
    }
}



