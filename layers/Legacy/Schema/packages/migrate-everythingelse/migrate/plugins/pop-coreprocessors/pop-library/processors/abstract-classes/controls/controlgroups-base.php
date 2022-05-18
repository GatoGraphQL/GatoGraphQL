<?php

abstract class PoP_Module_Processor_ControlGroupsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_CONTROLGROUP];
    }

    public function initModelProps(array $module, array &$props): void
    {
        if ($blocktarget = $this->getProp($module, $props, 'control-target')) {
            foreach ($this->getSubComponentVariations($module) as $submodule) {
                $this->setProp([$submodule], $props, 'control-target', $blocktarget);
            }
        }

        $this->appendProp($module, $props, 'class', 'pop-hidden-print');

        parent::initModelProps($module, $props);
    }
}
