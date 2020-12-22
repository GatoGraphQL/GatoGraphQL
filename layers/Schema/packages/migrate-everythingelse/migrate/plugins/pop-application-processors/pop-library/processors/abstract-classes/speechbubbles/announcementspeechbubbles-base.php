<?php

abstract class PoP_Module_Processor_AnnouncementSpeechBubblesBase extends PoP_Module_Processor_SpeechBubblesBase
{
    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);
    
        $ret[GD_JS_CLASSES]['bubble-wrapper'] = 'littleguy-announcement';
        
        return $ret;
    }
    
    public function initModelProps(array $module, array &$props)
    {

        // Artificial property added to identify the template when adding module-resources
        $this->setProp($module, $props, 'resourceloader', 'littleguy');
        parent::initModelProps($module, $props);
    }
}
