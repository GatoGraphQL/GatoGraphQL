<?php

class Wassup_Module_Processor_ScriptsLayouts extends PoP_Module_Processor_AppendScriptsLayoutsBase
{
    public final const MODULE_SCRIPT_HIGHLIGHTS = 'script-highlights';
    public final const MODULE_SCRIPT_HIGHLIGHTSEMPTY = 'script-highlightsempty';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCRIPT_HIGHLIGHTS],
            [self::class, self::MODULE_SCRIPT_HIGHLIGHTSEMPTY],
        );
    }
    
    public function doAppend(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_SCRIPT_HIGHLIGHTSEMPTY:
                return false;
        }
        
        return parent::doAppend($module);
    }
    
    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        switch ($module[1]) {
            case self::MODULE_SCRIPT_HIGHLIGHTS:
            case self::MODULE_SCRIPT_HIGHLIGHTSEMPTY:
                $classes = array(
                    self::MODULE_SCRIPT_HIGHLIGHTS => 'highlights',
                    self::MODULE_SCRIPT_HIGHLIGHTSEMPTY => 'highlights',
                );
                $ret[GD_JS_CLASSES][GD_JS_APPENDABLE] = $classes[$module[1]];
                break;
        }
        
        return $ret;
    }
}



