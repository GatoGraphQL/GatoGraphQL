<?php

class PoP_Module_Processor_ReloadEmbedPreviewConnectors extends PoP_Module_Processor_ReloadEmbedPreviewConnectorsBase
{
    public const MODULE_LAYOUT_RELOADEMBEDPREVIEWCONNECTOR = 'layout-reloadembedpreviewconnector';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_RELOADEMBEDPREVIEWCONNECTOR],
        );
    }
}



