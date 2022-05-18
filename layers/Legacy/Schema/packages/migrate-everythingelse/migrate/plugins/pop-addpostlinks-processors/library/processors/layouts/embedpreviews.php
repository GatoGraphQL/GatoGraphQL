<?php

class PoP_AddPostLinks_Module_Processor_EmbedPreviewLayouts extends PoP_Module_Processor_EmbedPreviewLayoutsBase
{
    public final const MODULE_ADDPOSTLINKS_LAYOUT_EMBEDPREVIEW_LINK = 'layout-addpostlinks-embedpreview-link';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_ADDPOSTLINKS_LAYOUT_EMBEDPREVIEW_LINK],
        );
    }
    public function getFrameSrcField(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_ADDPOSTLINKS_LAYOUT_EMBEDPREVIEW_LINK:
                return 'link';
        }

        return parent::getFrameSrcField($componentVariation, $props);
    }
    public function getFrameHeight(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_ADDPOSTLINKS_LAYOUT_EMBEDPREVIEW_LINK:
                return '400';
        }

        return parent::getFrameHeight($componentVariation, $props);
    }
    // function printSource(array $componentVariation, array &$props) {

    //     switch ($componentVariation[1]) {
            
    //         case self::MODULE_ADDPOSTLINKS_LAYOUT_EMBEDPREVIEW_LINK:

    //             return true;
    //     }

    //     return parent::printSource($componentVariation, $props);
    // }
}



