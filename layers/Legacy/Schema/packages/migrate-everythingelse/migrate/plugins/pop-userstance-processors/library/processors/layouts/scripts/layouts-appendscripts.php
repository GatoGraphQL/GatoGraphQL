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

    public function getOperation(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_SCRIPT_CREATEORUPDATESTANCEBUTTON:
                return 'replace';
        }

        return parent::getOperation($module, $props);
    }
    
    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        switch ($module[1]) {
            case self::MODULE_SCRIPT_CREATEORUPDATESTANCEBUTTON:
                $classes = array(
                    self::MODULE_SCRIPT_CREATEORUPDATESTANCEBUTTON => 'createorupdatestance',
                );
                $ret[GD_JS_CLASSES][GD_JS_APPENDABLE] = $classes[$module[1]];
                break;
        }
        
        return $ret;
    }
}



