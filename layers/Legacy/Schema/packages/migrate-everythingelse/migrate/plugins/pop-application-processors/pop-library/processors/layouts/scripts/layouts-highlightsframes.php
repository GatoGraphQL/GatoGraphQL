<?php

class PoP_Module_Processor_HighlightReferencesFramesLayouts extends PoP_Module_Processor_HighlightReferencesScriptFrameLayoutsBase
{
    public final const MODULE_LAYOUT_HIGHLIGHTS_APPENDTOSCRIPT = 'layout-highlights-appendtoscript';
    public final const MODULE_LAYOUT_HIGHLIGHTSEMPTY_APPENDTOSCRIPT = 'layout-highlightsempty-appendtoscript';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_HIGHLIGHTS_APPENDTOSCRIPT],
            [self::class, self::COMPONENT_LAYOUT_HIGHLIGHTSEMPTY_APPENDTOSCRIPT],
        );
    }

    public function doAppend(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_HIGHLIGHTSEMPTY_APPENDTOSCRIPT:
                return false;
        }
        
        return parent::doAppend($component);
    }

    public function getLayoutSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_HIGHLIGHTS_APPENDTOSCRIPT:
            case self::COMPONENT_LAYOUT_HIGHLIGHTSEMPTY_APPENDTOSCRIPT:
                return [PoP_Module_Processor_HighlightReferencedbyLayouts::class, PoP_Module_Processor_HighlightReferencedbyLayouts::COMPONENT_SUBCOMPONENT_HIGHLIGHTS];
        }
        
        return parent::getLayoutSubmodule($component);
    }
}



