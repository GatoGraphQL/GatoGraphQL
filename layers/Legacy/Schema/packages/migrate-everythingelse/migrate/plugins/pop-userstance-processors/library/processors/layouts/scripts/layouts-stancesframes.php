<?php

class PoP_Module_Processor_StanceReferencesFramesLayouts extends PoP_Module_Processor_StanceReferencesScriptFrameLayoutsBase
{
    public final const MODULE_LAYOUT_STANCES_APPENDTOSCRIPT = 'layout-stances-appendtoscript';
    public final const MODULE_LAYOUT_STANCESEMPTY_APPENDTOSCRIPT = 'layout-stancesempty-appendtoscript';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_STANCES_APPENDTOSCRIPT],
            [self::class, self::MODULE_LAYOUT_STANCESEMPTY_APPENDTOSCRIPT],
        );
    }

    public function doAppend(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_LAYOUT_STANCESEMPTY_APPENDTOSCRIPT:
                return false;
        }
        
        return parent::doAppend($component);
    }

    public function getLayoutSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_LAYOUT_STANCES_APPENDTOSCRIPT:
            case self::MODULE_LAYOUT_STANCESEMPTY_APPENDTOSCRIPT:
                return [UserStance_Module_Processor_StanceReferencedbyLayouts::class, UserStance_Module_Processor_StanceReferencedbyLayouts::MODULE_SUBCOMPONENT_STANCES];
        }
        
        return parent::getLayoutSubmodule($component);
    }
}



