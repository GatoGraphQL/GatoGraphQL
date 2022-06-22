<?php

class PoP_Module_Processor_PublishedLayouts extends PoP_Module_Processor_PostStatusDateLayoutsBase
{
    public final const COMPONENT_LAYOUT_PUBLISHED = 'layout-published';
    public final const COMPONENT_LAYOUT_WIDGETPUBLISHED = 'layout-widgetpublished';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_PUBLISHED,
            self::COMPONENT_LAYOUT_WIDGETPUBLISHED,
        );
    }
}



