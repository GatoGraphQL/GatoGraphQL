<?php

abstract class PoP_Module_Processor_ControlButtonGroupsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_CONTROLBUTTONGROUP];
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        if ($blocktarget = $this->getProp($component, $props, 'control-target')) {
            foreach ($this->getSubcomponents($component) as $subComponent) {
                $this->setProp([$subComponent], $props, 'control-target', $blocktarget);
            }
        }

        $this->appendProp($component, $props, 'class', 'btn-group pop-hide-empty');
        $this->appendProp($component, $props, 'class', 'pop-hidden-print');
        parent::initModelProps($component, $props);
    }
}
