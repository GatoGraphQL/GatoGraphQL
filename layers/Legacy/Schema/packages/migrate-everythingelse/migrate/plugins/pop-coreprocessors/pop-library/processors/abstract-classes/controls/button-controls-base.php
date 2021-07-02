<?php

abstract class PoP_Module_Processor_ButtonControlsBase extends PoP_Module_Processor_ControlsBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_CONTROL_BUTTON];
    }

    public function getType(array $module)
    {
        return 'button';
    }
    public function getBtnClass(array $module, array &$props)
    {
        return 'btn btn-default';
    }
    public function getTextClass(array $module)
    {
        return 'hidden-xs';
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        if ($type = $this->getType($module)) {
            $ret['type'] = $type;
        }
        if ($text_class = $this->getTextClass($module)) {
            $ret[GD_JS_CLASSES]['text'] = $text_class;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        $this->appendProp($module, $props, 'class', $this->getBtnClass($module, $props));
        parent::initModelProps($module, $props);
    }
}
