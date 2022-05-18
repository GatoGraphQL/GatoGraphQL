<?php

class UserStance_Module_Processor_ScriptsLayouts extends PoP_Module_Processor_AppendScriptsLayoutsBase
{
    public final const MODULE_SCRIPT_STANCES = 'script-stances';
    public final const MODULE_SCRIPT_STANCESEMPTY = 'script-stancesempty';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCRIPT_STANCES],
            [self::class, self::MODULE_SCRIPT_STANCESEMPTY],
        );
    }
    
    public function doAppend(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_SCRIPT_STANCESEMPTY:
                return false;
        }
        
        return parent::doAppend($componentVariation);
    }
    
    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_SCRIPT_STANCES:
            case self::MODULE_SCRIPT_STANCESEMPTY:
                $classes = array(
                    self::MODULE_SCRIPT_STANCES => GD_CLASS_STANCES,
                    self::MODULE_SCRIPT_STANCESEMPTY => GD_CLASS_STANCES,
                );
                $ret[GD_JS_CLASSES][GD_JS_APPENDABLE] = $classes[$componentVariation[1]];
                break;
        }
        
        return $ret;
    }
}



