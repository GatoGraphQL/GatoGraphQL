<?php

class PoP_AddPostLinks_Module_Processor_LinkFrameLayouts extends PoP_AddPostLinks_Module_Processor_LinkFrameLayoutsBase
{
    public final const MODULE_ADDPOSTLINKS_LAYOUT_LINKFRAMEVISIBLE = 'layout-addpostlinks-linkframevisible';
    public final const MODULE_ADDPOSTLINKS_LAYOUT_LINKFRAMECOLLAPSED = 'layout-addpostlinks-linkframecollapsed';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_ADDPOSTLINKS_LAYOUT_LINKFRAMEVISIBLE],
            [self::class, self::MODULE_ADDPOSTLINKS_LAYOUT_LINKFRAMECOLLAPSED],
        );
    }
    public function getLayoutSubmodule(array $component)
    {
        $layouts = array(
            self::MODULE_ADDPOSTLINKS_LAYOUT_LINKFRAMEVISIBLE => [PoP_AddPostLinks_Module_Processor_EmbedPreviewLayouts::class, PoP_AddPostLinks_Module_Processor_EmbedPreviewLayouts::MODULE_ADDPOSTLINKS_LAYOUT_EMBEDPREVIEW_LINK],
            self::MODULE_ADDPOSTLINKS_LAYOUT_LINKFRAMECOLLAPSED => [PoP_AddPostLinks_Module_Processor_EmbedPreviewLayouts::class, PoP_AddPostLinks_Module_Processor_EmbedPreviewLayouts::MODULE_ADDPOSTLINKS_LAYOUT_EMBEDPREVIEW_LINK],
        );
        if ($layout = $layouts[$component[1]] ?? null) {
            return $layout;
        }

        return parent::getLayoutSubmodule($component);
    }

    public function printSource(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_ADDPOSTLINKS_LAYOUT_LINKFRAMEVISIBLE:
            case self::MODULE_ADDPOSTLINKS_LAYOUT_LINKFRAMECOLLAPSED:
                return true;
        }

        return parent::printSource($component, $props);
    }

    public function showFrameInCollapse(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_ADDPOSTLINKS_LAYOUT_LINKFRAMEVISIBLE:
                return false;

            case self::MODULE_ADDPOSTLINKS_LAYOUT_LINKFRAMECOLLAPSED:
                return true;
        }

        return parent::showFrameInCollapse($component, $props);
    }
}



