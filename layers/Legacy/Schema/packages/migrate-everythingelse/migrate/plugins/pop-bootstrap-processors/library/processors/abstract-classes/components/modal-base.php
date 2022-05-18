<?php

abstract class PoP_Module_Processor_ModalsBase extends PoP_Module_Processor_BootstrapComponentsBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_BOOTSTRAPCOMPONENT_MODAL];
    }

    public function getBootstrapcomponentType(array $componentVariation)
    {
        return 'modal';
    }

    public function getBodyClass(array $componentVariation, array &$props)
    {
        return '';
    }

    public function getDialogClass(array $componentVariation, array &$props)
    {
        return '';
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        if ($body_class = $this->getBodyClass($componentVariation, $props)) {
            $ret[GD_JS_CLASSES]['body'] = $body_class;
        }
        if ($dialog_class = $this->getDialogClass($componentVariation, $props)) {
            $ret[GD_JS_CLASSES]['dialog'] = $dialog_class;
        }
                
        return $ret;
    }
}
