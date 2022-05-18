<?php

abstract class PoP_Module_Processor_AnnouncementSpeechBubblesBase extends PoP_Module_Processor_SpeechBubblesBase
{
    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $ret[GD_JS_CLASSES]['bubble-wrapper'] = 'littleguy-announcement';

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {

        // Artificial property added to identify the template when adding module-resources
        $this->setProp($componentVariation, $props, 'resourceloader', 'littleguy');
        parent::initModelProps($componentVariation, $props);
    }
}
