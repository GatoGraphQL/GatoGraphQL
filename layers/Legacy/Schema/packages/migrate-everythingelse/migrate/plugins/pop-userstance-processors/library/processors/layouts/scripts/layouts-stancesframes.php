<?php

class PoP_Module_Processor_StanceReferencesFramesLayouts extends PoP_Module_Processor_StanceReferencesScriptFrameLayoutsBase
{
    public final const COMPONENT_LAYOUT_STANCES_APPENDTOSCRIPT = 'layout-stances-appendtoscript';
    public final const COMPONENT_LAYOUT_STANCESEMPTY_APPENDTOSCRIPT = 'layout-stancesempty-appendtoscript';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_STANCES_APPENDTOSCRIPT,
            self::COMPONENT_LAYOUT_STANCESEMPTY_APPENDTOSCRIPT,
        );
    }

    public function doAppend(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_STANCESEMPTY_APPENDTOSCRIPT:
                return false;
        }
        
        return parent::doAppend($component);
    }

    public function getLayoutSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_STANCES_APPENDTOSCRIPT:
            case self::COMPONENT_LAYOUT_STANCESEMPTY_APPENDTOSCRIPT:
                return [UserStance_Module_Processor_StanceReferencedbyLayouts::class, UserStance_Module_Processor_StanceReferencedbyLayouts::COMPONENT_SUBCOMPONENT_STANCES];
        }
        
        return parent::getLayoutSubcomponent($component);
    }
}



