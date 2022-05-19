<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_ScriptFrameLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_SCRIPTFRAME];
    }

    public function getLayoutSubcomponent(array $component)
    {
        return null;
    }

    public function getScriptSubcomponent(array $component)
    {
        return null;
    }

    public function getSubcomponents(array $component): array
    {
        return array_merge(
            parent::getSubcomponents($component),
            array(
                $this->getLayoutSubcomponent($component),
                $this->getScriptSubcomponent($component),
            )
        );
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $layout = $this->getLayoutSubcomponent($component);
        $script = $this->getScriptSubcomponent($component);
        $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['layout'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($layout);
        $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['script'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($script);

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        $script = $this->getScriptSubcomponent($component);
        $this->setProp($script, $props, 'frame-component', $component);
        parent::initModelProps($component, $props);
    }
}
