<?php

class PoP_Module_Processor_PublishedLayouts extends PoP_Module_Processor_PostStatusDateLayoutsBase
{
    public const MODULE_LAYOUT_PUBLISHED = 'layout-published';
    public const MODULE_LAYOUT_WIDGETPUBLISHED = 'layout-widgetpublished';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_PUBLISHED],
            [self::class, self::MODULE_LAYOUT_WIDGETPUBLISHED],
        );
    }
}



