<?php

abstract class PoP_Module_Processor_TalkingSpeechBubblesBase extends PoP_Module_Processor_SpeechBubblesBase
{
    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);
    
        $ret[GD_JS_CLASSES]['bubble-wrapper'] = 'littleguy-talking';
        
        return $ret;
    }
}
