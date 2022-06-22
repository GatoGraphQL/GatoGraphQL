<?php

abstract class PoP_Module_Processor_RadioButtonControlsBase extends PoP_Module_Processor_ControlsBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_CONTROL_RADIOBUTTON];
    }
}
