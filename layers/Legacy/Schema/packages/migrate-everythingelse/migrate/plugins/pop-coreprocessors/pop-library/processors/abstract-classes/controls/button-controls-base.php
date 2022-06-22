<?php

abstract class PoP_Module_Processor_ButtonControlsBase extends PoP_Module_Processor_ControlsBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_CONTROL_BUTTON];
    }

    public function getType(\PoP\ComponentModel\Component\Component $component)
    {
        return 'button';
    }
    public function getBtnClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return 'btn btn-default';
    }
    public function getTextClass(\PoP\ComponentModel\Component\Component $component)
    {
        return 'hidden-xs';
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
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

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        $this->appendProp($component, $props, 'class', $this->getBtnClass($component, $props));
        parent::initModelProps($component, $props);
    }
}
