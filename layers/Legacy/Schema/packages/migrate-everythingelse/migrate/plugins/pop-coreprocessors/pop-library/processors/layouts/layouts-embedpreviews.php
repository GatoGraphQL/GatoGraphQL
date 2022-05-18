<?php

class PoP_Module_Processor_EmbedPreviewLayouts extends PoP_Module_Processor_EmbedPreviewLayoutsBase
{
    public final const MODULE_LAYOUT_EMBEDPREVIEW = 'layout-urlembedpreview';
    public final const MODULE_LAYOUT_USERINPUTEMBEDPREVIEW = 'layout-userinputembedpreview';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_EMBEDPREVIEW],
            [self::class, self::MODULE_LAYOUT_USERINPUTEMBEDPREVIEW],
        );
    }
    public function getFrameSrc(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_USERINPUTEMBEDPREVIEW:
                return \PoP\Root\App::applyFilters('PoP_Module_Processor_EmbedPreviewLayouts:getFrameSrc', '');
        }

        return parent::getFrameSrc($module, $props);
    }
}



