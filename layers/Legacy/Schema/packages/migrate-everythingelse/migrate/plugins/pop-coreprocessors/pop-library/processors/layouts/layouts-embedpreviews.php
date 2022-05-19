<?php

class PoP_Module_Processor_EmbedPreviewLayouts extends PoP_Module_Processor_EmbedPreviewLayoutsBase
{
    public final const COMPONENT_LAYOUT_EMBEDPREVIEW = 'layout-urlembedpreview';
    public final const COMPONENT_LAYOUT_USERINPUTEMBEDPREVIEW = 'layout-userinputembedpreview';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_EMBEDPREVIEW],
            [self::class, self::COMPONENT_LAYOUT_USERINPUTEMBEDPREVIEW],
        );
    }
    public function getFrameSrc(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_USERINPUTEMBEDPREVIEW:
                return \PoP\Root\App::applyFilters('PoP_Module_Processor_EmbedPreviewLayouts:getFrameSrc', '');
        }

        return parent::getFrameSrc($component, $props);
    }
}



