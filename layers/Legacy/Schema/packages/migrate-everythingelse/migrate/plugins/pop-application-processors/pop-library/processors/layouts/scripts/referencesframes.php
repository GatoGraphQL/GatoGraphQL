<?php

class PoP_Module_Processor_ReferencesFramesLayouts extends PoP_Module_Processor_ReferencesScriptFrameLayoutsBase
{
    public final const COMPONENT_LAYOUT_REFERENCEDBY_APPENDTOSCRIPT_DETAILS = 'layout-referencedby-appendtoscript-details';
    public final const COMPONENT_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_DETAILS = 'layout-referencedbyempty-appendtoscript-details';
    public final const COMPONENT_LAYOUT_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW = 'layout-referencedby-appendtoscript-simpleview';
    public final const COMPONENT_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_SIMPLEVIEW = 'layout-referencedbyempty-appendtoscript-simpleview';
    public final const COMPONENT_LAYOUT_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW = 'layout-referencedby-appendtoscript-fullview';
    public final const COMPONENT_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_FULLVIEW = 'layout-referencedbyempty-appendtoscript-fullview';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_REFERENCEDBY_APPENDTOSCRIPT_DETAILS,
            self::COMPONENT_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_DETAILS,
            self::COMPONENT_LAYOUT_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW,
            self::COMPONENT_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_SIMPLEVIEW,
            self::COMPONENT_LAYOUT_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW,
            self::COMPONENT_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_FULLVIEW,
        );
    }

    public function doAppend(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_DETAILS:
            case self::COMPONENT_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_SIMPLEVIEW:
            case self::COMPONENT_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_FULLVIEW:
                return false;
        }
        
        return parent::doAppend($component);
    }

    public function getLayoutSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_REFERENCEDBY_APPENDTOSCRIPT_DETAILS:
            case self::COMPONENT_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_DETAILS:
                return [PoP_Module_Processor_ReferencedbyLayouts::class, PoP_Module_Processor_ReferencedbyLayouts::COMPONENT_SUBCOMPONENT_REFERENCEDBY_DETAILS];

            case self::COMPONENT_LAYOUT_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:
            case self::COMPONENT_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_SIMPLEVIEW:
                return [PoP_Module_Processor_ReferencedbyLayouts::class, PoP_Module_Processor_ReferencedbyLayouts::COMPONENT_SUBCOMPONENT_REFERENCEDBY_SIMPLEVIEW];

            case self::COMPONENT_LAYOUT_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:
            case self::COMPONENT_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_FULLVIEW:
                return [PoP_Module_Processor_ReferencedbyLayouts::class, PoP_Module_Processor_ReferencedbyLayouts::COMPONENT_SUBCOMPONENT_REFERENCEDBY_FULLVIEW];
        }
        
        return parent::getLayoutSubcomponent($component);
    }
}



