<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_ScriptFrameLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_SCRIPTFRAME];
    }

    public function getLayoutSubmodule(array $componentVariation)
    {
        return null;
    }

    public function getScriptSubmodule(array $componentVariation)
    {
        return null;
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        return array_merge(
            parent::getSubComponentVariations($componentVariation),
            array(
                $this->getLayoutSubmodule($componentVariation),
                $this->getScriptSubmodule($componentVariation),
            )
        );
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $layout = $this->getLayoutSubmodule($componentVariation);
        $script = $this->getScriptSubmodule($componentVariation);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['layout'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($layout);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['script'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($script);

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $script = $this->getScriptSubmodule($componentVariation);
        $this->setProp($script, $props, 'frame-module', $componentVariation);
        parent::initModelProps($componentVariation, $props);
    }
}
