<?php

class UserStance_Module_Processor_ScriptsLayouts extends PoP_Module_Processor_AppendScriptsLayoutsBase
{
    public final const COMPONENT_SCRIPT_STANCES = 'script-stances';
    public final const COMPONENT_SCRIPT_STANCESEMPTY = 'script-stancesempty';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_SCRIPT_STANCES,
            self::COMPONENT_SCRIPT_STANCESEMPTY,
        );
    }
    
    public function doAppend(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_SCRIPT_STANCESEMPTY:
                return false;
        }
        
        return parent::doAppend($component);
    }
    
    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        switch ($component->name) {
            case self::COMPONENT_SCRIPT_STANCES:
            case self::COMPONENT_SCRIPT_STANCESEMPTY:
                $classes = array(
                    self::COMPONENT_SCRIPT_STANCES => GD_CLASS_STANCES,
                    self::COMPONENT_SCRIPT_STANCESEMPTY => GD_CLASS_STANCES,
                );
                $ret[GD_JS_CLASSES][GD_JS_APPENDABLE] = $classes[$component->name];
                break;
        }
        
        return $ret;
    }
}



