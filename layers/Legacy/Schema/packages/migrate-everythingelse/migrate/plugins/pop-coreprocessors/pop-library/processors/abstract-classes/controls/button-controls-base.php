<?php

abstract class PoP_Module_Processor_ButtonControlsBase extends PoP_Module_Processor_ControlsBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_CONTROL_BUTTON];
    }

    public function getType(array $component)
    {
        return 'button';
    }
    public function getBtnClass(array $component, array &$props)
    {
        return 'btn btn-default';
    }
    public function getTextClass(array $component)
    {
        return 'hidden-xs';
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        if ($type = $this->getType($component)) {
            $ret['type'] = $type;
        }
        if ($text_class = $this->getTextClass($component)) {
            $ret[GD_JS_CLASSES]['text'] = $text_class;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        $this->appendProp($component, $props, 'class', $this->getBtnClass($component, $props));
        parent::initModelProps($component, $props);
    }
}
