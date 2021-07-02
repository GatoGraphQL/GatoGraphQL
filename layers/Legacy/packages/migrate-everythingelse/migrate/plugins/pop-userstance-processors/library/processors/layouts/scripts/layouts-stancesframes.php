<?php

class PoP_Module_Processor_StanceReferencesFramesLayouts extends PoP_Module_Processor_StanceReferencesScriptFrameLayoutsBase
{
    public const MODULE_LAYOUT_STANCES_APPENDTOSCRIPT = 'layout-stances-appendtoscript';
    public const MODULE_LAYOUT_STANCESEMPTY_APPENDTOSCRIPT = 'layout-stancesempty-appendtoscript';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_STANCES_APPENDTOSCRIPT],
            [self::class, self::MODULE_LAYOUT_STANCESEMPTY_APPENDTOSCRIPT],
        );
    }

    public function doAppend(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_STANCESEMPTY_APPENDTOSCRIPT:
                return false;
        }
        
        return parent::doAppend($module);
    }

    public function getLayoutSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_STANCES_APPENDTOSCRIPT:
            case self::MODULE_LAYOUT_STANCESEMPTY_APPENDTOSCRIPT:
                return [UserStance_Module_Processor_StanceReferencedbyLayouts::class, UserStance_Module_Processor_StanceReferencedbyLayouts::MODULE_SUBCOMPONENT_STANCES];
        }
        
        return parent::getLayoutSubmodule($module);
    }
}



