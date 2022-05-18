<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_ScriptFrameLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_SCRIPTFRAME];
    }

    public function getLayoutSubmodule(array $component)
    {
        return null;
    }

    public function getScriptSubmodule(array $component)
    {
        return null;
    }

    public function getSubcomponents(array $component): array
    {
        return array_merge(
            parent::getSubcomponents($component),
            array(
                $this->getLayoutSubmodule($component),
                $this->getScriptSubmodule($component),
            )
        );
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $layout = $this->getLayoutSubmodule($component);
        $script = $this->getScriptSubmodule($component);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['layout'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($layout);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['script'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($script);

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        $script = $this->getScriptSubmodule($component);
        $this->setProp($script, $props, 'frame-module', $component);
        parent::initModelProps($component, $props);
    }
}
