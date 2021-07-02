<?php

abstract class PoP_Module_Processor_ModalsBase extends PoP_Module_Processor_BootstrapComponentsBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_BOOTSTRAPCOMPONENT_MODAL];
    }

    public function getBootstrapcomponentType(array $module)
    {
        return 'modal';
    }

    public function getBodyClass(array $module, array &$props)
    {
        return '';
    }

    public function getDialogClass(array $module, array &$props)
    {
        return '';
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        if ($body_class = $this->getBodyClass($module, $props)) {
            $ret[GD_JS_CLASSES]['body'] = $body_class;
        }
        if ($dialog_class = $this->getDialogClass($module, $props)) {
            $ret[GD_JS_CLASSES]['dialog'] = $dialog_class;
        }
                
        return $ret;
    }
}
