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
    public function getFrameSrcField(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_ADDPOSTLINKS_LAYOUT_EMBEDPREVIEW_LINK:
                return 'link';
        }

        return parent::getFrameSrcField($module, $props);
    }
    public function getFrameHeight(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_ADDPOSTLINKS_LAYOUT_EMBEDPREVIEW_LINK:
                return '400';
        }

        return parent::getFrameHeight($module, $props);
    }
    // function printSource(array $module, array &$props) {

    //     switch ($module[1]) {
            
    //         case self::MODULE_ADDPOSTLINKS_LAYOUT_EMBEDPREVIEW_LINK:

    //             return true;
    //     }

    //     return parent::printSource($module, $props);
    // }
}



