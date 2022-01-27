<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

abstract class PoP_Module_Processor_ViewComponentButtonsBase extends PoP_Module_Processor_PreloadTargetDataButtonsBase
{
    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        if ($header = $this->getHeaderSubmodule($module)) {
            $ret[] = $header;
        }

        return $ret;
    }

    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_VIEWCOMPONENT_BUTTON];
    }

    public function getHeaderSubmodule(array $module): ?array
    {
        return null;
    }
    public function getUrl(array $module, array &$props)
    {
        return null;
    }

    public function headerShowUrl(array $module)
    {
        return false;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        if ($url = $this->getUrl($module, $props)) {
            $ret['url'] = $url;
        }

        if ($header = $this->getHeaderSubmodule($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['header'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($header);
        }

        return $ret;
    }
}
