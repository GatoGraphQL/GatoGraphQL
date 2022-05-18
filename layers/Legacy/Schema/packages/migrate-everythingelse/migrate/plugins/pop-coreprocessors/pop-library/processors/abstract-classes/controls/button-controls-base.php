<?php

abstract class PoP_Module_Processor_ButtonControlsBase extends PoP_Module_Processor_ControlsBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_CONTROL_BUTTON];
    }

    public function getType(array $componentVariation)
    {
        return 'button';
    }
    public function getBtnClass(array $componentVariation, array &$props)
    {
        return 'btn btn-default';
    }
    public function getTextClass(array $componentVariation)
    {
        return 'hidden-xs';
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        if ($type = $this->getType($componentVariation)) {
            $ret['type'] = $type;
        }
        if ($text_class = $this->getTextClass($componentVariation)) {
            $ret[GD_JS_CLASSES]['text'] = $text_class;
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $this->appendProp($componentVariation, $props, 'class', $this->getBtnClass($componentVariation, $props));
        parent::initModelProps($componentVariation, $props);
    }
}
