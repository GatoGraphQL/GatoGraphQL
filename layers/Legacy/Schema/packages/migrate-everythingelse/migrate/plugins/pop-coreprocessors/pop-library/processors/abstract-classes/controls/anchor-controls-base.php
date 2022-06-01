<?php

abstract class PoP_Module_Processor_AnchorControlsBase extends PoP_Module_Processor_ControlsBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_CONTROL_ANCHOR];
    }

    public function getHref(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return '#';
    }

    public function getClasses(\PoP\ComponentModel\Component\Component $component)
    {
        return array(
            'text' => 'pop-btn-title'
        );
    }

    public function getTarget(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return null;
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        if ($this->getProp($component, $props, 'make-title')) {
            $ret['make-title'] = true;
        }
        if ($target = $this->getTarget($component, $props)) {
            $ret['target'] = $target;
        }

        $ret[GD_JS_CLASSES] = $this->getClasses($component);

        return $ret;
    }

    public function getMutableonrequestConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($component, $props);

        // Adding in the runtime configuration, because the link can change. Eg:
        // Organization+Members / Members tabs in the author profile
        if ($href = $this->getHref($component, $props)) {
            $ret['href'] = $href;
        }

        return $ret;
    }
}
