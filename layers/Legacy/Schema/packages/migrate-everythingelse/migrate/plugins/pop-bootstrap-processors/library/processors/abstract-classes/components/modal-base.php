<?php

abstract class PoP_Module_Processor_ModalsBase extends PoP_Module_Processor_BootstrapComponentsBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_BOOTSTRAPCOMPONENT_MODAL];
    }

    public function getBootstrapcomponentType(\PoP\ComponentModel\Component\Component $component)
    {
        return 'modal';
    }

    public function getBodyClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return '';
    }

    public function getDialogClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return '';
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        if ($body_class = $this->getBodyClass($component, $props)) {
            $ret[GD_JS_CLASSES]['body'] = $body_class;
        }
        if ($dialog_class = $this->getDialogClass($component, $props)) {
            $ret[GD_JS_CLASSES]['dialog'] = $dialog_class;
        }
                
        return $ret;
    }
}
