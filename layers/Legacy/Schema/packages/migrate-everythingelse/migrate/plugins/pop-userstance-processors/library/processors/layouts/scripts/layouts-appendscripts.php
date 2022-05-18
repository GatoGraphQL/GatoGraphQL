<?php

class PoP_Module_Processor_StanceScriptsLayouts extends PoP_Module_Processor_AppendScriptsLayoutsBase
{
    public final const MODULE_SCRIPT_CREATEORUPDATESTANCEBUTTON = 'script-createorupdatestancebutton';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCRIPT_CREATEORUPDATESTANCEBUTTON],
        );
    }

    public function getOperation(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_SCRIPT_CREATEORUPDATESTANCEBUTTON:
                return 'replace';
        }

        return parent::getOperation($componentVariation, $props);
    }
    
    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_SCRIPT_CREATEORUPDATESTANCEBUTTON:
                $classes = array(
                    self::MODULE_SCRIPT_CREATEORUPDATESTANCEBUTTON => 'createorupdatestance',
                );
                $ret[GD_JS_CLASSES][GD_JS_APPENDABLE] = $classes[$componentVariation[1]];
                break;
        }
        
        return $ret;
    }
}



