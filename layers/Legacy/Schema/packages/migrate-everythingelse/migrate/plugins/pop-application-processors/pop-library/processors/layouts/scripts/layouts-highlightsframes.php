<?php

class PoP_Module_Processor_HighlightReferencesFramesLayouts extends PoP_Module_Processor_HighlightReferencesScriptFrameLayoutsBase
{
    public final const MODULE_LAYOUT_HIGHLIGHTS_APPENDTOSCRIPT = 'layout-highlights-appendtoscript';
    public final const MODULE_LAYOUT_HIGHLIGHTSEMPTY_APPENDTOSCRIPT = 'layout-highlightsempty-appendtoscript';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_HIGHLIGHTS_APPENDTOSCRIPT],
            [self::class, self::MODULE_LAYOUT_HIGHLIGHTSEMPTY_APPENDTOSCRIPT],
        );
    }

    public function doAppend(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_HIGHLIGHTSEMPTY_APPENDTOSCRIPT:
                return false;
        }
        
        return parent::doAppend($componentVariation);
    }

    public function getLayoutSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_HIGHLIGHTS_APPENDTOSCRIPT:
            case self::MODULE_LAYOUT_HIGHLIGHTSEMPTY_APPENDTOSCRIPT:
                return [PoP_Module_Processor_HighlightReferencedbyLayouts::class, PoP_Module_Processor_HighlightReferencedbyLayouts::MODULE_SUBCOMPONENT_HIGHLIGHTS];
        }
        
        return parent::getLayoutSubmodule($componentVariation);
    }
}



