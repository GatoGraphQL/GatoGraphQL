<?php

class PoP_AddPostLinks_Module_Processor_LinkFrameLayouts extends PoP_AddPostLinks_Module_Processor_LinkFrameLayoutsBase
{
    public final const COMPONENT_ADDPOSTLINKS_LAYOUT_LINKFRAMEVISIBLE = 'layout-addpostlinks-linkframevisible';
    public final const COMPONENT_ADDPOSTLINKS_LAYOUT_LINKFRAMECOLLAPSED = 'layout-addpostlinks-linkframecollapsed';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_ADDPOSTLINKS_LAYOUT_LINKFRAMEVISIBLE,
            self::COMPONENT_ADDPOSTLINKS_LAYOUT_LINKFRAMECOLLAPSED,
        );
    }
    public function getLayoutSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $layouts = array(
            self::COMPONENT_ADDPOSTLINKS_LAYOUT_LINKFRAMEVISIBLE => [PoP_AddPostLinks_Module_Processor_EmbedPreviewLayouts::class, PoP_AddPostLinks_Module_Processor_EmbedPreviewLayouts::COMPONENT_ADDPOSTLINKS_LAYOUT_EMBEDPREVIEW_LINK],
            self::COMPONENT_ADDPOSTLINKS_LAYOUT_LINKFRAMECOLLAPSED => [PoP_AddPostLinks_Module_Processor_EmbedPreviewLayouts::class, PoP_AddPostLinks_Module_Processor_EmbedPreviewLayouts::COMPONENT_ADDPOSTLINKS_LAYOUT_EMBEDPREVIEW_LINK],
        );
        if ($layout = $layouts[$component->name] ?? null) {
            return $layout;
        }

        return parent::getLayoutSubcomponent($component);
    }

    public function printSource(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_ADDPOSTLINKS_LAYOUT_LINKFRAMEVISIBLE:
            case self::COMPONENT_ADDPOSTLINKS_LAYOUT_LINKFRAMECOLLAPSED:
                return true;
        }

        return parent::printSource($component, $props);
    }

    public function showFrameInCollapse(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_ADDPOSTLINKS_LAYOUT_LINKFRAMEVISIBLE:
                return false;

            case self::COMPONENT_ADDPOSTLINKS_LAYOUT_LINKFRAMECOLLAPSED:
                return true;
        }

        return parent::showFrameInCollapse($component, $props);
    }
}



