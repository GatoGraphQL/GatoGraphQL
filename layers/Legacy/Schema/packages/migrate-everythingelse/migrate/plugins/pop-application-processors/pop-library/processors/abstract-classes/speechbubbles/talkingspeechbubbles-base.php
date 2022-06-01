<?php

abstract class PoP_Module_Processor_TalkingSpeechBubblesBase extends PoP_Module_Processor_SpeechBubblesBase
{
    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);
    
        $ret[GD_JS_CLASSES]['bubble-wrapper'] = 'littleguy-talking';
        
        return $ret;
    }
}
