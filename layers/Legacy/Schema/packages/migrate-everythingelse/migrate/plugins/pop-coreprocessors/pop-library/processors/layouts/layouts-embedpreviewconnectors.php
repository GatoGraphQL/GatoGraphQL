<?php

class PoP_Module_Processor_ReloadEmbedPreviewConnectors extends PoP_Module_Processor_ReloadEmbedPreviewConnectorsBase
{
    public final const COMPONENT_LAYOUT_RELOADEMBEDPREVIEWCONNECTOR = 'layout-reloadembedpreviewconnector';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_RELOADEMBEDPREVIEWCONNECTOR],
        );
    }
}



