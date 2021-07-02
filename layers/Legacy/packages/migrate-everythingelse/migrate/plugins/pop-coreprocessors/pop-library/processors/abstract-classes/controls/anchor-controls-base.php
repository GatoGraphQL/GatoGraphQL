<?php

abstract class PoP_Module_Processor_AnchorControlsBase extends PoP_Module_Processor_ControlsBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_CONTROL_ANCHOR];
    }

    public function getHref(array $module, array &$props)
    {
        return '#';
    }

    public function getClasses(array $module)
    {
        return array(
            'text' => 'pop-btn-title'
        );
    }

    public function getTarget(array $module, array &$props)
    {
        return null;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        if ($this->getProp($module, $props, 'make-title')) {
            $ret['make-title'] = true;
        }
        if ($target = $this->getTarget($module, $props)) {
            $ret['target'] = $target;
        }

        $ret[GD_JS_CLASSES] = $this->getClasses($module);

        return $ret;
    }

    public function getMutableonrequestConfiguration(array $module, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($module, $props);

        // Adding in the runtime configuration, because the link can change. Eg:
        // Organization+Members / Members tabs in the author profile
        if ($href = $this->getHref($module, $props)) {
            $ret['href'] = $href;
        }

        return $ret;
    }
}
