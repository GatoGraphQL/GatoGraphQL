<?php

class PoP_Module_Processor_ReferencesFramesLayouts extends PoP_Module_Processor_ReferencesScriptFrameLayoutsBase
{
    public final const MODULE_LAYOUT_REFERENCEDBY_APPENDTOSCRIPT_DETAILS = 'layout-referencedby-appendtoscript-details';
    public final const MODULE_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_DETAILS = 'layout-referencedbyempty-appendtoscript-details';
    public final const MODULE_LAYOUT_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW = 'layout-referencedby-appendtoscript-simpleview';
    public final const MODULE_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_SIMPLEVIEW = 'layout-referencedbyempty-appendtoscript-simpleview';
    public final const MODULE_LAYOUT_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW = 'layout-referencedby-appendtoscript-fullview';
    public final const MODULE_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_FULLVIEW = 'layout-referencedbyempty-appendtoscript-fullview';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_REFERENCEDBY_APPENDTOSCRIPT_DETAILS],
            [self::class, self::MODULE_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_DETAILS],
            [self::class, self::MODULE_LAYOUT_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW],
            [self::class, self::MODULE_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_SIMPLEVIEW],
            [self::class, self::MODULE_LAYOUT_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW],
            [self::class, self::MODULE_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_FULLVIEW],
        );
    }

    public function doAppend(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_DETAILS:
            case self::MODULE_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_SIMPLEVIEW:
            case self::MODULE_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_FULLVIEW:
                return false;
        }
        
        return parent::doAppend($module);
    }

    public function getLayoutSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_REFERENCEDBY_APPENDTOSCRIPT_DETAILS:
            case self::MODULE_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_DETAILS:
                return [PoP_Module_Processor_ReferencedbyLayouts::class, PoP_Module_Processor_ReferencedbyLayouts::MODULE_SUBCOMPONENT_REFERENCEDBY_DETAILS];

            case self::MODULE_LAYOUT_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:
            case self::MODULE_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_SIMPLEVIEW:
                return [PoP_Module_Processor_ReferencedbyLayouts::class, PoP_Module_Processor_ReferencedbyLayouts::MODULE_SUBCOMPONENT_REFERENCEDBY_SIMPLEVIEW];

            case self::MODULE_LAYOUT_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:
            case self::MODULE_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_FULLVIEW:
                return [PoP_Module_Processor_ReferencedbyLayouts::class, PoP_Module_Processor_ReferencedbyLayouts::MODULE_SUBCOMPONENT_REFERENCEDBY_FULLVIEW];
        }
        
        return parent::getLayoutSubmodule($module);
    }
}



