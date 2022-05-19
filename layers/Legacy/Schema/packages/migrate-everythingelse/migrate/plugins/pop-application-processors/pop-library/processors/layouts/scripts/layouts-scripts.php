<?php

class Wassup_Module_Processor_ScriptsLayouts extends PoP_Module_Processor_AppendScriptsLayoutsBase
{
    public final const COMPONENT_SCRIPT_HIGHLIGHTS = 'script-highlights';
    public final const COMPONENT_SCRIPT_HIGHLIGHTSEMPTY = 'script-highlightsempty';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SCRIPT_HIGHLIGHTS],
            [self::class, self::COMPONENT_SCRIPT_HIGHLIGHTSEMPTY],
        );
    }
    
    public function doAppend(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_SCRIPT_HIGHLIGHTSEMPTY:
                return false;
        }
        
        return parent::doAppend($component);
    }
    
    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_SCRIPT_HIGHLIGHTS:
            case self::COMPONENT_SCRIPT_HIGHLIGHTSEMPTY:
                $classes = array(
                    self::COMPONENT_SCRIPT_HIGHLIGHTS => 'highlights',
                    self::COMPONENT_SCRIPT_HIGHLIGHTSEMPTY => 'highlights',
                );
                $ret[GD_JS_CLASSES][GD_JS_APPENDABLE] = $classes[$component[1]];
                break;
        }
        
        return $ret;
    }
}



