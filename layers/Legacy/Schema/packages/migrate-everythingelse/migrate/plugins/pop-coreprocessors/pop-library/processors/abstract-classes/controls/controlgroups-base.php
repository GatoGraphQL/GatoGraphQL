<?php

abstract class PoP_Module_Processor_ControlGroupsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_CONTROLGROUP];
    }

    public function initModelProps(array $component, array &$props): void
    {
        if ($blocktarget = $this->getProp($component, $props, 'control-target')) {
            foreach ($this->getSubComponents($component) as $subComponent) {
                $this->setProp([$subComponent], $props, 'control-target', $blocktarget);
            }
        }

        $this->appendProp($component, $props, 'class', 'pop-hidden-print');

        parent::initModelProps($component, $props);
    }
}
