<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_ScriptFrameLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_SCRIPTFRAME];
    }

    public function getLayoutSubmodule(array $module)
    {
        return null;
    }

    public function getScriptSubmodule(array $module)
    {
        return null;
    }

    public function getSubmodules(array $module): array
    {
        return array_merge(
            parent::getSubmodules($module),
            array(
                $this->getLayoutSubmodule($module),
                $this->getScriptSubmodule($module),
            )
        );
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $moduleprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $layout = $this->getLayoutSubmodule($module);
        $script = $this->getScriptSubmodule($module);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['layout'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($layout);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['script'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($script);

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        $script = $this->getScriptSubmodule($module);
        $this->setProp($script, $props, 'frame-module', $module);
        parent::initModelProps($module, $props);
    }
}
