<?php

abstract class PoP_Module_Processor_ControlButtonGroupsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_CONTROLBUTTONGROUP];
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        if ($blocktarget = $this->getProp($componentVariation, $props, 'control-target')) {
            foreach ($this->getSubComponentVariations($componentVariation) as $submodule) {
                $this->setProp([$submodule], $props, 'control-target', $blocktarget);
            }
        }

        $this->appendProp($componentVariation, $props, 'class', 'btn-group pop-hide-empty');
        $this->appendProp($componentVariation, $props, 'class', 'pop-hidden-print');
        parent::initModelProps($componentVariation, $props);
    }
}
