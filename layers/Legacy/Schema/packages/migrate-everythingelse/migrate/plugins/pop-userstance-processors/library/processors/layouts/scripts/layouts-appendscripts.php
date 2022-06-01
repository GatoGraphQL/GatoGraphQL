<?php

class PoP_Module_Processor_StanceScriptsLayouts extends PoP_Module_Processor_AppendScriptsLayoutsBase
{
    public final const COMPONENT_SCRIPT_CREATEORUPDATESTANCEBUTTON = 'script-createorupdatestancebutton';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_SCRIPT_CREATEORUPDATESTANCEBUTTON,
        );
    }

    public function getOperation(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_SCRIPT_CREATEORUPDATESTANCEBUTTON:
                return 'replace';
        }

        return parent::getOperation($component, $props);
    }
    
    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        switch ($component->name) {
            case self::COMPONENT_SCRIPT_CREATEORUPDATESTANCEBUTTON:
                $classes = array(
                    self::COMPONENT_SCRIPT_CREATEORUPDATESTANCEBUTTON => 'createorupdatestance',
                );
                $ret[GD_JS_CLASSES][GD_JS_APPENDABLE] = $classes[$component->name];
                break;
        }
        
        return $ret;
    }
}



