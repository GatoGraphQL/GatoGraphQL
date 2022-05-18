<?php

abstract class PoP_Module_Processor_AnchorControlsBase extends PoP_Module_Processor_ControlsBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_CONTROL_ANCHOR];
    }

    public function getHref(array $componentVariation, array &$props)
    {
        return '#';
    }

    public function getClasses(array $componentVariation)
    {
        return array(
            'text' => 'pop-btn-title'
        );
    }

    public function getTarget(array $componentVariation, array &$props)
    {
        return null;
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        if ($this->getProp($componentVariation, $props, 'make-title')) {
            $ret['make-title'] = true;
        }
        if ($target = $this->getTarget($componentVariation, $props)) {
            $ret['target'] = $target;
        }

        $ret[GD_JS_CLASSES] = $this->getClasses($componentVariation);

        return $ret;
    }

    public function getMutableonrequestConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($componentVariation, $props);

        // Adding in the runtime configuration, because the link can change. Eg:
        // Organization+Members / Members tabs in the author profile
        if ($href = $this->getHref($componentVariation, $props)) {
            $ret['href'] = $href;
        }

        return $ret;
    }
}
