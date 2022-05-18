<?php

class PoP_Module_Processor_StanceScriptsLayouts extends PoP_Module_Processor_AppendScriptsLayoutsBase
{
    public final const MODULE_SCRIPT_CREATEORUPDATESTANCEBUTTON = 'script-createorupdatestancebutton';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SCRIPT_CREATEORUPDATESTANCEBUTTON],
        );
    }

    public function getOperation(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_SCRIPT_CREATEORUPDATESTANCEBUTTON:
                return 'replace';
        }

        return parent::getOperation($component, $props);
    }
    
    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_SCRIPT_CREATEORUPDATESTANCEBUTTON:
                $classes = array(
                    self::COMPONENT_SCRIPT_CREATEORUPDATESTANCEBUTTON => 'createorupdatestance',
                );
                $ret[GD_JS_CLASSES][GD_JS_APPENDABLE] = $classes[$component[1]];
                break;
        }
        
        return $ret;
    }
}



