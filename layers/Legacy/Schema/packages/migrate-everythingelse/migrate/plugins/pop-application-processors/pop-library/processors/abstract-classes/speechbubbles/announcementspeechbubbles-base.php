<?php

abstract class PoP_Module_Processor_AnnouncementSpeechBubblesBase extends PoP_Module_Processor_SpeechBubblesBase
{
    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $ret[GD_JS_CLASSES]['bubble-wrapper'] = 'littleguy-announcement';

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {

        // Artificial property added to identify the template when adding component-resources
        $this->setProp($component, $props, 'resourceloader', 'littleguy');
        parent::initModelProps($component, $props);
    }
}
