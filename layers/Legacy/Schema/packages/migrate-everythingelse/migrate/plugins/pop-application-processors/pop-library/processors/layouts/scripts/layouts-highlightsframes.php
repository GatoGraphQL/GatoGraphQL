<?php

class PoP_Module_Processor_HighlightReferencesFramesLayouts extends PoP_Module_Processor_HighlightReferencesScriptFrameLayoutsBase
{
    public final const COMPONENT_LAYOUT_HIGHLIGHTS_APPENDTOSCRIPT = 'layout-highlights-appendtoscript';
    public final const COMPONENT_LAYOUT_HIGHLIGHTSEMPTY_APPENDTOSCRIPT = 'layout-highlightsempty-appendtoscript';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_HIGHLIGHTS_APPENDTOSCRIPT,
            self::COMPONENT_LAYOUT_HIGHLIGHTSEMPTY_APPENDTOSCRIPT,
        );
    }

    public function doAppend(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_HIGHLIGHTSEMPTY_APPENDTOSCRIPT:
                return false;
        }
        
        return parent::doAppend($component);
    }

    public function getLayoutSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_HIGHLIGHTS_APPENDTOSCRIPT:
            case self::COMPONENT_LAYOUT_HIGHLIGHTSEMPTY_APPENDTOSCRIPT:
                return [PoP_Module_Processor_HighlightReferencedbyLayouts::class, PoP_Module_Processor_HighlightReferencedbyLayouts::COMPONENT_SUBCOMPONENT_HIGHLIGHTS];
        }
        
        return parent::getLayoutSubcomponent($component);
    }
}



