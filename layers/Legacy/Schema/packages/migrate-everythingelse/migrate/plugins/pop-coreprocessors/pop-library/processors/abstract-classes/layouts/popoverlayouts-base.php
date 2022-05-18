<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_PopoverLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_POPOVER];
    }

    public function getLayoutSubmodule(array $module)
    {
        return null;
    }
    public function getLayoutContentSubmodule(array $module)
    {
        return null;
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        if ($layout = $this->getLayoutSubmodule($module)) {
            $ret[] = $layout;
        }
        if ($layout_content = $this->getLayoutContentSubmodule($module)) {
            $ret[] = $layout_content;
        }

        return $ret;
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);
        $this->addJsmethod($ret, 'popover');
        return $ret;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $moduleprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        if ($layout = $this->getLayoutSubmodule($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['layout'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($layout);
        }
        if ($layout_content = $this->getLayoutContentSubmodule($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['layout-content'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($layout_content);
        }
        
        return $ret;
    }
}
