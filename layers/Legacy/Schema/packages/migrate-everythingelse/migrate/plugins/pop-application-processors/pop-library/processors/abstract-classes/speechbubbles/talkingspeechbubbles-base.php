<?php

abstract class PoP_Module_Processor_TalkingSpeechBubblesBase extends PoP_Module_Processor_SpeechBubblesBase
{
    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);
    
        $ret[GD_JS_CLASSES]['bubble-wrapper'] = 'littleguy-talking';
        
        return $ret;
    }
}
